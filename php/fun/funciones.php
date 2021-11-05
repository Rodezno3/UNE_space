<?php 
//ingresar el id del menú
function Precio($menu){
    require "conexion.php";
    $man = "SELECT Precio FROM Menu_C WHERE IDMEC=:idme";
    $men = $conn->prepare($man);
    $men->bindParam(':idme' , $menu);
    $men->execute();
    
    $dan = "SELECT Descuento FROM Descuento_C WHERE IDMEC=:id";
    $den = $conn->prepare($dan);
    $den->bindParam(':id' , $menu);
    $den->execute();
    
    $din = $den->fetch(PDO::FETCH_ASSOC);
    
    $min = $men->fetch(PDO::FETCH_ASSOC);
    
    if(!empty($din['Descuento'])){
        $descuento = (($min['Precio'] * $din['Descuento'])/100);
        $precio = $min['Precio'] - $descuento;
    } else {
        $precio = $min['Precio'];
    }
    
    return $precio;
}

//ingresar el id del menú
function PrecioFac($menu){
    require "conexion.php";
    $man = "SELECT Precio, Descuento FROM Menu WHERE IDME=:idme";
    $men = $conn->prepare($man);
    $men->bindParam(':idme' , $menu);
    $men->execute();
    $min = $men->fetch(PDO::FETCH_ASSOC);
    if($min['Descuento'] == 0){
        $pri = $min['Precio'];
    } else {
        $mon = (($min['Descuento']/100)*$min['Precio']);
        $pri = $min['Precio'] - $mon;
    }
    return $pri;
}
?>