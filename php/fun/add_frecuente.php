<?php 
date_default_timezone_set('America/El_Salvador'); 
if(isset($_POST['name']) && isset($_POST['mail'])){
    require "conexion.php";
    if(isset($_POST['phon'])){
        $phon = $_POST['phon'];
    } else {
        $phon = null;
    }
    if(isset($_POST['addr'])){
        $addr = $_POST['addr'];
    } else {
        $addr = null;
    }
    if(isset($_POST['city'])){
        $city = $_POST['city'];
    } else {
        $city = null;
    }
    $cero = 0;
    $uno = 1;
    $hoy = date('Y-m-d');
    $sql = "INSERT INTO Cliente_Frecuente (Nombre , Email , Telefono , Direccion , Ciudad , Balance_Puntos , Primera , Ultima , TotalVisita , Total) VALUES (:name , :mail , :phon , :addr , :city , :punto , :first , :last , :vist , :total)";
    $query = $conn->prepare($sql);
    $query->bindParam(':name' , $_POST['name']);
    $query->bindParam(':mail' , $_POST['mail']);
    $query->bindParam(':phon' , $phon);
    $query->bindParam(':addr' , $addr);
    $query->bindParam(':city' , $city);
    $query->bindParam(':punto' , $cero);
    $query->bindParam(':first' , $hoy);
    $query->bindParam(':last' , $hoy);
    $query->bindParam(':vist' , $uno);
    $query->bindParam(':total' , $cero);
    if($query->execute()){
        header("location: ../../frecuente.php");
    } else {
        header("location: ../../frecuente.php?error");
    }
} else {
    header("location: ../../frecuente.php");
}
?>