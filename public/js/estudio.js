$(document).ready(function() {    
    
	  //
    // Si Cambia el valor en los Inputs (class => Ingresos || Gastos) realizar operacion de suma
    //
    $('.ingresos').change(function(){
      ingresos();
    });

    $('.gastos').change(function(){
	    gastos();
    });

    /*
    *   Funciones Ingresos y Gastos
    */

    function ingresos(){
      var solicitante1 = $('#solicitante1').val();
      var solicitante2 = $('#solicitante2').val();
      var conyuge1     = $('#conyuge1').val();
      var conyuge2     = $('#conyuge2').val();
      var ingrfamiliar = $('#ingrfamiliar').val();
      
      if(conyuge1 == null && conyuge2 == null)
      {
        conyuge1 = 0;
        conyuge2 = 0;
      }

      var TotalIngr = Number(solicitante1) + 
              Number(solicitante2) + 
              Number(conyuge1) + 
              Number(conyuge2) +
              Number(ingrfamiliar);

      $('#TotalIngresos').val(TotalIngr);
    }

    function gastos(){
      var alimentacion = $('#alimentacion').val();
      var luz          = $('#luz').val();
      var agua         = $('#agua').val();
      var educacion    = $('#educacion').val();
      var renta        = $('#renta').val();
      var transporte   = $('#transporte').val();
      var otros        = $('#otros').val();

      var TotalGst =  Number(alimentacion) + 
              Number(luz) + 
              Number(agua) + 
              Number(educacion) + 
              Number(renta) +
              Number(transporte) + 
              Number(otros);

      $('#TotalGastos').val(TotalGst);
    }

    // Agregar Familiares a la DB
    $('#btnAdd-familiar').click(function(){
      var nombre      = $('#nombre_familiar').val();
      var paterno     = $('#paterno_familiar').val();
      var materno     = $('#materno_familiar').val();
      var parentesco  = $('#parentesco_familiar').val();
      var genero      = $('#genero_familiar').val();
      var edad        = $('#edad_familiar').val();
      var ocupacion   = $('#ocupacion_familiar').val();
      var ingreso     = $('#ingreso_familiar').val();
      var token       = $('#token').val();
      var id          = $('#num').val();
      
      $.ajax({
        url : '/PostFamiliar',
        headers: {'X-CSRF-TOKEN': token},
        type : 'POST',
        dataType: 'json',
        data : {
            'nombre': nombre,
            'paterno': paterno, 
            'materno': materno, 
            'parentesco': parentesco,
            'genero': genero,
            'edad': edad,
            'ocupacion': ocupacion,
            'ingreso': ingreso,
            'id' : id
        },
        success:function(data){
            var ingrfamiliar = 0;
            $('.limpiar').val("");
            $('.limpiarNum').val("0");
            $('#mostrar-familiares').empty();
            $.each(data.familiares, function(index, data){
                $('#mostrar-familiares').append(
                  "<tr>"+
                    "<td>" + data.nombre + " " + data.ape_paterno + " " + data.ape_materno + "</td>"+
                    "<td>" + data.parentesco + "</td>"+
                    "<td>" + data.edad + "</td>"+
                    "<td>" + data.genero + "</td>"+
                    "<td>" + data.ocupacion + "</td>"+
                    "<td>" + data.ingresos + "</td>"+
                    "<td><button type='button' value='"+ data.id_familiar + "' class='btn btn-danger btn-sm btnquitar'><span class='icon icon-bin2'></span></button></td>"+
                  "</tr>");
                ingrfamiliar += data.ingresos;
            });
            $('#ingrfamiliar').val(ingrfamiliar);
            $('.msj-error').fadeOut();
            ingresos();
        },
        error:function(msj){
          $('#msj').empty();
          $.each(msj.responseJSON, function(index, msj){
            $.each(msj, function(index, msj2){
              $('#msj').append("<li>"+ msj2 +"</li>");
            });
          });
          $('.msj-error').fadeIn();
        }
      });
    });
    
    // Quitar Familiares a la DB
    $("#mostrar-familiares").on('click', '.btnquitar' ,function(){
      var dataId = $(this).val();
      var route  = "/DeleteFamiliar/" + dataId;
      var id     = $('#num').val();
      
      $.ajax({
        url: route,
        type: 'GET',
        dataType: 'json',
        data: {'cliente' : id},
        success:function(data){
          var ingrfamiliar = 0;
          $('#mostrar-familiares').empty();
          $.each(data.familiares, function(index, data){
               $('#mostrar-familiares').append(
                 "<tr>"+
                   "<td>" + data.nombre + " " + data.ape_paterno + " " + data.ape_materno + "</td>"+
                   "<td>" + data.parentesco + "</td>"+
                   "<td>" + data.edad + "</td>"+
                   "<td>" + data.genero + "</td>"+
                   "<td>" + data.ocupacion + "</td>"+
                   "<td>" + data.ingresos + "</td>"+
                   "<td><button type='button' value='"+ data.id_familiar + "' class='btn btn-danger btn-sm btnquitar'><span class='icon icon-bin2'></span></button></td>"+
                 "</tr>");
                  ingrfamiliar += data.ingresos;
          });
          $('#ingrfamiliar').val(ingrfamiliar);
          ingresos();
        }
      });

    });
    
    // Cerrar la ventana de Errores
    $('.close-alert').click(function(){
        $('.msj-error, .msj-error2').fadeOut();
    });

    // Agregar Referencias a la DB
    $('#btnAdd-referencia').click(function(){
      var nombre      = $('#nombre_referencia').val();
      var paterno     = $('#paterno_referencia').val();
      var materno     = $('#materno_referencia').val();
      var parentesco  = $('#parentesco_referencia').val();
      var genero      = $('#genero_referencia').val();
      var domicilio   = $('#domicilio_referencia').val();
      var telefono    = $('#telefono_referencia').val();
      var token       = $('#token_referencia').val();
      var id          = $('#num').val();
      
      $.ajax({
        url : '/PostReferencia',
        headers: {'X-CSRF-TOKEN': token},
        type : 'POST',
        dataType: 'json',
        data : {
            'nombre': nombre,
            'paterno': paterno, 
            'materno': materno, 
            'parentesco': parentesco,
            'genero': genero,
            'domicilio': domicilio,
            'telefono': telefono,
            'id' : id
        },
        success:function(data){
            $('.limpiar').val("");
            $('#mostrar-referencias').empty();
            $.each(data.referencias, function(index, data){
                $('#mostrar-referencias').append(
                  "<tr>"+
                    "<td>" + data.nombre + " " + data.ape_paterno + " " + data.ape_materno + "</td>"+
                    "<td>" + data.parentesco + "</td>"+
                    "<td>" + data.genero + "</td>"+
                    "<td>" + data.domicilio + "</td>"+
                    "<td>" + data.telefono + "</td>"+
                    "<td><button type='button' value='"+ data.id_referencia + "' class='btn btn-danger btn-sm btnquitar'><span class='icon icon-bin2'></span></button></td>"+
                  "</tr>");
            });
            $('.msj-error2').fadeOut();
        },
        error:function(msj){
          $('#msj2').empty();
          $.each(msj.responseJSON, function(index, msj){
            $.each(msj, function(index, msj2){
              $('#msj2').append("<li>"+ msj2 +"</li>");
            });
          });
          $('.msj-error2').fadeIn();
        }
      });
    });
    
    // Quitar Referencias a la DB
    $("#mostrar-referencias").on('click', '.btnquitar' ,function(){
      var dataId = $(this).val();
      var route  = "/DeleteReferencia/" + dataId;
      var id     = $('#num').val();
      
      $.ajax({
        url: route,
        type: 'GET',
        dataType: 'json',
        data: {'cliente' : id},
        success:function(data){
          $('#mostrar-referencias').empty();
          $.each(data.referencias, function(index, data){
               $('#mostrar-referencias').append(
                  "<tr>"+
                    "<td>" + data.nombre + " " + data.ape_paterno + " " + data.ape_materno + "</td>"+
                    "<td>" + data.parentesco + "</td>"+
                    "<td>" + data.genero + "</td>"+
                    "<td>" + data.domicilio + "</td>"+
                    "<td>" + data.telefono + "</td>"+
                    "<td><button type='button' value='"+ data.id_referencia + "' class='btn btn-danger btn-sm btnquitar'><span class='icon icon-bin2'></span></button></td>"+
                  "</tr>");
          });
        }
      });

    });
    
    //**************** Solicitante ******************//
   /*
    *   Select Estado    -> #state
    *   Select Municipio -> #town
    *   Desplegar Municipios Segun el Estado Seleccionado
    */

    $('#state').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/MunicipiosGet",
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
            url:"/LocalidadesGet",
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
            url:"/ColoniasGet",
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
            url:"/CallesGet",
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


    //**************** Conyuge ******************//

    /*
    *   Select Estado    -> #stateC
    *   Select Municipio -> #townC
    *   Desplegar Municipios Segun el Estado Seleccionado
    */

    $('#stateC').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/MunicipiosGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#townC').append('<option selected value=""> Cargando... </option>');
            //     $('#townC').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#townC').prop('disabled',false);
                    $('#townC').empty();
                    $('#townC').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#townC').append('<option value="'+dato.id_municipio+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#townC').empty();
                    $('#townC').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Municipio -> #townC
    *   Select Localidad -> #locationC
    *   Desplegar Localidades Segun el Municipio Seleccionado
    */

    $('#townC').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/LocalidadesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#locationC').append('<option selected value=""> Cargando... </option>');
            //     $('#locationC').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#locationC').prop('disabled',false);
                    $('#locationC').empty();
                    $('#locationC').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#locationC').append('<option value="'+dato.id_localidad+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#locationC').empty();
                    $('#locationC').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Localidad -> #locationC
    *   Select Colonia   -> #colonieC
    *   Desplegar Colonias Segun la Localidad Seleccionada
    */
    
    $('#locationC').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/ColoniasGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#colonieC').append('<option selected value=""> Cargando... </option>');
            //     $('#colonieC').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#colonieC').prop('disabled',false);
                    $('#colonieC').empty();
                    $('#colonieC').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#colonieC').append('<option value="'+dato.id_colonia+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#colonieC').empty();
                    $('#colonieC').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Colonia   -> #colonieC
    *   Select Calle     -> #streetC
    *   Desplegar Calles Segun la Colonia Seleccionada
    */
    
    $('#colonieC').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/CallesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#streetC').append('<option selected value=""> Cargando... </option>');
            //     $('#streetC').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#streetC').prop('disabled',false);
                    $('#streetC').empty();
                    $('#streetC').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#streetC').append('<option value="'+dato.id_calle+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#streetC').empty();
                    $('#streetC').append('<option selected value=""> No se encontro... </option>');
                }
                $(".SimpleSelect").trigger('chosen:updated');
            }
        });
    });

    //Si tiene servicio de Salud activar el campo  NO. seguro social (Solicitante)
    $('#ServicioSalud').change(function(){
        var servicio = $('#ServicioSalud').val();
        if(servicio != "NO TIENE" && servicio != ""){
          $('#DivSeguro').show();
          $('#Seguro').prop('required',true);
        }else{
          $('#DivSeguro').hide();
          $('#Seguro').prop('required',false).val("");
        }
    });

    //Si tiene servicio de Salud activar el campo  NO. seguro social (Conyuge)
    $('#ServicioSaludConyuge').change(function(){
        var servicio = $('#ServicioSaludConyuge').val();
        if(servicio != "NO TIENE"){
          $('#DivSeguroConyuge').fadeIn();
          $('#SeguroConyuge').prop('required',true);
        }else{
          $('#DivSeguroConyuge').fadeOut();
          $('#SeguroConyuge').prop('required',false);
          $('#SeguroConyuge').val("");
        }
    });

    //Si elige Propia, activar el campo Escrituracion
    $('#Vivienda').change(function(){
        var servicio = $('#Vivienda').val();
        if(servicio != "PROPIA"){
          $('#DivEscrituracion').fadeOut();
          $('#CheckBoxEscrituracion').attr('checked', false);
        }else{
          $('#DivEscrituracion').fadeIn();
        }
    });

    // Validacion btn Continuar/Regresar

    $(document).on('click','#BtnSolicitante',function(){
        var servicio_salud = $('#ServicioSalud').val();
        var seguro_social  = $('#Seguro').val();
        if ((servicio_salud != "NO TIENE" && servicio_salud != '') && seguro_social == '') {
          alert("Complete el campo No. Seguro Social");
          return false;
        }
        var datos = {
            nombre_empresa:   $('input[name="empresa"]').val(),
            telefono_empresa: $('input[name="tel_empresa"]').val(),
            antiguedad:       $('input[name="antiguedad"]').val(),
            estado:           $('#state').val(),
            municipio:        $('#town').val(),
            localidad:        $('#location').val(),
            colonia:          $('#colonie').val(),
            calle:            $('#street').val(),
            no_ext:           $('input[name="num_ext"]').val(),
            no_int:           $('input[name="num_int"]').val(),
            servicio_salud:   $('select[name="servicio_salud"]').val(),
            servicio_vivienda:$('select[name="servicio_vivienda"]').val(),
            tab:"SOLICITANTE"
        };
        var Validacion = PeticionesAjax("/GetValidacionEstudioSocioeconomico","GET",datos);
        if (!Validacion) {return false;}
        var conyuge = $('input[name="cony"]').val();
        if (conyuge != "0") {
          $('a[href="#conyuge"]').tab('show');
        } else {
          $('a[href="#familiares"]').tab('show');
        }
    });

    $(document).on('click','#BtnConyuge',function(){
        var servicio_salud = $('#ServicioSaludConyuge').val();
        var seguro_social  = $('#SeguroConyuge').val();
        if ((servicio_salud != "NO TIENE" && servicio_salud != '') && seguro_social == '') {
          alert("Complete el campo No. Seguro Social (Conyuge)");
          return false;
        }
        var datos = {
            ocupacion:        $('select[name="ocupacion_conyuge"]').val(),
            nombre_empresa:   $('input[name="empresa_conyuge"]').val(),
            telefono_empresa: $('input[name="tel_empresa_conyuge"]').val(),
            antiguedad:       $('input[name="antiguedad_conyuge"]').val(),
            estado:           $('#stateC').val(),
            municipio:        $('#townC').val(),
            localidad:        $('#locationC').val(),
            colonia:          $('#colonieC').val(),
            calle:            $('#streetC').val(),
            no_ext:           $('input[name="num_ext_conyuge"]').val(),
            no_int:           $('input[name="num_int_conyuge"]').val(),
            servicio_salud:   $('select[name="servicio_salud_conyuge"]').val(),
            servicio_vivienda:$('select[name="servicio_vivienda_conyuge"]').val(),
            tab:"CONYUGE"
        };
        var Validacion = PeticionesAjax("/GetValidacionEstudioSocioeconomico","GET",datos);
        if (!Validacion) {return false;}
        $('a[href="#familiares"]').tab('show');
    });

    $(document).on('click','#BtnFamiliares',function(){
        $('a[href="#IngrGst"]').tab('show');
    });

    $(document).on('click','#BtnIngresosGastos',function(){
      var vacio = 0;
      $.each($('.gastos'),function(){
        if($(this).val() == ''){
          vacio++;
        }
      });

      $.each($('.ingresos'),function(){
        if($(this).val() == ''){
          vacio++;
        }
      });

      if(vacio != 0){
        alert("Complete todos los campos con una cantidad mayor o igual a cero.");
        return false;
      }

      $('a[href="#referencias"]').tab('show');
    });

    $(document).on('click','#BtnReferencias',function(){
        $('a[href="#vivienda"]').tab('show');
    });

    $(document).on('click','#BtnBackReferencias',function(){
        $('a[href="#referencias"]').tab('show');
    });

    $(document).on('click','#BtnBackIngresosGastos',function(){
        $('a[href="#IngrGst"]').tab('show');
    });

    $(document).on('click','#BtnBackFamiliares',function(){
        $('a[href="#familiares"]').tab('show');
    });

    $(document).on('click','#BtnBackConyuge',function(){
        $('a[href="#conyuge"]').tab('show');
    });

    $(document).on('click','#BtnBackSolicitante',function(){
        $('a[href="#solicitante"]').tab('show');
    });

    function PeticionesAjax(url,tipo,datos = {}){
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
            if(data != "OK"){
              flag = false;
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
