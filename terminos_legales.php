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
    <title>Términos Legales</title>
    <!-- <link rel="stylesheet" href=".\css\style.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: block;
            justify-content: center;
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
    <center>
    <div class="content">
        <h2 class="title">Términos Legales</h2>
        <p>Al acceder y utilizar nuestros servicios, aceptas estar sujeto a los 
            <br>términos y condiciones que se detallan a continuación. Estos 
            <br>términos son un acuerdo entre tú y nuestra empresa, y tienen como 
            <br>objetivo regular el uso de nuestros servicios de manera justa y 
            <br>transparente.
         </p>

        <h2 class="subheading">1. Uso de los Servicios</h2>
        <p>Al utilizar nuestros servicios, te comprometes a hacerlo de manera 
            <br>responsable y legal. No podrás utilizar nuestros servicios para 
            <br>realizar actividades ilegales, fraudulentas, o que violen derechos 
            <br>de propiedad intelectual. Nos reservamos el derecho de suspender o 
            <br>cancelar el acceso a nuestros servicios en caso de violación de estos 
            <br>términos.
        </p>

        <h2 class="subheading">2. Propiedad Intelectual</h2>
        <p>Todo el contenido de nuestros servicios, incluyendo texto, gráficos, 
            <br>logos, imágenes y software, está protegido por derechos de autor y 
            <br>propiedad intelectual. No está permitido copiar, reproducir, 
            <br>distribuir o modificar ninguno de estos materiales sin la autorización 
            <br>previa y por escrito de nuestra empresa.
        </p>

        <h2 class="subheading">3. Limitación de Responsabilidad</h2>
        <p>No nos hacemos responsables de ningún daño directo, indirecto, incidental 
            <br>o consecuente que pueda surgir del uso de nuestros servicios. 
            <br>Esto incluye, pero no se limita a, daños por pérdida de datos, 
            <br>interrupción del servicio o errores en el contenido proporcionado.
        </p>

        <h2 class="subheading">4. Modificaciones de los Términos</h2>
        <p>Nos reservamos el derecho de modificar estos términos en cualquier 
            <br>momento, y cualquier cambio será comunicado oportunamente a través de 
            <br>los canales adecuados. Es tu responsabilidad revisar regularmente estos
            <br> términos para estar al tanto de cualquier modificación.
        </p>

        <h2 class="subheading">5. Ley Aplicable</h2>
        <p>Este acuerdo se regirá e interpretará de acuerdo con las leyes del país 
            <br>donde se encuentre registrada nuestra empresa. Cualquier disputa 
            <br>relacionada con estos términos será resuelta en los tribunales 
            <br>competentes de esa jurisdicción.
        </p>
    </div>
    </center>
    <footer class="footer">
        <p>&copy; 2024 Mi Empresa | <a href="terminos_legales.php" class="footer-link">Términos Legales</a> | <a href="politica_de_datos.php" class="footer-link">Política de Datos</a></p>
    </footer>
</body>
</html>
