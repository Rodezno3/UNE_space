<?php 
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
require 'php/fun/conexion.php';
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $sql = "SELECT * FROM Cliente_Frecuente WHERE Nombre LIKE '%$search%' OR Email LIKE '%$search%'";
    $query = $conn->prepare($sql);
    /*$query->bindParam(':search' , $_GET['search']);
    $query->bindParam(':mail' , $_GET['search']);*/
    $query->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Casa Xoxoctic</title>
    <!-- estilos -->
    <link rel="stylesheet" href="css/estilos_header.css">
    <link rel="stylesheet" href="css/estilos_menu.css">
    <link rel="stylesheet" href="css/estilos_orden.css">
    <link rel="stylesheet" href="css/estilos_index.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link rel="stylesheet" href="css/estilos_frecuencia.css">
    <!-- scripts -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <?php 
    date_default_timezone_set('America/El_Salvador');
    $hora = date("H:i:s" , time());
    $fstar = strtotime("20:00:00");
    if(time() >= $fstar){
    ?>
    <script type="text/javascript">
      alert("Ya ha finalizado el día.");
    </script>
    <?php } ?>
</head>
<body>
    <?php require "php/header.php"; ?>
    <section id="seccion">
        <h1>Clientes Frecuentes</h1>
        <form action="" method="get" id="f_head">
            <input type="text" name="search" id="search" placeholder="Buscar por Nombre o Correo">
            <input type="submit" value="Buscar">
            <a href="frecuente.php?ingresar=true">Ingresar</a>
        </form>
        <?php if(isset($_GET['search'])){ ?>
        <article id="tren">
            <table columspace=0>
                <tr id="thead">
                    <td>ID</td>
                    <td>Nombre</td>
                    <td>Email</td>
                    <td>Teléfono</td>
                    <td>Dirección</td>
                    <td>Ciudad</td>
                    <td>Primera Visita</td>
                    <td>Ultima Visita</td>
                    <td>Total Visitas</td>
                    <td>Gasto Total</td>
                </tr>
                <?php 
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr>
                    <td><?= $row['IDCF']; ?></td>
                    <td><?= $row['Nombre']; ?></td>
                    <td><?= $row['Email']; ?></td>
                    <td><?= $row['Telefono']; ?></td>
                    <td><?= $row['Direccion']; ?></td>
                    <td><?= $row['Ciudad']; ?></td>
                    <td><?= $row['Primera']; ?></td>
                    <td><?= $row['Ultima']; ?></td>
                    <td><?= $row['TotalVisita']; ?></td>
                    <td><?= $row['Total']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </article>
        <?php } elseif(isset($_GET['ingresar'])) { ?>
        <article id="ingresar">
            <h3>Ingresar Cliente Frecuente</h3>
            <form action="php/fun/add_frecuente.php" method="post">
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="text" name="mail" placeholder="Correo" required>
                <input type="text" name="phon" placeholder="Teléfono">
                <input type="text" name="addr" placeholder="Dirección">
                <input type="text" name="city" placeholder="Ciudad"><br>
                <input type="submit" value="Ingresar">
            </form>
        </article>
        <?php } ?>
    </section>
</body>
</html>