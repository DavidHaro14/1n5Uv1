$(document).ready(function(){
    $(".SimpleSelect").chosen({
        width: "100%",
        no_results_text: "No se encontro: ",
        max_shown_results: 10
    });

    $(".MultiSelect").chosen({
        width: "100%",
        placeholder_text_multiple: "Seleccionar",
        display_selected_options: false,
        no_results_text: "No se encontro: ",
    });

    $('.readonly').keydown(function(e){
        e.preventDefault();
    });

    $('.Letrero').tooltip();

    $(document).on('focus','input,textarea',function(){
        $(this).select();
    });

    $(document).on('keyup','input[type="text"],textarea',function(){
        this.value = this.value.toUpperCase();
    });
    
    $(document).on('change','input[type="text"],textarea',function(){
        this.value = this.value.toUpperCase();
    });

    $(document).on('click','button[type="submit"],input[type="submit"]',function(){
        if ($(this).text() != ' Buscar') {
            var confirmacion = confirm('Seguro que desea continuar?');
            if (!confirmacion) {return false;}
        }
    });

    //Solo numerico y punto
    $(document).on('keydown','.OnlyNumber, input[type="number"]',function(e){
        // Disponible: borrar, Enter
        if ($.inArray(e.keyCode, [110, 190, 9, 8, 13, 109]) !== -1 ||
             // Disponible: inicio, fin, izquierda, derecha
            (e.keyCode >= 37 && e.keyCode <= 40)) {
                 return;
        }
        // Solo usar teclas numericas
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();//retornar
        }
    });

    //Activar Solo Teclas Numericas y alfanumericas Inputs
    $(document).on('keydown','OnlyNumberDomicilio',function(e){
        // Disponible: borrar, Enter
        if ($.inArray(e.keyCode, [9, 8, 13, 109]) !== -1 ||
             // Disponible: inicio, fin, izquierda, derecha
            (e.keyCode >= 37 && e.keyCode <= 40)) {
                 return;
        }
        // Solo usar teclas numericas y alfanumericas
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && (e.keyCode < 65 || e.keyCode > 90)) {
            e.preventDefault();//retornar
        }
    });

});