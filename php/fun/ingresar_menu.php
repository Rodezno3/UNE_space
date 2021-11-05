<?php 
if(isset($_POST)){
    if(!empty($_POST['pro_ing']) && !empty($_POST['pre_ing'])){
        require "conexion.php";
        $sql = "INSERT INTO Menu_C (Producto , Precio , Grupo) VALUES (:pro , :pre , :gru)";
        $query = $conn->prepare($sql);
        $query->bindParam(':pro' , $_POST['pro_ing']);
        $query->bindParam(':pre' , $_POST['pre_ing']);
        $query->bindParam(':gru' , $_POST['tipo']);
        if($query->execute()){
            header("location: ../../menu.php?value=true");
        } else {
            header("location: ../../menu.php?value=false");
        }
    } else {
        header("location: ../../menu.php?value=false0");
    }
} else {
    header("location: ../../menu.php");
}
?>