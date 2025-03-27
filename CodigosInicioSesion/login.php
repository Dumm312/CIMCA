<?php
session_start();
include("conexion.php");

if (isset($_SESSION['id_Usuario'])) {
    // Si ya hay una sesión activa, redirige a otra página (por ejemplo, al carrito)
    header("Location: Carrito.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['contraseña']; // Recibe la contraseña en texto plano

    // Verificar si el usuario existe
    $sql = "SELECT u.id_Usuario, c.contraseña 
            FROM usuario u 
            JOIN cliente c ON u.id_Usuario = c.id_Usuario
            WHERE u.correo = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_Usuario, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) { // Verifica la contraseña
            $_SESSION['correo'] = $correo;
            $_SESSION['id_Usuario'] = $id_Usuario;
            header("Location: Carrito.php");
            exit();
        } else {
            echo "<script>alert('❌ Contraseña incorrecta'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('❌ Usuario no encontrado.'); window.history.back();</script>";
    }

    $stmt->close();
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

    <h1 id="TituloLogin">Inicia sesión en nuestra papelería</h1>
    <div class="Login">
        <form method="POST" action="login.php">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" required><br>

            <button type="submit" name="login">Iniciar Sesión</button>
        </form>

        <p>¿Aún no tienes una cuenta? <a href="registro.php"><button id="Registrarse">Regístrate</button></a></p>

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

        #TituloLogin{
            margin-top: 100px;
            font-size: 36px;
            color: #000000;
        }

        p {
            text-align: center;
            font-size: 18px;
            max-width: 800px;
            margin: 20px auto;
            line-height: 1;
        }

        /* Contenedor del formulario */
        .Login {
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

        #Registrarse {
            padding: 12px;
            background-color:rgb(117, 179, 173);
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
