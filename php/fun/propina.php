<?php 
if(isset($_GET['ida'])){
    if($_GET['date'] == 1){
        require "conexion.php";
        $sql = "UPDATE Activo SET Propina=0 WHERE IDA=:ida";
        $query = $conn->prepare($sql);
        $query->bindParam(':ida' , $_GET['ida']);
        $query->execute();
        header("location: ../../inicio.php");
    } elseif($_GET['date'] == 0){
        require "conexion.php";
        $sql = "UPDATE Activo SET Propina=1 WHERE IDA=:ida";
        $query = $conn->prepare($sql);
        $query->bindParam(':ida' , $_GET['ida']);
        $query->execute();
        header("location: ../../inicio.php");
    } else {
        header("location: ../../inicio.php");
    }
} else {
    header("location: ../../inicio.php");
}
?>