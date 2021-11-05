<?php 
session_start();
if(!isset($_SESSION['mesero'])){
    header("location: index.php");
}
require "php/fun/tiempo.php";
require "php/fun/conexion.php";
require "php/fun/funciones.php";
if(isset($_COOKIE['menu'])){
    var_dump($_COOKIE);
    echo "Si hay una coookie";
}
for($i=0 ; $i<=24 ; $i++){
    $a = "a".$i;
    $b = "b".$i;
    if(isset($_COOKIE[$a])){
        setcookie($a , false , $expire = time() -100 , "/");
        setcookie($b , false , $expire = time() -100 , "/");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Casa Xoxoctic</title>
    <!-- estilos -->
    <link rel="stylesheet" href="css/estilos_header.css">
    <link rel="stylesheet" href="css/estilos_menu.css">
    <link rel="stylesheet" href="css/estilos_orden.css">
    <link rel="stylesheet" href="css/estilos_index.css">
    <link rel="stylesheet" href="css/fontello.css">
    <!-- scripts -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script type="text/javascript">
    var total = 0;
    var cook = 0;
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        var llevar = 0;
        $('#palle').click(function(){
            if(llevar == 0){
                $('#palle').css({'background':'forestgreen'});
                llevar = 1;
            } else {
                $('#palle').css({'background':'#fff'});
                llevar = 0;
            }
        });
    });
    </script>
    <script type="text/javascript" src="js/panel.js"></script>
    <script src="js/js.cookies.js"></script>
</head>
<body>
    <?php require "php/header.php"; ?>
    <div id="contenedor_preorden">
    <section class="preorden">
        <h3>Pre Orden</h3>
        <hr>
        <div id="prepa">
        </div>
        <hr>
        <p><span class="toty">Total:</span> <span class="toty">$ <input type="text" value="0.00" id="tere"> </span></p>
        <a href="nueva_orden.php"><button id="limpiar">Limpiar Orden</button></a>
    </section>
    <section class="menu_contenedor">
        <form action="php/fun/add_orden.php" method="post">
            <article id="info_cliente">
                <input type="text" placeholder="Nombre Cliente" id="name_cliente" name="name" value="Anónimo" required>
                <p id="mesa_btn">Mesa</p>
                <input type="text" style="display:none;" name="mesa" id="mesa" required>
                <p id="frecuencia_btn"><span class="icon-users"></span></p>
                <input type="text" style="display:none;" name="freq" id="freq" >
                <p id="cliente_btn">N° de Cliente</p>
                <input type="text" style="display:none;" name="cliente" id="cliente" required>
                <label for="llevar" id="palle">Para llevar</label>
                <input type="checkbox" id="llevar" name="llevar" value="1">
                <p id="nota_btn">Agregar Nota</p>
                <input type="text" style="display:none;" name="nota" id="nota">
                <input type="submit" value="Agregar Orden" id="enviar">
            </article>
        </form>
        <article class="contenedor_cubo">
            <div class="cubo" id="tipo1">
                <img src="img/iconos/004-taza-de-caf-1.png" alt="">
                <p>Espresso</p>
            </div>
            <div class="cubo" id="tipo2">
                <img src="img/iconos/005-taza-de-caf-2.png" alt="">
                <p>Iced Coffee</p>
            </div>
            <div class="cubo" id="tipo3">
                <img src="img/iconos/006-t-helado.png" alt="">
                <p>Cold Drinks</p>
            </div>
            <div class="cubo" id="tipo4">
                <img src="img/iconos/002-t-verde.png" alt="">
                <p>Hot Drinks</p>
            </div>
            <div class="cubo" id="tipo5">
                <img src="img/iconos/001-maquina-de-cafe.png" alt="">
                <p>Specialty Coffe</p>
            </div>
            <div class="cubo" id="tipo6">
                <img src="img/iconos/008-panadera.png" alt="">
                <p>Bread order</p>
            </div>
            <div class="cubo" id="tipo7">
                <img src="img/iconos/007-tarta-de-queso.png" alt="">
                <p>House Sweets</p>
            </div>
            <div class="cubo" id="tipo8">
                <img src="img/iconos/009-galleta-salada.png" alt="">
                <p>Snacks</p>
            </div>
            <div class="cubo" id="tipo9">
                <img src="img/iconos/pizza-slice.png" alt="">
                <p>Pizza</p>
            </div>
            <div class="cubo" id="tipo10">
                <img src="img/iconos/evento.png" alt="">
                <p>Eventos</p>
            </div>
            <div class="cubo" id="tipo11">
                <img src="img/iconos/cerveza.png" alt="">
                <p>Bebidas Alcoholicas</p>
            </div>
            <div class="cubo" id="tipo12">
                <img src="img/iconos/sandwich.png" alt="">
                <p>Sándwich</p>
            </div>
            <div class="cubo" id="tipo13">
                <img src="img/iconos/mantequilla-de-mani.png" alt="">
                <p>Productos Procesados</p>
            </div>
        </article>
        <?php
        $hit = 13;
        for($i=1 ; $i<=$hit ; $i++){
        ?>
        <article class="menu_detalle" id="menu<?= $i; ?>">
            <ul>
            <?php 
            $sql = "SELECT * FROM Menu_C WHERE Grupo = :i";
            $query = $conn->prepare($sql);
            $query->bindParam(':i' , $i);
            $query->execute();
            $kil = $i;
            $idu = $kil . 10;
            while($a = $query->fetch(PDO::FETCH_ASSOC)){
            ?>
                <li>
                    <div class="md_info">
                        <p><?= $a['Producto']; ?></p>
                        <p>$<?= number_format(Precio($a['IDMEC']) , 2); ?></p>
                    </div>
                    <div class="md_boton">
                        <span id="less<?= $idu; ?>" class="minus">-</span>
                        <input type="text" value="1" max="100" id="uni<?= $idu; ?>" class="formate">
                        <span id="plus<?= $idu; ?>" class="plusle">+</span>
                        <button id="s<?= $idu; ?>"><span></span> Agregar</button>
                        <button id="n<?= $idu; ?>"><span></span> Quitar</button>
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        //número
                        var uni<?= $idu; ?> = 1;
                        $('#plus<?= $idu; ?>').click(function(){
                            if(uni<?= $idu; ?> < 100){
                                uni<?= $idu; ?>++;
                                $('#uni<?= $idu; ?>').val(uni<?= $idu; ?>);
                            }
                        });
                        $('#less<?= $idu; ?>').click(function(){
                            if(uni<?= $idu; ?> > 1){
                                uni<?= $idu; ?>--;
                                $('#uni<?= $idu; ?>').val(uni<?= $idu; ?>);
                            }
                        });
                        //'cookie
                        $('#n<?= $idu; ?>').hide();
                        var ki<?= $idu; ?> = 0;
                        var ko<?= $idu; ?> = 0;
                        var hu<?= $idu; ?> = 0;
                        $('#s<?= $idu; ?>').click(function(){
                            //funcion de cookie
                            var pri = <?= Precio($a['IDMEC']); ?>;
                            var rip = $('#uni<?= $idu; ?>').val();
                            var pir = (pri * rip).toFixed(2);
                            var supcook = 'a' + cook;
                            var subcook = 'b' + cook;
                            ko<?= $idu; ?> = supcook;
                            hu<?= $idu; ?> = subcook;
                            Cookies.set(supcook , '<?= $a['IDMEC']; ?>' , { expires: 1 , path: '/' });
                            Cookies.set(subcook , rip , { expires: 1 , path: '/' });
                            ki<?= $idu; ?> = rip;
                            cook++;
                            total = (parseFloat(total) + parseFloat(pir)).toFixed(2);
                            $('#tere').val(total);
                            $('#prepa').append('<p id="<?= $idu; ?>"><span>'+rip+' <?= $a['Producto']; ?></span> <span> $'+pir+'</span></p>');
                            $('#n<?= $idu; ?>').show();
                            $('#s<?= $idu; ?>').hide();
                        });
                        $('#n<?= $idu; ?>').click(function(){
                            //funcion de cookie
                            var fir = <?= Precio($a['IDMEC']); ?>;
                            var rif = $('#uni<?= $idu; ?>').val();
                            if(rif > ki<?= $idu; ?>){
                                rif = ki<?= $idu; ?>;
                            }
                            var fri = (fir * rif).toFixed(2);
                            Cookies.set(ko<?= $idu; ?> , '<?= $a['Producto']; ?>' , { expires: -1 , path: '/' });
                            Cookies.set(hu<?= $idu; ?> , rif , { expires: -1 , path: '/' });
                            total = (parseFloat(total) - parseFloat(fri)).toFixed(2);
                            $('#tere').val(total);
                            $('#<?= $idu; ?>').remove();
                            $('#s<?= $idu; ?>').show();
                            $('#n<?= $idu; ?>').hide();
                        });
                    });
                    </script>
                </li>
            <?php $idu++; } ?>
                <li></li>
            </ul>
        </article>
        <?php } ?>
    </section>
    </div>
    <div id="panel_mesa">
        <section id="cubo">
            <h1>Número de mesa</h1>
                <input type="text" name="pin" maxlength="5" id="pin"><br>
            <article id="panel">
                <ul>
                    <li id="1">1</li>
                    <li id="2">2</li>
                    <li id="3">3</li>
                </ul>
                <ul>
                    <li id="4">4</li>
                    <li id="5">5</li>
                    <li id="6">6</li>
                </ul>
                <ul>
                    <li id="7">7</li>
                    <li id="8">8</li>
                    <li id="9">9</li>
                </ul>
                <ul>
                    <li id="x" class="cancel">X</li>
                    <li id="0">0</li>
                    <li id="ok" class="go">Ok</li>
                </ul>
            </article>
        </section>
    </div>
    <div id="panel_cliente">
        <section id="cubo">
            <h1>Número de Clientes</h1>
                <input type="text" name="pin" maxlength="5" id="pins"><br>
            <article id="panel">
                <ul>
                    <li id="1s">1</li>
                    <li id="2s">2</li>
                    <li id="3s">3</li>
                </ul>
                <ul>
                    <li id="4s">4</li>
                    <li id="5s">5</li>
                    <li id="6s">6</li>
                </ul>
                <ul>
                    <li id="7s">7</li>
                    <li id="8s">8</li>
                    <li id="9s">9</li>
                </ul>
                <ul>
                    <li id="xs" class="cancel">X</li>
                    <li id="0s">0</li>
                    <li id="oks" class="go">Ok</li>
                </ul>
            </article>
        </section>
    </div>
    <div id="panel_frecuente">
        <section id="cubo">
             <h1>Seleccione a Cliente Frecuente</h1>
             <select name="clifre" id="frequency">
                 <option selected disabled>-- Selecciona --</option>
                 <?php 
                 $xql = "SELECT IDCF, Nombre, Email FROM Cliente_Frecuente";
                 $xuery = $conn->prepare($xql);
                 $xuery->execute();
                 while($xow = $xuery->fetch(PDO::FETCH_ASSOC)){
                 ?>
                 <option value="<?= $xow['IDCF']; ?>"><?= $xow['Nombre']." - ".$xow['Email']; ?></option>
                 <?php } ?>
             </select><br>
             <input type="button" value="Aceptar" id="frecc">
        </section>
    </div>
    <div id="panel_nota">
        <section id="cubo_2">
             <h1>Escribe una nota para cocina.</h1>
             <textarea name="" id="texto_nota"></textarea><br>
             <input type="button" value="Aceptar" id="notate">
        </section>
    </div>
</body>
</html>