<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
if(!isset($_GET['fun'])){
    header("location: menu.php");
}
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
    <link rel="stylesheet" href="css/estilos_menu.css">
    <!-- scripts -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
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
    <section>
        <?php if($_GET['fun'] == 1){
        $pro = $_GET['pro'];
        $sql = "SELECT Producto, Precio FROM Menu_C WHERE IDMEC=:idme";
        $query = $conn->prepare($sql);
        $query->bindParam(':idme' , $pro);
        $query->execute();
        $woz = $query->fetch(PDO::FETCH_ASSOC);
        ?>
        <!-- -->
        <h1 class="titu_edit">Editar Menú</h1>
        <article class="medium">
            <form action="php/fun/actpro.php?menu=<?= $pro; ?>" method="post">
                <label for="">Descripción: </label>
                <input type="text" value="<?= $woz['Producto']; ?>" name="producto"><br>
                <label for="">Precio: $</label>
                <input type="text" value="<?= number_format($woz['Precio'] , 2); ?>" id="precio" name="precio"><br>
                <a href="menu.php" class="regre">Regresar</a>
                <input type="submit" value="Actualizar">
            </form>
        </article>
        <?php } elseif($_GET['fun'] == 2){ ?>
        <!-- -->
        <h1 class="titu_edit">Aplicar Descuento</h1>
        <article class="medium">
            <form action="php/fun/act_descuento.php?menu=<?= $_GET['pro']; ?>" method="post">
                <?php 
                $s = "SELECT Producto FROM Menu_C WHERE IDMEC=:id";
                $q = $conn->prepare($s);
                $q->bindParam(':id' , $_GET['pro']);
                $q->execute();
                $f = $q->fetch(PDO::FETCH_ASSOC);
                
                $xql = "SELECT * FROM Descuento_C WHERE IDMEC=:id";
                $xuery = $conn->prepare($xql);
                $xuery->bindParam(':id' , $_GET['pro']);
                $xuery->execute();
                
                ?>
                <h3><?= $f['Producto']; ?></h3>
                <label for="">Descuento por porcentaje: </label>
                <input type="text" placeholder="%100" id="precio" name="percent"
                <?php if($xow = $xuery->fetch(PDO::FETCH_ASSOC)){
                    echo "value='".$xow['Descuento']."'";
                } ?> ><br>
                <label>Fecha Inicial: </label>
                <input type="date" value="<?= date('d-m-Y'); ?>" required name="inicio"><br>
                <label>Fecha Fin: </label>
                <input type="date" value="<?= date('d-m-Y'); ?>" required name="fin"><br><br>
                <a href="menu.php" class="regre">Regresar</a>
                <?php 
                if(isset($xow['Descuento'])){ ?>
                <a href="php/fun/dlt_descuento.php?menu=<?= $_GET['pro']; ?>" class="regre">Eliminar Descuento</a>
                <?php } else { ?>
                <input type="submit" value="Actualizar">
                <?php } ?>
            </form>
        </article>
        <!-- -->
        <?php } elseif($_GET['fun'] == 3){ ?>
        <h1 class="titu_edit">Agregar Producto</h1>
        <article class="medium">
            <form action="php/fun/ingresar_menu.php" method="post">
                <label for="">Producto: </label>
                <input type="text" placeholder="" id="caja_uni" required name="pro_ing"><br>
                <label for="">Monto: $</label>
                <input type="text" placeholder="0.00" id="precio" required name="pre_ing"><br>
                <input type="text" value="<?= $_GET['tipo']; ?>" style="display: none;" name="tipo" required><br>
                <input type="submit" value="Ingresar" class="ingerir">
            </form>
        </article>
        <?php } ?>
    </section>
</body>
</html>