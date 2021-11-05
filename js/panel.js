$(document).ready(function(){
    var pin = "";
    $('#panel_mesa').hide();
    $('#1').click(function(){
        pin = pin + 1;
        $('#pin').val(pin);
    });
    $('#2').click(function(){
        pin = pin + 2;
        $('#pin').val(pin);
    });
    $('#3').click(function(){
        pin = pin + 3;
        $('#pin').val(pin);
    });
    $('#4').click(function(){
        pin = pin + 4;
        $('#pin').val(pin);
    });
    $('#5').click(function(){
        pin = pin + 5;
        $('#pin').val(pin);
    });
    $('#6').click(function(){
        pin = pin + 6;
        $('#pin').val(pin);
    });
    $('#7').click(function(){
        pin = pin + 7;
        $('#pin').val(pin);
    });
    $('#8').click(function(){
        pin = pin + 8;
        $('#pin').val(pin);
    });
    $('#9').click(function(){
        pin = pin + 9;
        $('#pin').val(pin);
    });
    $('#0').click(function(){
        pin = pin + 0;
        $('#pin').val(pin);
    });
    $('#x').click(function(){
        pin = "";
        $('#pin').val("");
    });
    $('#p').click(function(){
        pin = pin + '.';
        $('#pin').val(pin);
    });
    /*--- mesa ---*/
    $('#ok').click(function(){
        $('#mesa').val(pin);
        $('#panel_mesa').hide();
    });
    $('#mesa_btn').click(function(){
        $('#panel_mesa').show();
    });
    $('#okf').click(function(){
        var back = 0;
        back = pin - total;
        $('#back').val(back.toFixed(2));
        $('#panel_factura').hide();
    });
    
    
    
    /*----- SEGUNDO PANEL -----*/
    var pins = "";
    $('#panel_cliente').hide();
    $('#1s').click(function(){
        pins = pins + 1;
        $('#pins').val(pins);
    });
    $('#2s').click(function(){
        pins = pins + 2;
        $('#pins').val(pins);
    });
    $('#3s').click(function(){
        pins = pins + 3;
        $('#pins').val(pins);
    });
    $('#4s').click(function(){
        pins = pins + 4;
        $('#pins').val(pins);
    });
    $('#5s').click(function(){
        pins = pins + 5;
        $('#pins').val(pins);
    });
    $('#6s').click(function(){
        pins = pins + 6;
        $('#pins').val(pins);
    });
    $('#7s').click(function(){
        pins = pins + 7;
        $('#pins').val(pins);
    });
    $('#8s').click(function(){
        pins = pins + 8;
        $('#pins').val(pins);
    });
    $('#9s').click(function(){
        pins = pins + 9;
        $('#pins').val(pins);
    });
    $('#0s').click(function(){
        pins = pins + 0;
        $('#pins').val(pins);
    });
    $('#xs').click(function(){
        pins = "";
        $('#pins').val("");
    });
    
    
    
    /*--- mesa ---*/
    $('#oks').click(function(){
        $('#cliente').val(pins);
        $('#panel_cliente').hide();
    });
    $('#cliente_btn').click(function(){
        $('#panel_cliente').show();
    });
    
    
    
    /*------- Frecuente -------*/
    $('#panel_frecuente').hide();
    
    $('#frecuencia_btn').click(function(){
        $('#panel_frecuente').show();
    });
    
    $('#frecc').click(function(){
        $('#panel_frecuente').hide();
        var cliente = $('#frequency').val();
        $('#freq').val(cliente);
    });
    
    /*-------- Notas --------*/
    $('#panel_nota').hide();
    
    $('#nota_btn').click(function(){
        $('#panel_nota').show();
    });
    
    $('#notate').click(function(){
        $('#panel_nota').hide();
        var nota = $('#texto_nota').val();
        $('#nota').val(nota);
    });
});