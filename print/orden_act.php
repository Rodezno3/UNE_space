<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
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
}catch(Exception $e){}

require "../php/fun/conexion.php";
/*--- INFO GENERAL ---*/
$sql = "SELECT * FROM Activo_C WHERE IDAC=:ida";
$query = $conn->prepare($sql);
$query->bindParam(':ida' , $_GET['final']);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
/*--- MENU GENERAL ---*/
$leng = "SELECT * FROM Descripcion_C WHERE IDAC=:ida";
$consulta = $conn->prepare($leng);
$consulta->bindParam(':ida' , $_GET['final']);
$consulta->execute();

$printer->text("\n"."ORDEN #". $_GET['final'] . "\n");
$printer->text("Mesa: ".$row['Mesa']."\n");
$printer->text("Fecha: ".$fdate."   Hora:".$clock."\n");
$printer->text("**** COCINA ****" . "\n");
$printer->text("-----------------------------" . "\n");
/*
	Ahora vamos a imprimir los
	productos
*/
/*Alinear a la izquierda para la cantidad y el nombre*/
$printer->setJustification(Printer::JUSTIFY_LEFT);
$not = 0;
$net = '';
for($i=0 ; $i<=24 ; $i++){
    $a = "a".$i;
    $b = "b".$i;
    if(isset($_COOKIE[$a])){
        $m = $_COOKIE[$a];
        $n = $_COOKIE[$b];
        $fql = "SELECT Producto FROM Menu_C WHERE IDMEC=:id";
        $fuery = $conn->prepare($fql);
        $fuery->bindParam(':id' , $m);
        $fuery->execute();
        $fow = $fuery->fetch(PDO::FETCH_ASSOC);
        $printer->text("".$n." ".$fow['Producto']." \n");
    }
}
 
/*
	Terminamos de imprimir
	los productos, ahora va el total
*/


/*
	Podemos poner también un pie de página
*/

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

if(isset($_GET['final'])){
    $id = $_GET['final'];
} else {
    header("location: ../inicio.php?error=edit_ingresar");
}
if(isset($_GET) && isset($_COOKIE)){
    if(!empty($_POST['name'])){
        if(!empty($_POST['llevar'])){
            $llevar = 1;
        } else {
            $llevar = 0;
        }
        if(!empty($_POST['nota'])){
            $notas = $_POST['nota'];
        } else {
            $notas = null;
        }
        $noland = 0;
        for($i=0 ; $i<=24 ; $i++){
            $a = "a".$i;
            $b = "b".$i;
            if(isset($_COOKIE[$a])){
                $m = $_COOKIE[$a];
                $n = $_COOKIE[$b];
                $s = "INSERT INTO Descripcion_C (IDAC ,IDMEC , Cantidad, Nota) VALUES (:ida , :idme , :can , :not)";
                $q = $conn->prepare($s);
                $q->bindParam(':ida' , $id);
                $q->bindParam(':idme' , $m);
                $q->bindParam(':can' , $n);
                $q->bindParam(':not' , $notas);
                if($q->execute()){
                setcookie($a , false , $expire = time() -100 , "/");
                setcookie($b , false , $expire = time() -100 , "/");
                
                } else {
                    $noland = 1;
                }
            }
        }
        if($noland == 0){
            header("location: ../inicio.php");
        } else {
            header("location: ../inicio.php?error=1");
        }
        //header("location: ../../print/orden.php");
        header("location: ../inicio.php");
    } else {}
} else {}

?>