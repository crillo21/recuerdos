<?php
session_start();
include('db.php');

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Procesar la eliminación de imágenes seleccionadas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_images'])) {
    $images_to_delete = $_POST['selected_images'] ?? [];

    if (!empty($images_to_delete)) {
        foreach ($images_to_delete as $image_id) {
            // Eliminar el archivo de la carpeta si es necesario
            $stmt = $conn->prepare("SELECT file_path FROM archivos WHERE id = ?");
            $stmt->bind_param("i", $image_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $file_path = $row['file_path'];
                if (file_exists($file_path)) {
                    unlink($file_path); // Eliminar físicamente el archivo
                }
            }

            $stmt->close();

            // Eliminar el registro de la base de datos
            $stmt_delete = $conn->prepare("DELETE FROM archivos WHERE id = ?");
            $stmt_delete->bind_param("i", $image_id);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
    }
}

// Consultar las imágenes para mostrar
$stmt = $conn->prepare("SELECT id, file_name, file_path FROM archivos WHERE file_type LIKE 'image/%'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Imágenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        button {
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

        button:hover {
            background-color: #d32f2f;
        }
        header {
            background-color: #4CAF50;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 1000px;
            margin-top:30px;
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
    <center>
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
    </center>
    <div class="container">
        <h3>Eliminar Imágenes</h3>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Miniatura</th>
                        <th>Nombre del Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_images[]" value="<?= htmlspecialchars($row['id']); ?>">
                            </td>
                            <td>
                                <img src="<?= htmlspecialchars($row['file_path']); ?>" alt="<?= htmlspecialchars($row['file_name']); ?>">
                            </td>
                            <td><?= htmlspecialchars($row['file_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <br>
            <button type="submit" name="delete_images">Eliminar Seleccionados</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
?>
