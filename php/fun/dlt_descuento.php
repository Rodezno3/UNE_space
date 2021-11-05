<?php 
if(isset($_GET['menu'])){
    require "conexion.php";
    $sql = "DELETE FROM Descuento_C WHERE IDMEC=:id";
    $query = $conn->prepare($sql);
    $query->bindParam(':id' , $_GET['menu']);
    if($query->execute()){
        header("location: ../../menu.php");
    } else {
        header("location: ../../menu.php?error");
    }
} else {
    header("location: ../../menu.php");
}
?>