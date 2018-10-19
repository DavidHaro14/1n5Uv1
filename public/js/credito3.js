$(document).ready(function(){

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
                $('.SimpleSelect').trigger('chosen:updated');
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
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Localidad         -> #location
    *   Select Fraccionamiento   -> #fraccionamiento
    *   Desplegar Fraccionamientos Segun la Localidad Seleccionada
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
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Fraccionamiento   -> #colonie
    *   Select Manzana           -> #manzana
    *   Desplegar las manzanas Segun el Fraccionamiento Seleccionado
    */
    
    $('#colonie').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/LotesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#manzana').append('<option selected value=""> Cargando... </option>');
            //     $('#manzana').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#manzana').prop('disabled',false);
                    $('#manzana').empty();
                    $('#manzana').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#manzana').append('<option value="'+dato.colonia_id+'"> '+dato.no_manzana+' </option>');
                    });
                } else {
                    $('#manzana').empty();
                    $('#manzana').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Manzana   -> #manzana
    *   Select Lote      -> #lote
    *   Desplegar Lotes Segun la manzana Seleccionada
    */
    
    $('#manzana').change(function(){
        var id = $(this).val();
        var manzana = $('#manzana option:selected').text();
        $.ajax({
            url:"/Lotes2Get",
            type:"GET",
            dataType:"json",
            data:{'id':id, 'manzana':manzana},
            // beforeSend:function(){
            //     $('#lote').append('<option selected value=""> Cargando... </option>');
            //     $('#lote').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#lote').prop('disabled',false);
                    $('#lote').empty();
                    //$('#lote').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#lote').append('<option value="'+dato.id_lote+'"> '+dato.no_lote+' </option>');
                    });
                    
                    $('#otro_lote').val($('#lote').val());
                } else {
                    $('#lote').empty();
                    $('#lote').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /***************************
    * FORM SINO EXISTE EL LOTE *
    ****************************/

    /*
    *   Select Estado    -> #state
    *   Select Municipio -> #town
    *   Desplegar Municipios Segun el Estado Seleccionado
    */

    $('#state2').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/MunicipiosGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#town2').append('<option selected value=""> Cargando... </option>');
            //     $('#town2').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    $('#town2').prop('disabled',false);
                    $('#town2').empty();
                    $('#town2').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#town2').append('<option value="'+dato.id_municipio+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#town2').empty();
                    $('#town2').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Municipio -> #town2
    *   Select Localidad -> #location
    *   Desplegar Localidades Segun el Municipio Seleccionado
    */

    $('#town2').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/LocalidadesGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#location2').append('<option selected value=""> Cargando... </option>');
            //     $('#location2').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    $('#location2').prop('disabled',false);
                    $('#location2').empty();
                    $('#location2').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#location2').append('<option value="'+dato.id_localidad+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#location2').empty();
                    $('#location2').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
    *   Select Localidad         -> #location2
    *   Select Fraccionamiento   -> #fraccionamiento
    *   Desplegar Fraccionamientos Segun la Localidad Seleccionada
    */
    
    $('#location2').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/ColoniasGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#colonie2').append('<option selected value=""> Cargando... </option>');
            //     $('#colonie2').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    // $('#colonie2').prop('disabled',false);
                    $('#colonie2').empty();
                    $('#colonie2').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#colonie2').append('<option value="'+dato.id_colonia+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#colonie2').empty();
                    $('#colonie2').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    /*
        JQUERY CAMPOS CHANGE
     */

    $('#plazo, #monto_credito').change(function(){
    	pago_total();
        mensualidad();
    });

    $('#plazo, #monto_credito').keyup(function(){
        pago_total();
        mensualidad();
    });

    $('.btn-corrida').click(function(){
    	corrida();
    });

    $('#select-lote').change(function(){
        var id = $(this).val();
        //var route2  = "/LoteInfoGet/" + id;
        ViewLote(id);
    });

    $('#lote').change(function(){
        var valor = $(this).val();
        $('#otro_lote').val(valor);
    });

    $('.btn-AddLote').click(function(){
        $('.DivSelectLote').hide();
        $('#otro_lote').val("0");
        $('#state, #state2, #town2, #town, #location2, #location, #colonie2, #colonie, #lote, #manzana').val("");
        $('#form-lote').show();
    });

    $(document).on('click',function(){
        $('.li-credito').removeAttr('data-toogle data-placement title data-original-title').popover('destroy');
    });

    $('.btnSubmitLote').click(function(){
        var confirmacion = confirm("Estas seguro que los datos estan correctamente? Si continuas no existirán modificaciones mas adelante");
        if (confirmacion) {
            $.post('/SetRegularizacionLote',$('#form-lote').serialize()).done(function(data){
                if(data != ""){
                    $('#form-lote').hide();
                    $('.li-lote').hide().removeClass('active');
                    $('#panel-lote').removeClass('active');
                    $('.li-credito').addClass('active').attr({'data-toogle':'popover','data-placement':'bottom','title':'Nuevo Lote Registrado','data-content':'El lote se registro y se asigno correctamente a este credito, favor de continuar el proceso'}).popover('show');
                    $('#panel-credito').addClass('active');
                    $('#form-lote')[0].reset();
                    $('#otro_lote').val(data);
                    $('select#select-lote').prop('disabled',true);
                }
            }).fail(function(data){
                //console.log(data);
                $('.mensaje-error').empty();
                $.each(data.responseJSON,function(index,error){
                    $('.mensaje-error').append('<p style="color:red;"><b>'+error[0]+'</b></p>');
                });
                $('#msj_error').modal('show');
            });
        }
    });

    $('.btn-ViewLote').click(function(){
        var id = $('#lote').val();
        ViewLote(id,true);
    });

    function ViewLote(id, panel = false){
        if(id != '' && id != "0"){
            $.get('/LoteInfoGet/'+id).done(function(data){
                if (! data.noreste) data.noreste = "NO SE REGISTRO";
                if (! data.noroeste) data.noroeste = "NO SE REGISTRO";
                if (! data.sureste) data.sureste = "NO SE REGISTRO";
                if (! data.suroeste) data.suroeste = "NO SE REGISTRO";

                if (! data.norte) data.norte = "NO SE REGISTRO";
                if (! data.sur) data.sur = "NO SE REGISTRO";
                if (! data.este) data.este = "NO SE REGISTRO";
                if (! data.oeste) data.oeste = "NO SE REGISTRO";

                if (! data.ochavo) data.ochavo = "NO SE REGISTRO";

                if (! data.clave_catastral) data.clave_catastral = "NO SE REGISTRO";

                //$('.li-lote').hide();
                $(".tbody_lote").empty().append(
                    '<tr>'+
                        '</td><td colspan="6" class="text-center subtitle"><b>Lote</b></td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>No. Manzana: </strong></td><td>'+data.no_manzana+'</td>'+
                        '<td><strong>No. Lote: </strong></td><td>'+data.no_lote+'</td>'+
                        '<td><strong>Superficie: </strong></td><td>'+data.superficie+' M2.</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Clave Catastral: </strong></td><td>'+data.clave_catastral+'</td>'+
                        '<td><strong>Ochavo: </strong></td><td>'+data.ochavo+'</td>'+
                        '<td><strong>Uso de Suelo: </strong></td><td>'+data.uso_suelo+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Estatus: </strong></td><td>'+data.estatus+'</td>'+
                        '<td><strong>Domicilio: </strong></td><td colspan="3">'+data.calle+' #'+data.numero+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '</td><td colspan="6" class="text-center subtitle"><b>Colindancias</b></td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Norte: </strong></td><td colspan="5">'+data.norte+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Sur: </strong></td><td colspan="5">'+data.sur+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Este: </strong></td><td colspan="5">'+data.este+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Oeste: </strong></td><td colspan="5">'+data.oeste+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Noreste: </strong></td><td colspan="5">'+data.noreste+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Noroeste: </strong></td><td colspan="5">'+data.noroeste+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Sureste: </strong></td><td colspan="5">'+data.sureste+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td><strong>Suroeste: </strong></td><td colspan="5">'+data.suroeste+'</td>'+
                    '</tr>'
                );
            });

            if(panel){
                $('.li-lote').removeClass('active');
                $('#panel-lote').removeClass('active');
                $('.li-info').addClass('active');
                $('#panel-info').addClass('active');
            } else {
                $('.li-lote').hide();
            }

        } else {
            $('.tbody_lote').empty();
            $('.li-lote').show();
        }
    }

    /*
        FUNCIONES PARA CALCULAR LOS CAMPOS INGRESADOS
     */

    function mensualidad(){
        var plazo = $('#plazo').val();
        var pago_total = $('#pago_total').val();
        if (plazo > 0) {

        	var mensualidad = (pago_total / plazo);

            var oper = Math.round(mensualidad * 100) / 100;
        	//$('#mensualidad').val(mensualidad);

            if(isNaN(oper)){
                $('#mensualidad').val(0);
            } else { 
               $('#mensualidad').val(mensualidad);
               $('#format_mensualidad').val(formato_money(oper));
            }
        }
    }

    function pago_total(){
        var enganche     = $('#enganche').val();
        var m_credito    = $('#monto_credito').val();
        var total        = m_credito - enganche;

        var oper = Math.round(total * 100) / 100;

        if(isNaN(total)){
            $('#pago_total').val(0);
        } else { 
           $('#pago_total').val(total);
           $('#format_pago_total').val(formato_money(oper));
        }
    }

    function corrida(){
    	var plazo = $('#plazo').val();
    	var pago_mensual = $('#mensualidad').val();
        //var fecha = $('#fecha').val();
        var saldo = $('#pago_total').val();

    	$('#mostrar_corrida').empty();

        //if (plazo != "0" && pago_mensual != "0" && fecha != "" && saldo !="0") {

        	for (var mensualidad = 0; mensualidad < plazo ; mensualidad ++) {
                var v_pago_mensual = (Math.round(pago_mensual * 100) / 100);
                var v_saldo        = (Math.round((saldo - pago_mensual) * 100) / 100);
        		$('#mostrar_corrida').append(
        			"<tr>"+
        				"<td class='text-center'>" + (parseFloat(mensualidad) + 1) + "</td>" +
                        //"<td>"   + fecha_corrida(fecha,(mensualidad+1)) + "</td>" +
        				"<td class='text-center'> $" + formato_money(v_pago_mensual) + "</td>" +
                        "<td class='text-center'> $" + formato_money(v_saldo) + "</td>" +
        			"</tr>"
        			);
                saldo -= pago_mensual;
        	}

        /*} else {

            $('#mostrar_corrida').append("<tr><td class='text-center' colspan='4'>Completar los campos anteriores.</td></tr>");

        }*/

    }

    function formato_money(money){
        var v_money = money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
        return v_money;
    }

    function fecha_corrida(fecha,mes){

        var date  = new Date(fecha);
        var day   = date.getDate() + 1;
        var month = date.getMonth() + 1 + mes;
        var nuevo = (day > 9 ? day : "0" + day) + "/" + (month > 9 ? month : "0" + month) + "/" + date.getFullYear();

        return nuevo;

    }

});