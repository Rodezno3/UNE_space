<?php

if(isset($_GET['id']) && isset($_GET['orden'])){
    $id = $_GET['id'];
    $or = $_GET['orden'];
} else {
    header("location: ../inicio.php?error=factura");
}

require __DIR__ . '/ticket/autoload.php'; 

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$nombre_impresora = "POS-80C"; //Nombre de Impresora 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);

$printer->setJustification(Printer::JUSTIFY_CENTER);

try{
	$logo = EscposImage::load("logos.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){}

require "../php/fun/tiempo.php";
require "../php/fun/conexion.php";
require "../php/fun/funciones.php";
/*--- INFO GENERAL ---*/
$sql = "SELECT * FROM Activo_C WHERE IDAC=:ida";
$query = $conn->prepare($sql);
$query->bindParam(':ida' , $_GET['id']);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
/*--- MENU GENERAL ---*/
$leng = "SELECT * FROM Descripcion_C WHERE IDAC=:ida";
$consulta = $conn->prepare($leng);
$consulta->bindParam(':ida' , $_GET['id']);
$consulta->execute();
$total = 0;

$printer->text("\n"."CASA XOXÓCTIC" . "\n");
$printer->text("CASA XOXÓCTIC Café, bakery y eco tienda." . "\n");
$printer->text("Av. las Gardenias, polig 2,\n");
$printer->text("col Las Mercedes #10 \n");
$printer->text("San Salvador, San Salvador." . "\n");
$printer->text("N.I.T.: 0614-290997-136-6" . "\n");
$printer->text("Mesa: ".$row['Mesa']."   Mesero: ".$row['Mesero']."\n");
$printer->text("Fecha: ".$fdate."   Hora: ".$clock."\n");
$printer->text("**** CUENTA ****" . "\n");
$printer->text("----------------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Cant.  Descripción    P.U   IMP.\n");
$printer->text("----------------------------------"."\n");

$printer->setJustification(Printer::JUSTIFY_LEFT);

while($colum = $consulta->fetch(PDO::FETCH_ASSOC)){
    $s = "SELECT Producto , Precio FROM Menu_C WHERE IDMEC=:idme";
    $q = $conn->prepare($s);
    $q->bindParam(':idme' , $colum['IDMEC']);
    $q->execute();
    $r = $q->fetch(PDO::FETCH_ASSOC);
    
    $printer->text( $r['Producto'] . " \n");
    $printer->text( $colum['Cantidad'] . " Unidad   $". number_format($r['Precio'] , 2) ." $".number_format(($r['Precio'] * $colum['Cantidad']) , 2)."   \n");
    
    $total = $total + ($r['Precio'] * $colum['Cantidad']);
}
/*-----*/
if(!empty($row['IDCF'])){
    $zql = "SELECT TotalVisita , Total FROM Cliente_Frecuente WHERE IDCF=:id";
    $zuery = $conn->prepare($zql);
    $zuery->bindParam(':id' , $row['IDCF']);
    $zuery->execute();
    $zow = $zuery->fetch(PDO::FETCH_ASSOC);
    $vis = $zow['TotalVisita'] + 1;
    $compra = $zow['Total'] + $total;
    
    $xql = "UPDATE Cliente_Frecuente SET TotalVisita=:vis AND Ultima=:last AND Total=:total";
    $xuery = $conn->prepare($xql);
    $xuery->bindParam(':vis' , $vis);
    $xuery->bindParam(':last' , $fdate);
    $xuery->bindParam(':total' , $compra);
    $xuery->execute();
    
    $porcent = (($total * 5)/100);
    $printer->text("Cliente Frecuente Descuento 5% \n");
    $printer->text("- $".number_format($porcent , 2)."\n");
    $total = $total - $porcent;
    
}
/*----*/
$printer->text("----------------------------------"."\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("TOTAL: $".number_format($total , 2)."\n");

$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("----------------------------------"."\n");
$printer->text("\n");
$printer->text("Gracias por su compra\n");
$printer->text("Casa Xoxóctic le espera pronto\n");
$printer->text("Tel: +503 7697-0647\n");
$printer->text("https://www.casaxoxoctic.com/\n");


$printer->feed(3);

$printer->cut();

$printer->pulse();

$printer->close();

$f = $_GET['id'];
header("location: ../php/fun/finalizar_orden.php?id=$f");

?>