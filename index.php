<?php 
session_start();
$mensaje = "";
if(isset($_POST['pin'])){
    if(strlen($_POST['pin']) == 4){
        if($_POST['pin'] == 4209){
            $_SESSION['mesero'] = "Isaac";
            header("location: inicio.php");
        } elseif($_POST['pin'] == 1219){
            $_SESSION['mesero'] = "Jocelyn";
            header("location: inicio.php");
        } elseif($_POST['pin'] == 1234){
            $_SESSION['mesero'] = "Eric";
            header("location: inicio.php");
        } elseif($_POST['pin'] == 2021){
            $_SESSION['mesero'] = "Melissa";
            header("location: inicio.php");
        }  elseif($_POST['pin'] == 7852){
            $_SESSION['mesero'] = "Invitado";
            header("location: inicio.php");
        } else {
            $mensaje = "Pin incorrecto";
        }
    } else {
        $mensaje = "Ingrese los datos correctamente";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Casa Xoxoctic</title>
    <link rel="stylesheet" href="css/estilos_index.css">
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/panel.js"></script>
</head>
<body>
    <section id="cubo">
        <h1>Inicio de Sesión</h1>
        <p><?= $mensaje; ?></p>
        <form action="" method="post">
            <input type="password" name="pin" maxlength="4" id="pin"><br>
            <input type="submit" value="Ingresar" id="boton" style="display: none;">
        </form>
        <article id="panel">
            <ul>
                <li id="1">1</li>
                <li id="2">2</li>
                <li id="3">3</li>
            </ul>
            <ul>
                <li id="4">4</li>
                <li id="5">5</li>
                <li id="6">6</li>
            </ul>
            <ul>
                <li id="7">7</li>
                <li id="8">8</li>
                <li id="9">9</li>
            </ul>
            <ul>
                <li id="x" class="cancel">X</li>
                <li id="0">0</li>
                <li id="g" class="go"><label for="boton">Go</label></li>
            </ul>
        </article>
    </section>
</body>
</html>