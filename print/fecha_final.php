<?php

require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$nombre_impresora = "POS-80C"; 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);

$printer->setJustification(Printer::JUSTIFY_CENTER);

try{
	$logo = EscposImage::load("logos.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*/}

require "../php/fun/conexion.php";
require "../php/fun/funciones.php";
/*--- INFO GENERAL ---*/
$sql = "SELECT * FROM Menu_C";
$query = $conn->prepare($sql);
$query->execute();
/*--- MENU GENERAL ---*/
$global_total = 0;

if(isset($_GET['mes'])){
    $numes = $_GET['mes'];
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

$printer->text("\n"."CASA XOXÓCTIC" . "\n");
$printer->text("CASA XOXÓCTIC Café, bakery y eco tienda." . "\n");

$printer->text("Fecha: ".$fdate."   Hora: ".$clock."\n");
$printer->text("**** CORTE DE MES DE ".$mon." ****" . "\n");
$printer->text("----------------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Cant.  Descripción    P.U   IMP.\n");
$printer->text("----------------------------------"."\n");
$year = date('Y');
$printer->setJustification(Printer::JUSTIFY_LEFT);
while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $leng = "SELECT Cantidad, Precio FROM Historial_Orden_C O LEFT JOIN Historial_C H ON O.IDH = H.IDH WHERE Producto=:pro AND Fecha BETWEEN '$year-$numes-01' AND '$year-$numes-$end'";
    $consulta = $conn->prepare($leng);
    $consulta->bindParam(':pro' , $row['Producto']);
    $consulta->execute();
    $potal = 0;
    $total = 0;
    while($xow = $consulta->fetch(PDO::FETCH_ASSOC)){
        $potal = $potal + $xow['Cantidad'];
    }
    if($potal != 0){
    $printer->text( $row['Producto'] . " \n");
    $total = $row['Precio'] * $potal;
    $printer->text( $potal . " Unidad   $". number_format($row['Precio'] , 2) ." - $". number_format($total , 2) ."   \n");
    }
    
    $global_total = $global_total + $total;
}

$printer->text("----------------------------------"."\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("TOTAL VENTAS DE MES: $".number_format(($global_total) , 2)."\n");



$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("----------------------------------"."\n");



/*Alimentamos el papel 3 veces*/
$printer->feed(3);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/
$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();
header("location: ../count.php");

?>