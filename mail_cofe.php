<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    header("location: inicio.php?error=mail");
}

require 'php/fun/conexion.php';
date_default_timezone_set("America/El_Salvador");
$sql = "SELECT * FROM Activo_C WHERE IDAC=:ida";
$query = $conn->prepare($sql);
$query->bindParam(':ida' , $_GET['id']);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

$leng = "SELECT * FROM Descripcion_C WHERE IDAC=:ida";
$consulta = $conn->prepare($leng);
$consulta->bindParam(':ida' , $_GET['id']);
$consulta->execute();
$total = 0;


$body = '<section style="width: 90%; margin-left: 5%;">
        <article style="background: antiquewhite; text-align:center; padding: 10px 0; width: 100%;">
            <img src="cid:logo" alt="logo xoxoctic" style="height: 90px;">
        </article>
        <article style="width: 100%; display: flex; justify-content: center;">
        <div style="width: 316px; margin: 25px 0;">
            <div style="text-align: center;">
            <img src="cid:little" alt="" style="width: 100px;">
            <h3>CASA XOXÓCTIC</h3>
            <h4>CASA XOXÓCTIC Café, bakery y eco tienda.</h4>
            <p>Avenida Tres Gardenias, Casa 10, San Salvador, San Salvador.</p>
            </div>
            <div style="text-align: left; display: flex; justify-content: space-around;">
                <span>Mesa: '.$row['Mesa'].'</span>
                <span>Mesero: '.$row['Mesero'].'</span>
            </div>
            <div style="text-align: left; display: flex; justify-content: space-around;">
                <p>Fecha: '.date("d-m-Y").'</p>
                <p>Hora: '.date("H:i:s").'</p>
            </div>
            <h5 style="text-align: center;">**** CUENTA ****</h5>
            <hr>
            <p style="display: flex; justify-content: space-around;"><span>Cant.</span> <span>Descripción</span><span>P.U.</span>  <span>IMP.</span></p>
            <hr>';

while($colum = $consulta->fetch(PDO::FETCH_ASSOC)){
    $s = "SELECT Producto , Precio FROM Menu_C WHERE IDMEC=:idme";
    $q = $conn->prepare($s);
    $q->bindParam(':idme' , $colum['IDMEC']);
    $q->execute();
    $r = $q->fetch(PDO::FETCH_ASSOC);
    
    $float =   '<p><span>'.$r['Producto'].'</span></p>
            <p style="display: flex; justify-content: space-around;">
                <span>'.$colum['Cantidad'].' Unidad</span>
                <span>$'.number_format($r['Precio'] , 2).'</span>
                <span>$'.number_format(($r['Precio'] * $colum['Cantidad']) , 2).'</span>
            </p>
            <hr>';
    
    $total = $total + ($r['Precio'] * $colum['Cantidad']);
    $body = $body . $float;
}
                
$total =   '<p style="display: flex; justify-content: space-between;">
                <strong><span>Total</span></strong>
                <strong><span>$'.number_format($total , 2).'</span></strong>
            </p>
            <hr>
            <div style="text-align: center;">
            <span>Gracias por su compra</span><br>
            <span>Casa Xoxóctic le espera pronto</span><br>
            <span>Tel: +503 7697-0647</span><br>
            <span>https://www.casaxoxoctic.com/</span>
            </div>
        </div>
        </article>
        <article style="background: rgba(0,0,0,0.2); text-align: center; width: 100%; padding: 15px 0; font-family: sans-serif;">
            <div style="text-align: center; height: 150px;">
            <ul style="display: flex; list-style: none; padding: 0; text-align: center; position: absolute; left: 50%; transform: trasalteX(-50%);">
                <li style="text-align: center;"><a href="https://www.facebook.com/xoxoctic503" target="_blank"><img src="cid:facebook" alt="" style="width: 30px; height: 30px; margin: 5px 15px; border-radius: 50px;"></a></li>
                <li style="text-align: center;"><a href="https://www.instagram.com/xoxocticsv/" target="_blank"><img src="cid:instagram" alt="" style="width: 30px; height: 30px; margin: 5px 15px; border-radius: 50px;"></a></li>
                <li style="text-align: center;"><a href="https://api.whatsapp.com/send?phone=" target="_blank"><img src="cid:whatsapp" alt="" style="width: 30px; height: 30px; margin: 5px 15px; border-radius: 50px;"></a></li>
            </ul>
            </div>
            <p style="font-size: 12px; color: #333; padding: 0 10px; line-height: 20px;">No conteste a este mensaje. Para ponerse en contacto con nostros escribanos al correo o llamenos al +503 7697-0647</p>
            <p style="color: #333; font-size: 15px; padding: 7px;">xoxoctic es una empresa familiar orgullosos de ser de El Salvador.</p>
        </article>
    </section>';
   $body = $body . $total;
   echo $body;
//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if(isset($_POST['mail'])){
        $re_mail = $_POST['mail'];
        if(isset($_POST['name'])){
            $name_mail = $_POST['name'];
        } else {
            $name_mail = 'Anónimo';
        }
    } else {
        header("location: inicio.php?error=correo");
    }
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true; 
    $mail->Username   = 'theplantshopsv@gmail.com'; 
    $mail->Password   = 'Ji2709501-';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('theplantshopsv@gmail.com', 'Casa Xoxoctic');
    $mail->addAddress($re_mail , $name_mail); 

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Factura de Casa Xoxoctic';
    $mail->AddEmbeddedImage('img/logo.png' , 'logo');
    $mail->AddEmbeddedImage('print/logos.png' , 'little');
    $mail->AddEmbeddedImage('img/fb.png' , 'facebook');
    $mail->AddEmbeddedImage('img/in.png' , 'instagram');
    $mail->AddEmbeddedImage('img/wh.png' , 'whatsapp');
    $mail->Body = $body;

    $mail->send();
    header("location: php/fun/finalizar_orden.php?id=$id");
} catch (Exception $e) {
    echo "mensage de error Mailer Error: {$mail->ErrorInfo}";
    //header("location: inicio.php?error=mail");
}