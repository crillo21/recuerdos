<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Usar consultas preparadas para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Nuevo registro creado con exito');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href=".\css\style.css"> <!-- Opcional: Archivo de estilo CSS -->
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="index.php" method="POST">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Registrarse">
        </form>

        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
