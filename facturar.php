<?php 
require "php/fun/conexion.php";
$total = 0; //PARA MIENTRAS
if(isset($_GET['final']) && isset($_GET['tipo']) && isset($_GET['orden'])){
    $id = $_GET['final'];
    $total = 0;
    $tipo = 0;
    $f = 0;
    if($_GET['tipo'] == 1){
        $sql = "SELECT * FROM Descripcion_C WHERE IDAC=:id";
        $query = $conn->prepare($sql);
        $query->bindParam(':id' , $id);
        $query->execute();
        while($qow = $query->fetch(PDO::FETCH_ASSOC)){
            $s = "SELECT Precio FROM Menu_C WHERE IDMEC=:id";
            $q = $conn->prepare($s);
            $q->bindParam(':id' , $qow['IDMEC']);
            $q->execute();
            $r = $q->fetch(PDO::FETCH_ASSOC);
            $total = $total + ($qow['Cantidad'] * $r['Precio']);
        }
        /*-----*/
        $xql = "SELECT IDCF FROM Activo_C WHERE IDAC=:id";
        $xuery = $conn->prepare($xql);
        $xuery->bindParam(':id' , $id);
        $xuery->execute();
        $xow = $xuery->fetch(PDO::FETCH_ASSOC);
        if(!empty($xow['IDCF'])){
            $f = 1;
            $zql = "SELECT Nombre , Email FROM Cliente_Frecuente WHERE IDCF=:id";
            $zuery = $conn->prepare($zql);
            $zuery->bindParam(':id' , $xow['IDCF']);
            $zuery->execute();
            $zow = $zuery->fetch(PDO::FETCH_ASSOC);
            $name = $zow['Nombre'];
            $mail = $zow['Email'];
        }
        /*-----*/
    } else {
        $tipo = 1;
        $sql = "SELECT * FROM Descripcion_T WHERE IDAT=:id";
        $query = $conn->prepare($sql);
        $query->bindParam(':id' , $id);
        $query->execute();
        while($qow = $query->fetch(PDO::FETCH_ASSOC)){
            $s = "SELECT Precio FROM Articulo_T WHERE IDART=:id";
            $q = $conn->prepare($s);
            $q->bindParam(':id' , $qow['IDART']);
            $q->execute();
            $r = $q->fetch(PDO::FETCH_ASSOC);
            $total = $total + ($qow['Cantidad'] * $r['Precio']);
        }
    }
    
} else {
    //header("location: inicio.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <title>Casa Xoxoctic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- estilos -->
    <link rel="stylesheet" href="css/estilos_header.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link rel="stylesheet" href="css/boilerplate.css">
    <link rel="stylesheet" href="css/estilos_index.css">
    <link rel="stylesheet" href="css/estilos_orden.css">
    <link rel="stylesheet" href="css/estilos_block_activos.css">
    <link rel="stylesheet" href="css/estilos_factura.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Dosis:wght@500&display=swap" rel="stylesheet">
    <!-- scripts -->
    <script type="text/javascript">
    var total = <?= $total; ?>;
    </script>
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/respond.min.js"></script>
    <script type="text/javascript" src="js/panel.js"></script>
</head>
<body>
    <?php require "php/header.php"; ?>
    <div id="panel_factura">
        <section id="cubo">
            <h1>Total = $<?= $total; ?></h1>
            <p>Digite el pago: </p>
            <input type="text" name="back" maxlength="10" id="pin"><br>
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
                    <li id=""></li>
                    <li id="0">0</li>
                    <li id=""></li>
                </ul>
                <ul>
                    <li id="x" class="cancel">X</li>
                    <li id="p">.</li>
                    <li id="okf" class="go">Ok</li>
                </ul>
            </article>
        </section>
    </div>
    <h1 id="vuelto">Vuelto: $ <input type="text" id="back" disabled></h1>
    <div class="lip">
    <?php 
    if($_GET['orden'] == 1){ 
        if($tipo == 0){
    ?>
    <a href="print/ticket_cafe.php?id=<?= $id; ?>&orden=1" class="lip"><button class="generar">Generar Factura</button></a>
    <!-- <a href="php/fun/finalizar_orden.php?id=<?= $id; ?>&tipo=<?= $tipo; ?>" class="lip"><button class="generar">Generar Factura</button></a> -->
    <?php      
        } elseif($tipo == 1){
    ?>
    <a href="print/ticket_tienda.php?id=<?= $id; ?>&" class="lip"><button class="generar">Generar Factura</button></a>
    <!-- <a href="php/fun/finalizar_orden_tienda.php?id=<?= $id; ?>&tipo=<?= $tipo; ?>" class="lip"><button class="generar">Generar Factura</button></a> -->
    <?php        
        } else {
    ?>
    <a href=""><button>Generar Factura</button></a>
    <?php        
        }
    ?>
    
    <?php 
    } elseif($_GET['orden'] == 2){ 
        if($tipo == 0){
    ?>
    <form action="mail_cofe.php?id=<?= $id; ?>" method="post" class="correo">
        <div class="cm_box">
            <label for="mail">Correo: </label>
            <input type="email" name="mail" id="mail" <?php if($f == 1){ echo "value='".$mail."'"; } ?>>
        </div>
        <div class="cm_box">
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" <?php if($f == 1){ echo "value='".$name."'"; } ?> >
        </div>
        <input type="submit" value="Generar Factura">
    </form>
    <?php
        } elseif($tipo == 1){
    ?>
    <form action="mail_tienda.php?id=<?= $id; ?>" method="post" class="correo">
        <div class="cm_box">
            <label for="mail">Correo: </label>
            <input type="email" name="mail" id="mail" <?php if($f == 1){ echo "value='".$mail."'"; } ?> >
        </div>
        <div class="cm_box">
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" <?php if($f == 1){ echo "value='".$name."'"; } ?> >
        </div>
        <input type="submit" value="Generar Factura">
    </form>
    <?php        
        } else {
     ?>
     <form action="" method="post" class="correo">
        <div class="cm_box">
            <label for="mail">Correo: </label>
            <input type="email" name="mail" id="mail">
        </div>
        <div class="cm_box">
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name">
        </div>
        <input type="submit" value="Generar Factura">
    </form>
    <?php       
        }
    ?>
    </div>
    <?php } else { ?>
    <?php } ?>
</body>
</html>