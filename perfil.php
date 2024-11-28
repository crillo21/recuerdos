<?php
session_start();
include('db.php');

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Función para cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Cerrar la sesión
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consultar los archivos del usuario autenticado
$stmt = $conn->prepare("SELECT * FROM archivos WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Archivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            margin-top:30px;
            margin-left:20px ;
            margin-right:20px ;
        }

        header h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        header ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        header ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        header ul li a:hover {
            text-decoration: underline;
        }

        header form {
            margin: 0;
        }

        header form button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        header form button:hover {
            background-color: #d32f2f;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h3 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .gallery img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .gallery img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .gallery a {
            text-decoration: none;
        }
        .content {
            margin-bottom: 50px; /* Espacio para el footer */
        }

        /* Estilos para el título principal */
        .title {
            color: #4CAF50;
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Estilos para los subtítulos */
        .subheading {
            font-size: 1.5em;
            color: #333;
            margin-top: 20px;
        }

        /* Estilos del footer */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            /* position: fixed; */
            width: 100%;
            bottom: 0;
        }

        .footer-link {
            color: white;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header con botón de cerrar sesión -->
    <header>
        <h1>Bienvenido a tu espacio seguro</h1>
        <ul>
            <li><a href=".\perfil.php">Perfil</a></li>
            <li><a href=".\subir_archivo.php">Subir Archivos</a></li>
            <li><a href=".\eliminar_imagenes.php">Eliminar Imagenes</a></li>
        </ul>
        <form method="POST">
            <button type="submit" name="logout">Cerrar Sesión</button>
        </form>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <h3>Galería de Archivos</h3>
        <div class="gallery">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php if (strpos($row['file_type'], 'image/') === 0): ?>
                    <!-- Mostrar imagen si es de tipo imagen -->
                    <a href="<?= htmlspecialchars($row['file_path']); ?>" target="_blank">
                        <img src="<?= htmlspecialchars($row['file_path']); ?>" alt="<?= htmlspecialchars($row['file_name']); ?>">
                    </a>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    </div>
    <footer class="footer"></p>
        <p>&copy; 2024 Cristian Criollo|
             
            <a href="terminos_legales.php" class="footer-link">Términos Legales</a> |
            <a href="politica_de_datos.php" class="footer-link">Política de Datos</a>
    </footer>

</body>
</html>

<?php
$stmt->close();
?>
