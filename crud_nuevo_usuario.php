<?php
session_start();
include('db.php'); // Incluir archivo de conexión a la base de datos

// Verificar si está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Procesar creación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $perfil = $_POST['perfil'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Contraseña encriptada

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, perfil, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $correo, $perfil, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: crud_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 400px;
            display: block;
            margin-top:40px ;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
<header>
        <h1>Parte Del Administrador</h1>
        <ul>
            <li><a href=".\crud_usuarios.php">Usuarios Registrados</a></li>
            <li><a href=".\crud_fotos.php">Fotos Cargadas</a></li>
        </ul>
        <form method="POST" action="../view/login_admin.php">
            <button type="submit" class="logout">Cerrar Sesión</button>
        </form>
    </header>
    <br>
        <center>
            <div class="form-container">
                <h1>Agregar Usuario</h1>
                <form method="POST">
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="email" name="correo" placeholder="Correo" required>
                    <input type="text" name="perfil" placeholder="Perfil (ej. Administrador, Usuario)" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Guardar Usuario</button>
                </form>
            </div>
        </center>
</body>
</html>
