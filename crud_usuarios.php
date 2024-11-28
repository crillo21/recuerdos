<?php
session_start();
include('db.php'); // Incluir archivo de conexión a la base de datos

// Verificar si está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Procesar eliminación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['delete_user']);
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Consultar todos los usuarios
$stmt = $conn->prepare("SELECT id, username, email, created_at FROM usuarios");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #d32f2f;
        }

        .add-user {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .add-user:hover {
            background-color: #45a049;
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
        <!-- Formulario para cerrar sesión y redirigir a login_admin.php -->
        <form method="POST" action="../view/login_admin.php">
            <button type="submit" class="logout">Cerrar Sesión</button>
        </form>
    </header>

    <h1>Gestión de Usuarios</h1>

    <!-- Botón para agregar nuevo usuario -->
    <form method="GET" action="crud_nuevo_usuario.php">
        <button class="add-user">Agregar Usuario</button>
    </form>

    <!-- Tabla con usuarios -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Fecha De Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <!-- Botón para eliminar usuario -->
                        <form method="POST" style="display:inline;">
                            <button type="submit" name="delete_user" value="<?= $row['id']; ?>">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
?>
