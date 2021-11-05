<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    header("location: ../../inicio.php?error=edit_ingresar");
}
echo "f";
date_default_timezone_set('America/El_Salvador');
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
        require "conexion.php";
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
                echo "s";
                } else {
                    $noland = 1;
                }
            }
        }
        echo "Hola?";
        if($noland == 0){
            echo "si";
            header("location: ../../inicio.php");
        } else {
            echo "no";
            header("location: ../../inicio.php?error=1");
        }
        echo "ninguna";
        //header("location: ../../print/orden.php");
        header("location: ../../inicio.php");
    } else {}
} else {}
?>