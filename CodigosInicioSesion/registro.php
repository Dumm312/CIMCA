<?php
session_start();
include("conexion.php");

// Verificar si el usuario está logueado
if (isset($_SESSION['id_Usuario'])) {
    header("Location: Carrito.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si los campos existen antes de accederlos
    $correo = $_POST['correo'] ?? '';

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener valores del formulario
    $nombre = $_POST['nombre'];
    $ap_Pat = $_POST['ap_Pat'];
    $ap_Mat = !empty($_POST['ap_Mat']) ? $_POST['ap_Mat'] : NULL; // Permitir que sea NULL
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encripta la contraseña

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Preparar la inserción en USUARIO
        $stmt1 = $conexion->prepare("INSERT INTO usuario (nombre, ap_Pat, ap_Mat, direccion, telefono, correo, tipo) VALUES (?, ?, ?, ?, ?, ?, 'CL')");
        $stmt1->bind_param("ssssss", $nombre, $ap_Pat, $ap_Mat, $direccion, $telefono, $correo);
        $stmt1->execute();

        // Obtener el ID del usuario recién insertado
        $id_usuario = $conexion->insert_id;

        // Preparar la inserción en CLIENTE
        $stmt2 = $conexion->prepare("INSERT INTO cliente (id_Usuario, contraseña) VALUES (?, ?)");
        $stmt2->bind_param("is", $id_usuario, $password);
        $stmt2->execute();

        // Confirmar la transacción
        $conexion->commit();
        echo "✅ Registro exitoso. <a href='login.php'>Iniciar sesión</a>";

    } catch (Exception $e) {
        // En caso de error, deshacer los cambios
        $conexion->rollback();
        echo "❌ Error al registrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papelería "La UC"</title> <!-- Nombre en la Ventana -->
    <!--Estilos con CSS-->

    <!--Slider-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.js"></script>

</head>
<body>
    <div class="wrapper">
        <!--Banner-->
        <div class="banner-wrapper">
            <header>
                <div class="header-inner">
                    <a href="Inicio.html" id="logo">
                    </a>

                    <nav>
                        <ul>
                            <li> <a href="Inicio.html">INICIO</a> </li>
                            <li> <a href="QuienesSomos.html">¿QUIÉNES SOMOS?</a> </li>
                            <li> <a href="Productos.php">PRODUCTOS</a> </li>
                            <li> <a href="Sucursales.html">SUCURSALES</a> </li>
                            <li> <a href="Contacto.php">CONTACTO</a> </li>
                            <li> <a href="Promociones.php">PROMOCIONES</a> </li>
                            <!-- <li> <a href="registro.php" > <img id="imagenCarrito" src="Imagenes/Carrito.png" alt="imagenCarrito"> </a> </li> -->
                        </ul>
                    </nav>

                </div>
            </header>

            <div class="slide-wrap"></div>
        </div>

    </div>

    <p>¿Ya tienes una cuenta? <a href="login.php"><button>Iniciar sesión</button></a></p>

    <h1 id="TituloRegistro">Regístrate en nuestra papelería</h1>
    <div class="Registro">
            <form method="POST">
            <label>Nombre:</label> <input type="text" name="nombre" required><br>
            <label>Apellido Paterno:</label> <input type="text" name="ap_Pat" required><br>
            <label>Apellido Materno:</label> <input type="text" name="ap_Mat"><br>
            <label>Dirección:</label> <input type="text" name="direccion" required><br>
            <label>Teléfono:</label> <input type="text" name="telefono" maxlength="15" required><br>
            <label>Correo:</label> <input type="email" name="correo" required><br>
            <label>Contraseña:</label> <input type="password" name="contraseña" required><br>
            <button type="submit">Registrarse</button>
        </form>

    </div>

    <style>
       body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: url(Imagenes/Fondo.jpg) no-repeat center center fixed;
        background-size: cover;
        color: #000000;
        text-align: center;
        }
        
        /* Estilos del header */
        header {
            background: rgba(16, 194, 9, 0.8);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin-block-start: auto;
            padding: 0 20%;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #f4a261;
        }

        #TituloRegistro{
            margin-top: 20px;
            font-size: 36px;
            color: #000000;
        }

        p {
            margin-top: 100px;
            margin-left: 250px;
            text-align: center;
            font-size: 18px;
            max-width: 800px;
            line-height: 1;
        }

        /* Contenedor del formulario */
        .Registro {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 auto;
            padding: 20px;
            max-width: 900px; /* Aumentamos el tamaño */
            width: 100%;
            border-radius: 10px;
        }

        /* Formulario en columna */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        /* Estilo de los inputs y textarea */
        form input, form textarea {
            display: center;
            width: 95%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        /* Etiquetas para mayor claridad */
        form label {
            font-weight: bold;
            margin-top: 10px;
        }

        /* Área de texto */
        form textarea {
            resize: none;
            height: 100px;
        }

        /* Botón */
        button {
            padding: 12px;
            background-color: #FFA726;
            border: none;
            color: black;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #FF9800;
        }

        #imagenSobre {
            margin-left: 70%;
            height: 110px;
        }

    </style>

</body>
</html>