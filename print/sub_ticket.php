<?php

if(!isset($_GET['final'])){
    header("location: ../inicio.php?error");
}

require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/*
	Este ejemplo imprime un
	ticket de venta desde una impresora térmica
*/


/*
    Aquí, en lugar de "POS" (que es el nombre de mi impresora)
	escribe el nombre de la tuya. Recuerda que debes compartirla
	desde el panel de control
*/

$nombre_impresora = "POS-80C"; 


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
#Mando un numero de respuesta para saber que se conecto correctamente.
//echo 1;
/*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionará en todas las
	impresoras

	Pequeña nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resolución baja. En mi caso
	la imagen que uso es de 250 x 250
*/

# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*
try{
	$logo = EscposImage::load("geek.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*}

/*
	Ahora vamos a imprimir un encabezado
*/

require "../php/fun/conexion.php";
require "../php/fun/funciones.php";
require "../php/fun/tiempo.php";
/*--- INFO GENERAL ---*/
$sql = "SELECT * FROM Historial_C WHERE IDH=:idh";
$query = $conn->prepare($sql);
$query->bindParam(':idh' , $_GET['final']);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
/*--- MENU GENERAL ---*/
$leng = "SELECT * FROM Historial_Orden_C WHERE IDH=:idh";
$consulta = $conn->prepare($leng);
$consulta->bindParam(':idh' , $_GET['final']);
$consulta->execute();
$total = 0;

$printer->text("\n"."CASA XOXÓCTIC" . "\n");
$printer->text("CASA XOXÓCTIC Café, bakery y eco tienda." . "\n");
$printer->text("Mesa: ".$row['Mesa']."\n");
#La fecha también
date_default_timezone_set("America/El_Salvador");
$printer->text("Fecha: " . $fdate . "  Hora: ".$clock."\n");
$printer->text("**** PRE - CUENTA ****" . "\n");
$printer->text("----------------------------------" . "\n");
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Cant.  Descripción    P.U   IMP.\n");
$printer->text("----------------------------------"."\n");
/*
	Ahora vamos a imprimir los
	productos
*/
/*Alinear a la izquierda para la cantidad y el nombre*/
$printer->setJustification(Printer::JUSTIFY_LEFT);
while($colum = $consulta->fetch(PDO::FETCH_ASSOC)){
    $printer->text( $colum['Producto'] . " \n");
    $printer->text( $colum['Cantidad'] . " Unidad   $". number_format($colum['Precio'] , 2) ." $". number_format(($colum['Precio'] * $colum['Cantidad']) , 2) ."   \n");
    
    $total = $total + ($colum['Precio'] * $colum['Cantidad']);
}
/*
	Terminamos de imprimir
	los productos, ahora va el total
*/
$printer->text("----------------------------------"."\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("SUBTOTAL: $".number_format($total , 2)."\n");
if($row['Propina'] == 1){
$propina = ($total * 0.1);
$printer->text("PROPINA (10%): $".number_format($propina , 2)."\n");
} else {
$propina = 0;
$printer->text("PROPINA (10%): $0.00 \n");
}                                           
$printer->text("TOTAL: $".number_format(($total + $propina) , 2)."\n");

/*
	Podemos poner también un pie de página
*/
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("----------------------------------"."\n");
$printer->text("\n");
$printer->text("Gracias por su compra\n");
$printer->text("Casa Xoxóctic lo espera pronto\n");



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
header("location: ../registro.php");

?>