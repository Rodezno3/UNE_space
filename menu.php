<?php 
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
date_default_timezone_set('America/El_Salvador'); 
require "php/fun/conexion.php";
require "php/fun/funciones.php";
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Dosis:wght@500&display=swap" rel="stylesheet">
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
    <section class="menu">
        <article class="contenedor_cubo">
            <div class="cubo" id="tipo1">
                <img src="img/iconos/004-taza-de-caf-1.png" alt="">
                <p>Espresso</p>
            </div>
            <div class="cubo" id="tipo2">
                <img src="img/iconos/005-taza-de-caf-2.png" alt="">
                <p>Iced Coffee</p>
            </div>
            <div class="cubo" id="tipo3">
                <img src="img/iconos/006-t-helado.png" alt="">
                <p>Cold Drinks</p>
            </div>
            <div class="cubo" id="tipo4">
                <img src="img/iconos/002-t-verde.png" alt="">
                <p>Hot Drinks</p>
            </div>
            <div class="cubo" id="tipo5">
                <img src="img/iconos/001-maquina-de-cafe.png" alt="">
                <p>Specialty Coffe</p>
            </div>
            <div class="cubo" id="tipo6">
                <img src="img/iconos/008-panadera.png" alt="">
                <p>Bread order</p>
            </div>
            <div class="cubo" id="tipo7">
                <img src="img/iconos/007-tarta-de-queso.png" alt="">
                <p>House Sweets</p>
            </div>
            <div class="cubo" id="tipo8">
                <img src="img/iconos/009-galleta-salada.png" alt="">
                <p>Snacks</p>
            </div>
            <div class="cubo" id="tipo9">
                <img src="img/iconos/pizza-slice.png" alt="">
                <p>Pizza</p>
            </div>
            <div class="cubo" id="tipo10">
                <img src="img/iconos/evento.png" alt="">
                <p>Eventos</p>
            </div>
            <div class="cubo" id="tipo11">
                <img src="img/iconos/cerveza.png" alt="">
                <p>Bebidas Alcoholicas</p>
            </div>
            <div class="cubo" id="tipo12">
                <img src="img/iconos/sandwich.png" alt="">
                <p>Sándwich</p>
            </div>
            <div class="cubo" id="tipo13">
                <img src="img/iconos/mantequilla-de-mani.png" alt="">
                <p>Productos Procesados</p>
            </div>
        </article>
        <?php 
        for($i=1 ; $i<=13 ; $i++){
        ?>
        <article class="menu_detalle" id="menu<?= $i; ?>">
            <ul>
            <?php
            $sql = "SELECT * FROM Menu_C WHERE Grupo = :i";
            $query = $conn->prepare($sql);
            $query->bindParam(':i' , $i);
            $query->execute();
            while($a = $query->fetch(PDO::FETCH_ASSOC)){
            ?>
                <li>
                    <div class="md_info">
                        <p><?= $a['Producto']; ?></p>
                        <p>$<?= number_format(Precio($a['IDMEC']) , 2); ?></p>
                    </div>
                    <div class="md_boton">
                        <a href="menu_edit.php?fun=1&pro=<?= $a['IDMEC']; ?>"><button><span></span> Editar</button></a>
                        <a href="menu_edit.php?fun=2&pro=<?= $a['IDMEC']; ?>"><button><span></span> Descuento</button></a>
                        <a href="php/fun/eliminar_menu.php?menu=<?= $a['IDMEC']; ?>"><button><span></span> Eliminar</button></a>
                    </div>
                </li>
            <?php } ?>
                <li>
                    <a href="menu_edit.php?fun=3&tipo=<?= $i; ?>"><button>Agregar Artículo</button></a>
                </li>
            </ul>
        </article>
        <?php } ?>
    </section>
</body>
</html>