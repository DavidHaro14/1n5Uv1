$(document).ready(function(){

    /* PRETICIONES REST DE RENAPO */
    // SOlICITANTE
    $('.Sbuscar-datos').click(function(){
        var curp = $('#SolicitanteCurp').val();
        $.ajax({
            url:"/RenapoDatosGet",
            type:"GET",
            dataType:"json",
            data:{
                curp:curp
            },
            success:function(data){
                //console.log(data.REST_Service.status_response);
                var estatus = data.REST_Service.status_response;
                var mensaje = data.REST_Service.message;

                if(estatus != "ERROR" && estatus != "FALSE"){
                    $('#SolicitanteNombre').val(data.response.nombres).prop('readonly',true);
                    $('#SolicitantePaterno').val(data.response.apellido1).prop('readonly',true);
                    $('#SolicitanteMaterno').val(data.response.apellido2).prop('readonly',true);
                    $('#date_nac').val(String(data.response.fechNac).substring(6) + "-" + String(data.response.fechNac).substring(3,5) + "-" + String(data.response.fechNac).substring(0,2));
                    $('#SolicitanteCurp').prop('readonly',true);
                    $('.Sbuscar-datos').prop('disabled',true);
                    $('.Sbuscar-curp').prop('disabled',true);
                    switch(data.response.sexo){
                        case 'H': $('#gender').val("MASCULINO");break;
                        case 'M': $('#gender').val("FEMENINO");break;
                    }
                } else {
                    alert("ERROR: "+ mensaje);
                }
            }
        });
    });

    $('.Sbuscar-curp').click(function(){
        var nombre  = $('#SolicitanteNombre').val();
        var paterno = $('#SolicitantePaterno').val();
        var materno = $('#SolicitanteMaterno').val();
        var sexo    = "H";
        var f_nac   = $('#date_nac').val();

        if($('#gender').val() != "MASCULINO"){sexo = "M";}
        f_nac = f_nac.substring(8) + "/" + f_nac.substring(5,7) + "/" + f_nac.substring(0,4);

        $.ajax({
            url:"/RenapoCurpGet",
            type:"GET",
            dataType:"json",
            data:{
                nombre:nombre.trim(),
                ape_pa:paterno.trim(),
                ape_ma:materno.trim(),
                genero:sexo,
                f_nac:f_nac
            },
            success:function(data){
                var estatus = data.REST_Service.status_response;
                var mensaje = data.REST_Service.message;

                if(estatus != "ERROR" && estatus != "FALSE"){
                    $('#SolicitanteNombre').prop('readonly',true);
                    $('#SolicitantePaterno').prop('readonly',true);
                    $('#SolicitanteMaterno').prop('readonly',true);
                    $('#SolicitanteCurp').val(data.response.CURP).prop('readonly',true);
                    $('.Sbuscar-datos').prop('disabled',true);
                    $('.Sbuscar-curp').prop('disabled',true);
                } else {
                    alert("ERROR: "+ mensaje);
                }
            }
        });
    });
    
    // CONYUGE
    $('.Cbuscar-curp').click(function(){
        var nombre  = $('#ConyugeNombre').val();
        var paterno = $('#ConyugePaterno').val();
        var materno = $('#ConyugeMaterno').val();
        var sexo    = "";
        var f_nac   = $('#cony_fecha_nac').val();

        f_nac = f_nac.substring(8) + "/" + f_nac.substring(5,7) + "/" + f_nac.substring(0,4);

        $.ajax({
            url:"/RenapoCurpGet",
            type:"GET",
            dataType:"json",
            data:{
                nombre:nombre.trim(),
                ape_pa:paterno.trim(),
                ape_ma:materno.trim(),
                genero:sexo,
                f_nac:f_nac
            },
            success:function(data){
                var estatus = data.REST_Service.status_response;
                var mensaje = data.REST_Service.message;

                if(estatus != "ERROR" && estatus != "FALSE"){
                    $('#ConyugeNombre').prop('readonly',true);
                    $('#ConyugePaterno').prop('readonly',true);
                    $('#ConyugeMaterno').prop('readonly',true);
                    $('#ConyugeCurp').val(data.response.CURP).prop('readonly',true);
                    $('.Cbuscar-curp').prop('disabled',true);
                } else {
                    alert("ERROR: "+ mensaje);
                }
            }
        });
    });

	$('#TabConyuge').hide();//Ocultar Pestaña de Conyuge

    //Si Esta Casado, Viudo o Union Libre Mostrar Formulario del conyuge
	$('.EstadoCivil').change(function(){
		if($('.EstadoCivil').val() == 'VIUDO(A)' || $('.EstadoCivil').val() == 'UNION LIBRE' || $('.EstadoCivil').val() == 'CASADO(A)'){
            //Mostrar TabConyuge y que sea requerido los datos del conyuge
      		$('#TabConyuge').fadeIn(); 
            $('.DatosConyuge').prop('required',true);
            $('#BtnContinuarGeneral').removeClass('BtnGeneral').removeClass('BtnGeneralConyuge').addClass('BtnGeneralConyuge');
            $('#BtnRegresarConyuge').removeClass('BtnConyuge2').removeClass('BtnGeneralConyuge').addClass('BtnGeneralConyuge');

        if($('.EstadoCivil').val() == 'CASADO(A)'){
            //Mostrar Input Bienes y que sea requerido 
            $('.bienes').fadeIn(); 
            $('#bienes').prop('required',true);

        } else { 
            //Ocultar Input Bienes y que no sea requerido 
            $('.bienes').fadeOut(); 
            $('#bienes').prop('required',false);
        }

        } else {
            $('#BtnContinuarGeneral').removeClass('BtnGeneral').removeClass('BtnGeneralConyuge').addClass('BtnGeneral');
            $('#BtnRegresarConyuge').removeClass('BtnConyuge2').removeClass('BtnGeneralConyuge').addClass('BtnConyuge2');
            //Vaciar datos inputs del conyuge
            $('.Cbuscar-curp').prop('disabled',false);
            $('.DatosConyuge').val("").prop('readonly',false);
            $('#cony_fecha_nac').prop('readonly',true);
            $('#bienes').val("");

            //Quitar required inputs
            $('.DatosConyuge').prop('required',false);
            $('#bienes').prop('required',false);

            //Ocultar TabConyuge
            $('#TabConyuge').fadeOut(); 
        }
	});

    /*
    *   Select Estado    -> #state
    *   Select Municipio -> #town
    *   Desplegar Municipios Segun el Estado Seleccionado
    */

    $('#state').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"MunicipiosGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#town').append('<option selected value=""> Cargando... </option>');
            //     $('#town').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#town').prop('disabled',false);
                    $('#town').empty();
                    $('#town').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#town').append('<option value="'+dato.id_municipio+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#town').empty();
                    $('#town').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Municipio -> #town
    *   Select Localidad -> #location
    *   Desplegar Localidades Segun el Municipio Seleccionado
    */

    $('#town').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"LocalidadesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#location').append('<option selected value=""> Cargando... </option>');
            //     $('#location').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#location').prop('disabled',false);
                    $('#location').empty();
                    $('#location').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#location').append('<option value="'+dato.id_localidad+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#location').empty();
                    $('#location').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Localidad -> #location
    *   Select Colonia   -> #colonie
    *   Desplegar Colonias Segun la Localidad Seleccionada
    */
    
    $('#location').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"ColoniasGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#colonie').append('<option selected value=""> Cargando... </option>');
            //     $('#colonie').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#colonie').prop('disabled',false);
                    $('#colonie').empty();
                    $('#colonie').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#colonie').append('<option value="'+dato.id_colonia+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#colonie').empty();
                    $('#colonie').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Colonia   -> #colonie
    *   Select Calle     -> #street
    *   Desplegar Calles Segun la Colonia Seleccionada
    */
    
    $('#colonie').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"CallesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#street').append('<option selected value=""> Cargando... </option>');
            //     $('#street').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#street').prop('disabled',false);
                    $('#street').empty();
                    $('#street').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#street').append('<option value="'+dato.id_calle+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#street').empty();
                    $('#street').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    //Buscador Scopes
    $('#btn-buscar').click(function(){
        var dato = $('#dato').val();
        var tipo = $('#tipo').val();

      $.ajax({
        url : '/GetPreRegistro',
        type : 'GET',
        dataType : 'json',
        data : {'buscar': dato, 'tipo': tipo},
        success:function(data){
            //Si existe el preregistro
            if(data != ""){
                $('.msj-error').fadeOut();
                $.each(data, function(index, data){
                    $('#SolicitanteNombre').val(data.solicitante_nombre).addClass('input-success');
                    $('#SolicitantePaterno').val(data.solicitante_ape_paterno).addClass('input-success');
                    $('#SolicitanteMaterno').val(data.solicitante_ape_materno).addClass('input-success');
                    $('#SolicitanteCurp').val(data.solicitante_curp).addClass('input-success');
                    $('#mail').val(data.correo).addClass('input-success');
                    $('#phone').val(data.telefono).addClass('input-success');
                    $('#state_nac').val(data.estado_nac).addClass('input-success');
                    $('#place_nac').val(data.lugar_nac).addClass('input-success');
                    $('#gender').val(data.genero).addClass('input-success');
                    $('#date_nac').val(data.fecha_nac).addClass('input-success');
                    $('#dependient').val(data.no_dependientes).addClass('input-success');
                    $('#ocupation').val(data.ocupacion_id).addClass('input-success');
                    $('#school').val(data.escolaridad).addClass('input-success');
                    $('#contra').val(data.password);
                    $('.EstadoCivil').val(data.estado_civil).addClass('input-success');
                    if(data.estado_civil == 'VIUDO(A)' || data.estado_civil == 'UNION LIBRE' || data.estado_civil == 'CASADO(A)'){
                       //Mostrar TabConyuge
                        $('#TabConyuge').fadeIn(); 
                        $('.DatosConyuge').prop('required',true);

                        if(data.estado_civil == 'CASADO(A)'){

                            $('.bienes').fadeIn(); 
                            $('#bienes').prop('required',true);
                            $('#bienes').val(data.bienes).addClass("input-success");

                        } else { 
                            $('.bienes').fadeOut(); 
                            $('#bienes').prop('required',false);
                            $('#bienes').val("data.bienes").removeClass("input-success");
                        }

                        $('#ConyugeNombre').val(data.conyuge_nombre).addClass("input-success");
                        $('#ConyugePaterno').val(data.conyuge_ape_paterno).addClass("input-success");
                        $('#ConyugeMaterno').val(data.conyuge_ape_materno).addClass("input-success");
                        $('#ConyugeCurp').val(data.conyuge_curp).addClass("input-success");
                        $('#cony_fecha_nac').val(data.conyuge_fecha_nac).addClass("input-success");
                        $('#cony_lugar_nac').val(data.conyuge_lugar_nac).addClass("input-success");
                    }
                });
            } else {
                //Si no existe el preregistro vaciar todos los inputs
                $('#SolicitanteNombre').val("").removeClass("input-success");
                $('#SolicitantePaterno').val("").removeClass("input-success");
                $('#SolicitanteMaterno').val("").removeClass("input-success");
                $('#SolicitanteCurp').val("").removeClass("input-success");
                $('#mail').val("").removeClass("input-success");
                $('#phone').val("").removeClass("input-success");
                $('#state_nac').val("").removeClass("input-success");
                $('#place_nac').val("").removeClass("input-success");
                $('#gender').val("").removeClass("input-success");
                $('#date_nac').val("").removeClass("input-success");
                $('#dependient').val("0").removeClass("input-success");
                $('#ocupation').val("").removeClass("input-success");
                $('#school').val("").removeClass("input-success");
                $('#contra').val("");
                $('.EstadoCivil').val("").removeClass("input-success");
                $('.DatosConyuge').val("").removeClass("input-success");

                //Quitar required inputs
                $('.DatosConyuge').prop('required',false);
                $('#bienes').prop('required',false);

                //Ocultar TabConyuge
                $('#TabConyuge').fadeOut(); 

                $('#msj').empty();
                $('.msj-error').fadeIn();
                $('#msj').append("No Se Encontro Ningun Registro");
            }
        },
        error:function(msj){
          $('#msj').html(msj.responseJSON.buscar);
          $('.msj-error').fadeIn();
        }
      });
    });
    
    //Cerrar alert
    $('.close-alert').click(function(){
        $('.msj-error').fadeOut();
    });

    /*
    *   Si elige una Adquisicion
    *   Ocultar Selects
    *   AutoConstruccion
    *   Mejoramiento
    */

    $('#Adquisicion').change(function(){
        var adquisicion = $('#Adquisicion').val();
        console.log(adquisicion);
        if(adquisicion != ""){
            $('#DivMejoramiento').fadeOut();
            $('#DivAutoConstruccion').fadeOut();
            $('#Adquisicion').prop('required',true);
            $('#AutoConstruccion').prop('required',false);
            $('#Mejoramiento').prop('required',false);
        }else{
            $('#DivMejoramiento').fadeIn();
            $('#DivAutoConstruccion').fadeIn();
            $('#Adquisicion').prop('required',true);
            $('#AutoConstruccion').prop('required',true);
            $('#Mejoramiento').prop('required',true);
        }
    });

    /*
    *   Si elige un Autoconstruccion
    *   Ocultar Selects
    *   Adquisicion
    *   Mejoramiento
    */

    $('#AutoConstruccion').change(function(){
        var autoconstruccion = $('#AutoConstruccion').val();
        console.log(autoconstruccion);
        if(autoconstruccion != ""){
            $('#DivMejoramiento').fadeOut();
            $('#DivAdquisicion').fadeOut();
            $('#AutoConstruccion').prop('required',true);
            $('#Mejoramiento').prop('required',false);
            $('#Adquisicion').prop('required',false);
        }else{
            $('#DivMejoramiento').fadeIn();
            $('#DivAdquisicion').fadeIn();
            $('#AutoConstruccion').prop('required',true);
            $('#Adquisicion').prop('required',true);
            $('#Mejoramiento').prop('required',true);
        }
    });

    /*
    *   Si elige un Mejoramiento
    *   Ocultar Selects
    *   Adquisicion
    *   Autocontruccion
    */

    $('#Mejoramiento').change(function(){
        var mejoramiento = $('#Mejoramiento').val();
        console.log(mejoramiento);
        if(mejoramiento != ""){
            $('#DivAdquisicion').fadeOut();
            $('#DivAutoConstruccion').fadeOut();
            $('#Mejoramiento').prop('required',true);
            $('#Adquisicion').prop('required',false);
            $('#AutoConstruccion').prop('required',false);
        }else{
            $('#DivAdquisicion').fadeIn();
            $('#DivAutoConstruccion').fadeIn();
            $('#Mejoramiento').prop('required',true);
            $('#Adquisicion').prop('required',true);
            $('#AutoConstruccion').prop('required',true);
        }
    });
    
    function formato_fecha(fecha){

        var date  = new Date(fecha);
        var day   = date.getDate();
        var month = date.getMonth();
        var nuevo = date.getFullYear() + "-" + (month > 9 ? month : "0" + month) + "-" + (day > 9 ? day : "0" + day);

        return nuevo;

    }

    $(document).on('click','.CleanCurp',function(){
        $('input[name="curp"]').val("").prop('readonly',false);
        $('input[name="fecha_nac"]').val("");
        $('input[name="ape_p"]').val("").prop('readonly',false);
        $('input[name="ape_m"]').val("").prop('readonly',false);
        $('input[name="nombre"]').val("").prop('readonly',false);
        $('.Sbuscar-datos').prop('disabled',false);
        $('.Sbuscar-curp').prop('disabled',false);
    });

    $(document).on('click','.CleanCurpConyuge',function(){
        $('input[name="nombre_conyuge"]').val("").prop('readonly',false);
        $('input[name="ape_p_conyuge"]').val("").prop('readonly',false);
        $('input[name="ape_m_conyuge"]').val("").prop('readonly',false);
        $('input[name="curp_conyuge"]').val("").prop('readonly',false);
        $('input[name="fecha_nac_conyuge"]').val("");
        $('.Cbuscar-curp').prop('disabled',false);
    });

    $(document).on('click','.BtnGeneral',function(){
        var datos = {
            nombre:         $('input[name="nombre"]').val(),
            curp:           $('input[name="curp"]').val(),
            ape_paterno:    $('input[name="ape_p"]').val(),
            ape_materno:    $('input[name="ape_m"]').val(),
            estado_civil:   $('select[name="estado_civil"]').val(),
            fecha_nac:      $('input[name="fecha_nac"]').val(),
            correo:         $('input[name="correo"]').val(),
            telefono:       $('input[name="tel"]').val(),
            estado_nac:     $('input[name="estado_nac"]').val(),
            lugar_nac:      $('input[name="lugar_nac"]').val(),
            no_dependientes:$('input[name="dependientes"]').val(),
            ocupacion:      $('select[name="ocupacion"]').val(),
            escolaridad:    $('select[name="escolaridad"]').val(),
            tab:"GENERALES"
        };
        var Validacion = PeticionesAjax("/GetValidacionAtencionSolicitud","GET",datos);
        if (!Validacion) {return false;}
        $('a[href="#domicilio"]').tab('show');
    });

    $(document).on('click','.BtnGeneralConyuge',function(){
        var datos = {
            nombre:         $('input[name="nombre"]').val(),
            curp:           $('input[name="curp"]').val(),
            ape_paterno:    $('input[name="ape_p"]').val(),
            ape_materno:    $('input[name="ape_m"]').val(),
            estado_civil:   $('select[name="estado_civil"]').val(),
            fecha_nac:      $('input[name="fecha_nac"]').val(),
            correo:         $('input[name="correo"]').val(),
            telefono:       $('input[name="tel"]').val(),
            estado_nac:     $('input[name="estado_nac"]').val(),
            lugar_nac:      $('input[name="lugar_nac"]').val(),
            no_dependientes:$('input[name="dependientes"]').val(),
            ocupacion:      $('select[name="ocupacion"]').val(),
            escolaridad:    $('select[name="escolaridad"]').val(),
            tab:"GENERALES"
        };
        var Validacion = PeticionesAjax("/GetValidacionAtencionSolicitud","GET",datos);
        if (!Validacion) {return false;}
        $('a[href="#conyuge"]').tab('show');
    });

    $(document).on('click','.BtnDomicilio',function(){
        var datos = {
            estado:       $('#state').val(),
            municipio:    $('#town').val(),
            localidad:    $('#location').val(),
            colonia:      $('#colonie').val(),
            calle:        $('#street').val(),
            no_ext:       $('input[name="num_ext"]').val(),
            no_int:       $('input[name="num_int"]').val(),
            codigo_postal:$('input[name="codigo_postal"]').val(),
            referencia1:  $('input[name="referencia"]').val(),
            referencia2:  $('input[name="referencia2"]').val(),
            referencia3:  $('input[name="referencia3"]').val(),
            //ubicacion:    $('input[name="ubicacion"]').val(),
            tab:"DOMICILIO"
        };
        var Validacion = PeticionesAjax("/GetValidacionAtencionSolicitud","GET",datos);
        if (!Validacion) {return false;}
        $('a[href="#modalidad"]').tab('show');
    });

    $(document).on('click','.BtnConyuge',function(){
        var datos = {
            nombre:      $('input[name="nombre_conyuge"]').val(),
            curp:        $('input[name="curp_conyuge"]').val(),
            ape_paterno: $('input[name="ape_p_conyuge"]').val(),
            ape_materno: $('input[name="ape_m_conyuge"]').val(),
            fecha_nac:   $('input[name="fecha_nac_conyuge"]').val(),
            lugar_nac:   $('input[name="lugar_nac_conyuge"]').val(),
            bienes:      $('select[name="bienes"]').val(),
            tab:"CONYUGE"
        };
        var Validacion = PeticionesAjax("/GetValidacionAtencionSolicitud","GET",datos);
        if (!Validacion) {return false;}
        $('a[href="#domicilio"]').tab('show');
    });

    $(document).on('click','.BtnConyuge2',function(){
        $('a[href="#generales"]').tab('show');
    });

    function PeticionesAjax(url,tipo,datos = {},funcion = ""){
        var flag = true;
        $.ajax({
            url:url,
            type:tipo,
            dataType:"json",
            async:false,
            data:datos
        }).always(function(){
            $('#ImgCargando').modal('show');
        }).complete(function(){
            $('#ImgCargando').modal('hide');
        }).done(function(data){
            if(funcion != ""){
                funcion(data);
            }
        }).fail(function(error){
            $('.mensaje-error-ajax').empty();
            $.each(error.responseJSON,function(index, mensaje){
                $('.mensaje-error-ajax').append("<li style='color:red;font-weight:bold;'>"+mensaje[0]+"</li>");
            });
            $('#MensajeError').modal('show');
            flag = false;
        });

        return flag;
    }
});
