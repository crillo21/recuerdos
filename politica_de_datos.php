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
    <title>Política de Manejo de Datos</title>
    <!-- <link rel="stylesheet" href="estilos.css"> -->
     <style>
        
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
                <h2 class="title">Política de Manejo de Datos</h2>
                <p>En nuestra empresa, respetamos tu privacidad y nos comprometemos a 
                    <br>proteger tu información personal. Nuestra política de manejo de datos 
                    <br>tiene como objetivo proporcionar una visión clara de cómo recopilamos, 
                    <br>utilizamos, almacenamos y protegemos tus datos.
                </p>
                    
                    <h2 class="subheading">1. Recopilación de Datos Personales</h2>
                    <p>Recopilamos datos personales cuando utilizas nuestros servicios, te 
                    <br>registras en nuestra página web, o interactúas con nosotros a través 
                    <br>de otros medios. Los datos recopilados pueden incluir, entre otros, 
                    <br>tu nombre, dirección de correo electrónico, dirección de facturación, 
                    <br>y cualquier otra información relevante que nos permita brindarte una 
                    <br>experiencia más personalizada y eficiente.
                </p>

                    <h2 class="subheading">2. Uso de los Datos</h2>
                    <p>Los datos recopilados son utilizados para procesar tus pedidos, 
                    <br>responder consultas, mejorar nuestros servicios, y para ofrecerte 
                    <br>información relevante sobre nuestros productos y servicios. También 
                    <br>podemos utilizar tus datos para mejorar la seguridad y la funcionalidad
                    <br>de nuestros sistemas.
                </p>

                    <h2 class="subheading">3. Almacenamiento y Seguridad</h2>
                    <p>Tomamos medidas de seguridad adecuadas para proteger tus datos contra 
                    <br>el acceso no autorizado, la alteración, divulgación o destrucción. 
                    <br>Nuestros sistemas de almacenamiento de datos están protegidos mediante 
                    <br>protocolos de seguridad avanzados, y solo el personal autorizado tiene 
                    <br>acceso a tus datos personales.
                </p>

                    <h2 class="subheading">4. Derechos del Usuario</h2>
                    <p>Como titular de tus datos personales, tienes derecho a acceder, 
                    <br>rectificar o eliminar cualquier información que tengamos sobre ti. Si 
                    <br>deseas ejercer cualquiera de estos derechos, puedes contactarnos a 
                    <br>través de nuestros canales de soporte, y procederemos con tu solicitud 
                    <br>de acuerdo a la legislación vigente.
                </p>

                    <h2 class="subheading">5. Modificaciones a la Política</h2>
                    <p>Nos reservamos el derecho de modificar esta política de manejo de datos 
                    <br>en cualquier momento. Las actualizaciones serán publicadas en este 
                    <br>mismo documento y serán efectivas de inmediato una vez publicadas. 
                    <br>Recomendamos revisar periódicamente esta política para estar al tanto 
                    <br>de cualquier cambio.
                </p>
            </div>
        </center>
    <footer class="footer">
        <p>&copy; 2024 Mi Empresa | <a href="terminos_legales.php" class="footer-link">Términos Legales</a> | <a href="politica_de_datos.php" class="footer-link">Política de Datos</a></p>
    </footer>
</body>
</html>
