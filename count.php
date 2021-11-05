<?php 
date_default_timezone_set('America/El_Salvador'); 
require "php/fun/conexion.php";
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
    <link rel="stylesheet" href="css/estilos_count.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link rel="stylesheet" href="css/boilerplate.css">
    <!-- scripts -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script type="text/javascript" src="js/respond.min.js"></script>
    <script type="text/javascript">
    var total = 0;
    var cook = 0;
    </script>
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
    <script type="text/javascript" src="js/panel.js"></script>
</head>
<body>
    <?php require "php/header.php"; ?>
    <section id="count">
        <article class="vida">
            <h1>Registro diario</h1>
            <table>
                <tr class="thead">
                    <td>Producto</td>
                    <td>Unidades</td>
                    <td>Total</td>
                </tr>
            <?php
            $total_global = 0;
            $sql = "SELECT * FROM Menu_C";
            $query = $conn->prepare($sql);
            $query->execute();
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $tql = "SELECT Cantidad, Precio FROM Historial_Orden_Diario_C O LEFT JOIN Historial_Diario_C H ON O.IDHDC = H.IDHDC WHERE Producto=:pro";
                $tuery = $conn->prepare($tql);
                $tuery->bindParam(':pro' , $row['Producto']);
                $tuery->execute();
                $total = 0;
                $potal = 0;
                while($sow = $tuery->fetch(PDO::FETCH_ASSOC)){
                    $potal = $potal + $sow['Cantidad'];
                } 
                if($potal != 0){
                    ?>
                <tr>
                    <td><?= $row['Producto']; ?></td>   
                    <td><?= $potal; ?></td>   
                    <td>$<?= $total = number_format(($row['Precio'] * $potal) , 2); ?></td>
                </tr>
            <?php
                    $total_global = $total_global + $total;
                } 
            } ?>
                <tr></tr>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td>$<?= number_format($total_global , 2); ?></td>
                </tr>
            </table>
            <a href="print/finalizar_dia.php"><button id="fin">Finalizar día</button></a>
        </article>
        <article class="vida">
            <?php
            $mon = '';
            $star = 01;
            $end = null;
            $potal = 0;
            $total_global_mensual = 0;
            if(isset($_POST['mes'])){
                $numes = $_POST['mes'];
                switch($numes){
                    case 1: $end = 31; $mon = "Enero"; break;
                    case 2: $end = 28; $mon = "Febrero"; break;
                    case 3: $end = 31; $mon = "Marzo"; break;
                    case 4: $end = 30; $mon = "Abril"; break;
                    case 5: $end = 31; $mon = "Mayo"; break;
                    case 6: $end = 30; $mon = "Junio"; break;
                    case 7: $end = 31; $mon = "Julio"; break;
                    case 8: $end = 31; $mon = "Agosto"; break;
                    case 9: $end = 30; $mon = "Septiembre"; break;
                    case 10: $end = 31; $mon = "Octubre"; break;
                    case 11: $end = 30; $mon = "Noviembre"; break;
                    case 12: $end = 31; $mon = "Diciembre"; break;
                }
            }
            ?>
            <h1>Registro Mensual <?= $mon; ?></h1>
            <form action="" method="post">
                <select name="mes" id="mes">
                    <option value="" selected disabled>-- SELECCIONE --</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
                <input type="submit" value="Aceptar" id="fin">
            </form>
            <table>
                <tr class="thead">
                    <td>Producto</td>
                    <td>Unidades</td>
                    <td>Total</td>
                </tr>
                <?php
                if(isset($_POST['mes'])){
                    $xql = "SELECT * FROM Menu_C";
                    $xuery = $conn->prepare($xql);
                    $xuery->execute();
                    $total_mensual = 0;
                    while($xow = $xuery->fetch(PDO::FETCH_ASSOC)){
                        $fan = "SELECT Cantidad, Precio FROM Historial_Orden_C O LEFT JOIN Historial_C H ON O.IDH = H.IDH WHERE Producto=:pro AND Fecha BETWEEN '2021-$numes-01' AND '2021-$numes-$end'";
                        $fen = $conn->prepare($fan);
                        $fen->bindParam(':pro' , $xow['Producto']);
                        $fen->execute();
                        $potal = 0;
                        $total = 0;
                        while($fun = $fen->fetch(PDO::FETCH_ASSOC)){
                            $potal = $potal + $fun['Cantidad'];
                        }
                        if($potal != 0){
                            $total = $potal * $xow['Precio'];
                    ?>
                    <tr>
                        <td><?= $xow['Producto']; ?></td>
                        <td><?= $potal; ?></td>
                        <td>$<?= number_format($total , 2); ?></td>
                    </tr>
                <?php 
                        $total_global_mensual = $total_global_mensual + $total;
                        } } } ?>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td>$<?= number_format($total_global_mensual , 2); ?></td>
                </tr>
            </table>
            <a href="print/fecha_final.php?mes=<?= $numes; ?>&dia=<?= $end; ?>&"><button id="fin">Finalizar Mes</button></a>
        </article>
    </section>
</body>
</html>