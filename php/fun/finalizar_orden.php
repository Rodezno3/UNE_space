<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
$key = 0;
require "tiempo.php";
if(isset($_GET['id'])){
    require "conexion.php";
    $sql = "SELECT * FROM Activo_C WHERE IDAC=:ida";
    $query = $conn->prepare($sql);
    $query->bindParam(':ida' , $_GET['id']);
    if($query->execute()){
        //echo "Si selecciono Activo<br>";
    }
    $kim = $query->fetch(PDO::FETCH_ASSOC);
    /*- CAMBIAR -*/
    $m = "INSERT INTO Historial_Diario_C (Mesa , Cliente , Mesero , Persona , IDCF , Hora , Fecha , Llevar) VALUES (:mesa , :clie , :mese , :pers , :idcf , :hora , :fech , :llev)";
    $n = $conn->prepare($m);
    $n->bindParam(':mesa' , $kim['Mesa']);
    $n->bindParam(':clie' , $kim['Cliente']);
    $n->bindParam(':mese' , $kim['Mesero']);
    $n->bindParam(':pers' , $kim['Personas']);
    $n->bindParam(':idcf' , $kim['IDCF']);
    $n->bindParam(':hora' , $kim['Hora']);
    $n->bindParam(':fech' , $fdate);
    $n->bindParam(':llev' , $kim['Llevar']);
    if($n->execute()){
        //echo "Si ingreso Historial<br>";
    }
    /*-- menu --*/
    $me = "SELECT * FROM Descripcion_C WHERE IDAC=:ida";
    $nu = $conn->prepare($me);
    $nu->bindParam(':ida' , $_GET['id']);
    if($nu->execute()){
        //echo "Si selecciono Description<br>";
    }
    while($re = $nu->fetch(PDO::FETCH_ASSOC)){
        $an = "SELECT * FROM Menu_C WHERE IDMEC=:idme";
        $tes = $conn->prepare($an);
        $tes->bindParam(':idme' , $re['IDMEC']);
        if($tes->execute()){
           //echo "Si selecciono un dato<br>";
        }
        $de = $tes->fetch(PDO::FETCH_ASSOC);
        
        $sub = "SELECT IDHDC FROM Historial_Diario_C ORDER BY IDHDC DESC";
        $cacha = $conn->prepare($sub);
        $cacha->execute();
        $misio = $cacha->fetch(PDO::FETCH_ASSOC);
        
        $s = "INSERT INTO Historial_Orden_Diario_C (IDHDC , Producto , Cantidad , Precio) VALUES (:idh , :pro , :can , :pre)";
        $q = $conn->prepare($s);
        $q->bindParam(':idh' , $misio['IDHDC']);
        $q->bindParam(':pro' , $de['Producto']);
        $q->bindParam(':can' , $re['Cantidad']);
        $q->bindParam(':pre' , $de['Precio']);
        if($q->execute()){
            //echo "Si ingreso un dato a Orden<br>";
        } else {
            $key = 1;
            //echo "No ingreso un dato a Orden:" . $de['Producto'] ." <br>";
        }
    }
    /*-- DELETE --*/
    if($key == 0){
        $el = "DELETE FROM Activo_C WHERE IDAC=:ida";
        $im = $conn->prepare($el);
        $im->bindParam(':ida' , $_GET['id']);
        $im->execute();

        $in = "DELETE FROM Descripcion_C WHERE IDAC=:ida";
        $ar = $conn->prepare($in);
        $ar->bindParam(':ida' , $_GET['id']);
        $ar->execute();
        
        $ru = "DELETE FROM Compartido WHERE IDAC=:ida";
        $by = $conn->prepare($ru);
        $by->bindParam(':ida' , $_GET['id']);
        $by->execute();
        
        header("location: ../../inicio.php");
    } else {
        header("location: ../../inicio.php?error");
    }
} else {}
?>