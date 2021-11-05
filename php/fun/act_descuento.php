<?php 
if(isset($_GET['menu']) && isset($_POST['percent'])){
    require "conexion.php";
    $sql = "INSERT INTO Descuento_C (IDMEC , Descuento , Inicio , Fin) VALUES (:id , :des , :ini , :fin)";
    $query = $conn->prepare($sql);
    $query->bindParam(':id' , $_GET['menu']);
    $query->bindParam(':des' , $_POST['percent']);
    $query->bindParam(':ini' , $_POST['inicio']);
    $query->bindParam(':fin' , $_POST['fin']);
    if($query->execute()){
        header("location: ../../menu.php");
    } else {
        header("location: ../../menu.php?error");
    }
} else {
    header("location: ../../menu.php");
}
?>