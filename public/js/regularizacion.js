$(document).ready(function(){	
	//Activar Solo Teclas Numericas Inputs
    // $('.OnlyNumber').keydown(function (e) {
    //     // Disponible: borrar, Enter
    //     if ($.inArray(e.keyCode, [9, 8, 13, 109]) !== -1 ||
    //          // Disponible: inicio, fin, izquierda, derecha
    //         (e.keyCode >= 37 && e.keyCode <= 40)) {
    //              return;
    //     }
    //     // Solo usar teclas numericas
    //     if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    //         e.preventDefault();//retornar
    //     }
    // });

    // $('.OnlyMoney').keydown(function (e) {
    //     // Disponible: borrar, Enter
    //     if ($.inArray(e.keyCode, [110, 190, 9, 8, 13, 109]) !== -1 ||
    //          // Disponible: inicio, fin, izquierda, derecha
    //         (e.keyCode >= 37 && e.keyCode <= 40)) {
    //              return;
    //     }
    //     // Solo usar teclas numericas
    //     if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    //         e.preventDefault();//retornar
    //     }
    // });

    // //Seleccionar contenido de input
    // $('.OnlyNumber, .OnlyMoney').focus(function(){
    //     this.select();
    // });


    // Consultar los Municipios del estado seleccionado
    $('#state').change(event => {
        $.get(`/MunicipiosGet/${event.target.value}`, function(res, sta){
            $('#town').empty();
            $('#town').append('<option selected value=""> Seleccionar </option>');
            res.forEach(element => {
                $('#town').append(`<option value=${element.id_municipio}> ${element.nombre} </option>`);
            });
        });
    });

    // Consultar las localidades del municipio seleccionado
    $('#town').change(event => {
        $.get(`/LocalidadesGet/${event.target.value}`, function(res, sta){
            $('#location').empty();
            $('#location').append('<option selected value=""> Seleccionar </option>');
            res.forEach(element => {
                $('#location').append(`<option value=${element.id_localidad}> ${element.nombre} </option>`);
            });
        });
    });

    // Consultar las colonias de la localidad seleccionada
    $('#location').change(event => {
        $.get(`/ColoniasGet/${event.target.value}`, function(res, sta){
            $('#colonie').empty();
            $('#colonie').append('<option selected value=""> Seleccionar </option>');
            res.forEach(element => {
                $('#colonie').append(`<option value=${element.id_colonia}> ${element.nombre} </option>`);
            });
        });
    }); 

    // Consultar las calles de la colonia seleccionada
    $('#colonie').change(event => {
        $.get(`/CallesGet/${event.target.value}`, function(res, sta){
            $('#street').empty();
            $('#street').append('<option selected value=""> Seleccionar </option>');
            res.forEach(element => {
                $('#street').append(`<option value=${element.id_calle}> ${element.nombre} </option>`);
            });
        });
    });
});