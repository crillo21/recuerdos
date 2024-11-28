<?php
session_start();
include('db.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Manejar el formulario de subida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['file'];
    $file_name = basename($file['name']);
    $file_tmp = $file['tmp_name'];
    $file_type = mime_content_type($file_tmp); // Obtener el tipo MIME del archivo
    $file_size = $file['size'];
    $max_size = 5 * 1024 * 1024; // 5MB máximo

    // Validar el tipo de archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi'];
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Tipo de archivo no permitido. Solo se pueden subir imágenes o videos.');</script>";
        exit();
    }

    // Validar el tamaño del archivo
    if ($file_size > $max_size) {
        echo "<script>alert('El archivo es demasiado grande. El tamaño máximo permitido es 5MB.');</script>";
        exit();
    }

    // Verificar y crear carpeta si no existe
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generar un nombre único para el archivo
    $target_file = $target_dir . uniqid() . "_" . $file_name;

    // Mover el archivo
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Insertar la información en la base de datos
        $stmt = $conn->prepare("INSERT INTO archivos (user_id, file_name, file_path, file_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $file_name, $target_file, $file_type);

        if ($stmt->execute()) {
            echo "<script>alert('Archivo subido exitosamente.');</script>";
        } else {
            echo "<script>alert('Error al subir el archivo en la base de datos: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error al mover el archivo. Por favor verifica los permisos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        .container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .custom-file-label:hover {
            background-color: #0056b3;
        }

        .file-name {
            font-size: 0.9rem;
            color: #555;
            margin-top: 10px;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .animated {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        header {
            background-color: #4CAF50;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 1000px;
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

    <div class="container animated">
        <h1>Subir Archivo</h1>
        <form method="POST" enctype="multipart/form-data">
            <label class="custom-file-label" for="file">Seleccionar archivo</label>
            <input type="file" name="file" id="file" required>
            <p class="file-name">Ningún archivo seleccionado</p>
            <br>
            <input class="submit-btn" type="submit" value="Subir archivo">
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('file');
        const fileNameDisplay = document.querySelector('.file-name');

        fileInput.addEventListener('change', () => {
            const fileName = fileInput.files[0]?.name || 'Ningún archivo seleccionado';
            fileNameDisplay.textContent = fileName;
        });
    </script>
</body>
</html>
