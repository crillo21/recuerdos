<?php
session_start();
include('db.php'); // Incluir archivo de conexión a la base de datos

// Verificar si está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ./index.php");
    exit();
}

// Consultar fotos subidas
$stmt = $conn->prepare("SELECT archivos.id, archivos.file_name, archivos.file_path, archivos.created_at 
                        FROM archivos");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Fotos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
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
            min-width: 100px;  /* Ancho mínimo de 100px */
            min-height: 90px;  /* Alto mínimo de 90px */
            width: 100px;     /* Establecer un ancho fijo de 100px */
            height: 90px;     /* Establecer un alto fijo de 90px */
            object-fit: cover; /* Mantener la proporción de la imagen sin distorsionarla */
        }

        .logout {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .logout:hover {
            background-color: #d32f2f;
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
    <h2>Fotos Subidas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Miniatura</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['file_name']); ?></td>
                    <td>
                        <!-- Enlace para abrir la imagen en una nueva pestaña -->
                        <a href="<?= htmlspecialchars($row['file_path']); ?>" target="_blank">
                            <img src="<?= htmlspecialchars($row['file_path']); ?>" alt="<?= htmlspecialchars($row['file_name']); ?>">
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
?>
