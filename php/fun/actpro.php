<?php
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
if(isset($_POST['producto']) && isset($_POST['precio']) && isset($_GET['menu'])){
    require "conexion.php";
    $sql = "UPDATE Menu_C SET Producto=:pro, Precio=:pre WHERE IDMEC=:idme";
    $query = $conn->prepare($sql);
    $query->bindParam(':pro' , $_POST['producto']);
    $query->bindParam(':pre' , $_POST['precio']);
    $query->bindParam(':idme' , $_GET['menu']);
    $query->execute();
    header("location: ../../menu.php?case=true");
} else {
    header("location: ../../menu.php?case=false");
}
?>