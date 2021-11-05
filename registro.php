<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
date_default_timezone_set('America/El_Salvador'); 
require "php/fun/conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <title>Casa Xoxoctic</title>
    <!-- estilos -->
    <link rel="stylesheet" href="css/estilos_header.css">
    <link rel="stylesheet" href="css/estilos_block_activos.css">
    <link rel="stylesheet" href="css/estilos_factura.css">
    <link rel="stylesheet" href="css/estilos_registro.css">
    <!-- scripts -->
</head>
<body>
    <?php require "php/header.php"; ?>
    <section class="inicio">
        <?php if(isset($_GET['ver'])){ 
        $s = "SELECT * FROM Historial_C WHERE IDH=:idh";
        $q = $conn->prepare($s);
        $q->bindParam(':idh' , $_GET['ver']);
        $q->execute();
        $r = $q->fetch(PDO::FETCH_ASSOC);
        ?>
        <article class="factura">
            <h2>Factura</h2>
            <hr>
            <h3>CASA XOXÓCTIC</h3>
            <h4>CASA XOXÓCTIC Café, bakery y eco tienda.</h4>
            <div class="f_div">
                <p>Mesa: <?= $r['Mesa']; ?>.</p>
            </div>
            <div class="f_div">
                <p>Fecha: <?= $r['Fecha']; ?></p>
                <p>Hora: <?= $r['Hora']; ?></p>
            </div>
            <h5>**** PRE - CUENTA ****</h5>
            <hr>
            <p><span>Cant. Descripción</span><span>Total</span></p>
            <hr>
            <?php 
            $pp = $r['IDH'];
            $pre = "SELECT * FROM Historial_Orden_C WHERE IDH=$pp";
            $consul = $conn->prepare($pre);
            $consul->execute();
            $total = 0;
            while($six = $consul->fetch(PDO::FETCH_ASSOC)){
            ?>
            <p>
                <span><?= $six['Cantidad'] ?> <?= $six['Producto']; ?></span>
                <span>$<?= number_format($six['Precio'] , 2); ?></span><span>$<?php $fan = number_format(($six['Cantidad'] * $six['Precio']) , 2); echo $fan; ?></span>
            </p>
            <?php 
            $total = $total + $fan;
            } ?>
            <br>
            <hr>
            <p>
                <span>Sub Total: </span>
                <span>$<?php echo number_format($total , 2); ?></span>
            </p>
            <hr>
            <p>
                <strong><span>Total</span></strong>
                <strong><span>$<?php echo number_format($total , 2); ?></span></strong>
            </p>
            <hr>
            <span class="centrado">NO VALIDO COMO TIQUETE</span>
            <hr>
            <span class="centrado">- Cuenta Cerrada -</span>
            <span class="centrado">Transacción en dolares Americanos.</span>
        </article>
        <?php } else { ?>
        <article class="factura">
            <h2>Factura</h2>
            <hr>
            <h3>CASA XOXÓCTIC</h3>
            <h4>CASA XOXÓCTIC Café, bakery y eco tienda.</h4>
            <div class="f_div">
                <p>Mesa: 0.</p>
            </div>
            <div class="f_div">
                <p>Fecha: 00/00/00</p>
                <p>Hora: 0:00:00 PM</p>
            </div>
            <h5>**** PRE - CUENTA ****</h5>
            <hr>
            <p><span>Cant. Descripción</span><span>Total</span></p>
            <hr>
            <p>
                <span>1 Menú número 1</span>
                <span>$0.00</span>
            </p>
            <br>
            <hr>
            <p>
                <span>Sub Total: </span>
                <span>$0.00</span>
            </p>
            <p>
                <span>Propina: </span>
                <span>$0.00</span>
            </p>
            <hr>
            <p>
                <strong><span>Total</span></strong>
                <strong><span>$0.00</span></strong>
            </p>
            <hr>
            <span class="centrado">NO VALIDO COMO TIQUETE</span>
            <hr>
            <span class="centrado">- Cuenta Cerrada -</span>
            <span class="centrado">Transacción en dolares Americanos.</span>
        </article>
        <?php } ?>
        <article class="block_activo">
            <form action="registro.php" method="post" id="formulario">
                <input type="search" placeholder="cliente" name="search" id="search">
                <div>
                    <label for="mesa">Mesa: </label>
                    <input type="number" name="mesa" id="mesa">
                    <!-- <label for="fecha">Fecha: </label>
                    <input type="date" name="fecha" id="fecha"> -->
                    <input type="submit" value="buscar" id="buscar">
                </div>
            </form>
            <table id="tabla">
                <tr id="thead">
                    <td class="p">#</td>
                    <td>Cliente</td>
                    <td class="p">Mesa</td>
                    <td>Hora</td>
                    <td>Fecha</td>
                    <td class="d">Detalles</td>
                    <td class="d">Imprimir</td>
                </tr>
                <?php
                if(!empty($_POST['search']) && !empty($_POST['mesa']) && !empty($_POST['fecha'])){
                    $search = $_POST['search'];
                    $sql = "SELECT * FROM Historial_C WHERE Cliente LIKE '%$search%' AND Mesa=:mesa AND Fecha=:fech";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':mesa' , $_POST['mesa']);
                    $query->bindParam(':fech' , $_POST['fecha']);
                } elseif(!empty($_POST['search']) && !empty($_POST['mesa'])){
                    $search = $_POST['search'];
                    $sql = "SELECT * FROM Historial_C WHERE Cliente LIKE '%$search%' AND Mesa=:mesa";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':mesa' , $_POST['mesa']);
                } elseif(!empty($_POST['search']) && !empty($_POST['fecha'])){
                    $search = $_POST['search'];
                    $sql = "SELECT * FROM Historial_C WHERE Cliente LIKE '%$search%' AND Fecha=:fech";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':mesa' , $_POST['fecha']);
                } elseif(!empty($_POST['mesa']) && !empty($_POST['fecha'])){
                    $sql = "SELECT * FROM Historial_C WHERE Mesa=:mesa AND Fecha=:fech";
                    $query->bindParam(':mesa' , $_POST['mesa']);
                    $query->bindParam(':fech' , $_POST['fecha']);
                    $query = $conn->prepare($sql);
                } elseif(!empty($_POST['search'])){
                    $search = $_POST['search'];
                    $sql = "SELECT * FROM Historial_C WHERE Cliente LIKE '%$search%'";
                    $query = $conn->prepare($sql);
                } elseif(!empty($_POST['mesa'])){
                    $sql = "SELECT * FROM Historial_C WHERE Mesa=:mesa";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':mesa' , $_POST['mesa']);
                } elseif(!empty($_POST['fecha'])){
                    $sql = "SELECT * FROM Historial_C WHERE Fecha=:fech";
                    $query = $conn->prepare($sql);
                    $query->bindParam(':mesa' , $_POST['fecha']);
                } else {
                    $sql = "SELECT * FROM Historial_C";
                    $query = $conn->prepare($sql);
                }                    
                $query->execute();
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr class="tbody">
                    <td class="p"><?= $row['IDH']; ?></td>
                    <td><?= $row['Cliente']; ?></td>
                    <td class="p"><?= $row['Mesa']; ?></td>
                    <td><?= $row['Hora']; ?></td>
                    <td><?= $row['Fecha']; ?></td>
                    <td class="d"><a href="?ver=<?= $row['IDH']; ?>">Detalles</a></td>
                    <td class="d"><a href="print/sub_ticket.php?final=<?= $row['IDH']; ?>">Imprimir</a></td>
                </tr>
                <?php } ?>
            </table>
        </article>
    </section>
</body>
</html>