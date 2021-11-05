<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
require "tiempo.php";
if(isset($_POST) && isset($_COOKIE)){
    if(!empty($_POST['name']) && !empty($_POST['mesa']) && !empty($_POST['cliente'])){
        require "conexion.php";
        if(!empty($_POST['llevar'])){
            $llevar = 1;
        } else {
            $llevar = 0;
        }
        if(!empty($_POST['freq'])){
            $frecuencia = $_POST['freq'];
            $zql = "SELECT Nombre FROM Cliente_Frecuente WHERE IDCF=:id";
            $zuery = $conn->prepare($zql);
            $zuery->bindParam(':id' , $frecuencia);
            $zuery->execute();
            $zow = $zuery->fetch(PDO::FETCH_ASSOC);
            $name = $zow['Nombre'];
        } else {
            $frecuencia = null;
            $name = $_POST['name'];
        }
        if(!empty($_POST['nota'])){
            $notas = $_POST['nota'];
        } else {
            $notas = null;
        }
        $mesero = $_SESSION['mesero'];
        $sql = "INSERT INTO Activo_C (Mesa , Cliente , Personas , IDCF , Mesero , Hora , Llevar) VALUES (:mesa , :cli , :per , :idcf , :idm , :hora , :lle)";
        $query = $conn->prepare($sql);
        $query->bindParam(':mesa' , $_POST['mesa']);
        $query->bindParam(':cli' , $name);
        $query->bindParam(':per' , $_POST['cliente']);
        $query->bindParam(':idcf' , $frecuencia);
        $query->bindParam(':idm' , $mesero);
        $query->bindParam(':hora' , $clock);
        $query->bindParam(':lle' , $llevar);
        if($query->execute()){
            $jum = "SELECT IDAC FROM Activo_C ORDER BY IDAC DESC";
            $suit = $conn->prepare($jum);
            $suit->execute();
            $h = $suit->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "No se ejecuto";
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
                $q->bindParam(':ida' , $h['IDAC']);
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
            $xql = "INSERT INTO Compartido (IDAC) VALUES (:idac)";
            $xuery = $conn->prepare($xql);
            $xuery->bindParam(':idac' , $h['IDAC']);
            if($xuery->execute()){
                header("location: ../../print/orden.php");
            } else {
                header("location: ../../inicio.php?error=2");
            }
        } else {
            header("location: ../../inicio.php?error=1");
        }
        $fi = $h['IDAC'];
        header("location: ../../print/orden.php?final=$fi");
        //header("location: ../../inicio.php");
    } else {}
} else {}
?>