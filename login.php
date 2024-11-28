<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Asegurar que la sesión esté iniciada
}

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include('db.php'); // Incluir archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos no estén vacíos
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
        exit();
    }

    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido. Acceso denegado.");
    }

    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Usar consultas preparadas para prevenir inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    if ($stmt === false) {
        die("Error en la preparación de la consulta.");
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validar si el usuario existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar la contraseña con password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Guardar el ID del usuario en la sesión
            header("Location: perfil.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.');</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.');</script>";
    }

    // Cerrar la consulta preparada
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href=".\css\style.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <!-- Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Campo para nombre de usuario -->
            <label for="username">Nombre de usuario o correo electrónico:</label>
            <input type="text" id="username" name="username" required>

            <!-- Campo para contraseña -->
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <!-- Botón de envío -->
            <input type="submit" value="Iniciar sesión">
        </form>
        <p>¿No tienes cuenta? <a href=".\index.php">Regístrate aquí</a></p>
        <p>Ingresar Como Administrador <a href=".\login_admin.php">Ingresa aqui</a></p>
    </div>
    
</body>
</html>
