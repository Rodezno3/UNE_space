$(document).ready(function(){
    $('#addimg').hide();
    
    $('#open').click(function(){
        $('#addimg').show();
    });
    $('#close').click(function(){
        $('#addimg').hide();
    });
    
    /*---- ----*/
    var imagen = '';
    $("#1").click(function(){
        imagen = '1.png';
    });
    $("#2").click(function(){
        imagen = '2.png';
    });
    $("#3").click(function(){
        imagen = '3.png';
    });
    $("#4").click(function(){
        imagen = '4.png';
    });
    $("#5").click(function(){
        imagen = '5.png';
    });
    $("#6").click(function(){
        imagen = '6.png';
    });
    $("#7").click(function(){
        imagen = '7.png';
    });
    $("#8").click(function(){
        imagen = '8.png';
    });
    $("#9").click(function(){
        imagen = '9.png';
    });
    $("#10").click(function(){
        imagen = '10.png';
    });
    $("#11").click(function(){
        imagen = '11.png';
    });
    $("#12").click(function(){
        imagen = '12.png';
    });
    $("#13").click(function(){
        imagen = '13.png';
    });
    $('#close').click(function(){
        $('#addimg').hide();
        $('#img').val(imagen);
    });
});