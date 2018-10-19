$(document).ready(function(){

    $('#programa').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/TiposProgramasGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            //     $('#documentos').empty().hide();
            //     $('#TiposProgramas').append('<option selected value=""> Cargando... </option>');
            //     $('#TiposProgramas').prop('disabled',true);
            // },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    //$('#TiposProgramas').prop('disabled',false);
                    $('#TiposProgramas').empty();
                    $('#TiposProgramas').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#TiposProgramas').append('<option value="'+dato.id_tipo_programa+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#TiposProgramas').empty();
                    $('#TiposProgramas').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });
    
    $('#cantidad').keyup(function(){
        CamposFechas();
    });

    $('#cantidad').change(function(){
        CamposFechas();
    });

    function CamposFechas(){
        var cantidad = $('#cantidad').val();
        var form = '';
        for (var i = 0; i < cantidad; i++) {
            form += '<div class="col-md-3 form-group">'+
                        '<label>Fecha '+ (i+1) +' <span class="icon icon-pencil ColorRequired"></span></label>'+
                        '<input type="text" class="form-control" placeholder="DD-MM-AAAA" name="fechas[]" required/>'+
                    '</div>';
        }

        if(cantidad === "0" || cantidad === ""){
            form = '<div class="col-md-12">'+
                        '<p>No hay reestructuras</p>'+
                    '</div>';
        }

        $('.FechasSaiv').empty().append(form);
    }

    /*$('#int_venc').keyup(function(){
        total_reestructura();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });*/

    $('#ca_venc').keyup(function(){
        $('#h_ca_venc').val($(this).val());
        $('#ca_desc').val(0);
        total_reestructura();
        costo_contado();
    });

    $('#fi_venc').keyup(function(){
        $('#h_fi_venc').val($(this).val());
        $('#fi_desc').val(0);
        total_reestructura();
        costo_contado();
    });

    $('#mo_venc').keyup(function(){
        $('#h_mo_venc').val($(this).val());
        $('#mo_desc').val(0);
        total_reestructura();
        costo_contado();
    });

    $('#ca_desc, #fi_desc, #mo_desc').keyup(function(){
        total_reestructura();
        costo_contado();
    });

    /*$('#ca_desc').keyup(function(){
        var descuento = $(this).val()/100;
        var capital   = $('#h_ca_venc').val();
        var oper = descuentos(descuento,capital);
        $('#ca_venc').val(oper);
        total_reestructura();
        costo_contado();
    });

    $('#fi_desc').keyup(function(){
        var descuento = $(this).val()/100;
        var financi   = $('#h_fi_venc').val();
        var oper = descuentos(descuento,financi);
        $('#fi_venc').val(oper);
        total_reestructura();
        costo_contado();
    });

    $('#mo_desc').keyup(function(){
        var descuento = $(this).val() / 100;
        var moratorios = $('#h_mo_venc').val();
        var oper = descuentos(descuento,moratorios);
        console.log(moratorios);
        $('#mo_venc').val(oper);
        total_reestructura();
        costo_contado();
    });*/

    $('#tasa,#plazo').keyup(function(){
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    /*$('#mensualidad').keyup(function(){
        generar_plazos();
        pago_total();
        costo_financiamiento();
    });*/

    $('#t_cobros').change(function(){
        var tabla = $('#t_cobros').val();
        if (tabla > 4) {
            $('#tasa').prop('readonly',true).val(0);
            $('#financiamiento').val(0);
            mensualidad();
            pago_total();
        } else {
            $('#tasa').prop('readonly',false);
            mensualidad();
            pago_total();
        }
    });

    $('.btn-corrida').click(function(){
        corrida();
    });

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
                    // $('#town').prop('disabled',false);
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
                    // $('#location').prop('disabled',false);
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
    *   Select Fraccionamiento   -> #colonie
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
                    // $('#colonie').prop('disabled',false);
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
                    // $('#manzana').prop('disabled',false);
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
                    // $('#lote').prop('disabled',false);
                    $('#lote').empty();
                    $('#lote').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#lote').append('<option value="'+dato.id_lote+'"> '+dato.no_lote+' </option>');
                    });
                } else {
                    $('#lote').empty();
                    $('#lote').append('<option selected value=""> No se encontro... </option>');
                }
                $('.SimpleSelect').trigger('chosen:updated');
            }
        });
    });

    $('#lote').change(function(){
        var id = $(this).val();
        if(id != '' && id != "0"){
            $('.btn-viewlote').prop('disabled',false);
            $.get('/LoteInfoGet/'+id).done(function(data){
                $('#superficie').val(data.superficie);
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
                $("#tbody_lote").empty().append(
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

        } else {
            $('.btn-viewlote').prop('disabled',true);
        }
    });

    /*
        FUNCIONES PARA CALCULAR LOS CAMPOS INGRESADOS

        TABLA 4 y 5
    */

    function corrida(){
        var tasa = ($('#tasa').val() / 100)/12;
        var plazo = $('#plazo').val();
        var pago_mensual = $('#mensualidad').val();
        var contado = $('#costo_contado').val();
        var saldo = $('#pago_total').val();

        var intereses = tasa * contado;
        var capital = pago_mensual - intereses;

        var tabla = $('#t_cobros').val();
        var display = "table-cell";

        if(tabla > 4){
            display = "none";
        }

        $('#mostrar_corrida').empty();
        var corrida = "";
        corrida +=
        '<table class="table table-hover">'+
            '<thead>'+
                '<th class="text-center">Mensualidad</th>'+
                '<th>Contado</th>'+
                '<th style="display:'+display+';">Capital</th>'+
                '<th style="display:'+display+';">Interés</th>'+
                '<th>Pago Mensual</th>'+
                '<th>Saldo</th>'+
            '</thead>'+
            '<tbody>';
        // console.log(inicio);

        for (var mensualidad = 0; mensualidad < plazo ; mensualidad ++) {
            var v_contado      = (Math.round(contado * 100) / 100);
            var v_capital      = (Math.round(capital * 100) / 100);
            var v_intereses    = (Math.round(intereses * 100) / 100);
            var v_pago_mensual = (Math.round(pago_mensual * 100) / 100);
            var v_saldo        = (Math.round((saldo - pago_mensual) * 100) / 100);
            corrida +=
                "<tr>"+
                    "<td class='text-center'>" + (parseFloat(mensualidad) + 1) + "</td>" +
                    // "<td>" + (fecha_vencimiento(inicio,(parseFloat(mensualidad) + 1) * 30)) + "</td>" +
                    "<td> $" + formato_money(v_contado) + "</td>" +
                    "<td style='display:"+display+";'> $" + formato_money(v_capital) + "</td>" +
                    "<td style='display:"+display+";'> $" + formato_money(v_intereses) + "</td>" +
                    "<td> $" + formato_money(v_pago_mensual) + "</td>" +
                    "<td> $" + formato_money(v_saldo) + "</td>" +
                "</tr>";
            contado -= capital;
            intereses = tasa * contado;
            capital = pago_mensual - intereses;
            saldo -= pago_mensual;
        }
        corrida +=
                '</tbody>' +
            '</table>';
        $('#mostrar_corrida').append(corrida);
    }

    function mensualidad(){
        var tabla = $('#t_cobros').val();
        var plazo = $('#plazo').val();
        var costo_contado = $('#costo_contado').val();
        var mensualidad = 0;

        if(tabla > 4){
            mensualidad = costo_contado / plazo;
        } else {
            var interes = ($('#tasa').val() / 100)/12;      
            mensualidad = (interes * costo_contado) / (1 - Math.pow(1 + parseFloat(interes), -plazo) );
        }

        var oper = Math.round(mensualidad * 100) / 100;//redondear a 2 decimales

        if(isNaN(oper)){
            $('#mensualidad').val(0);
        } else { 
           $('#mensualidad').val(oper);
        }
    }

    function costo_financiamiento(){
        var tabla = $('#t_cobros').val();
        if(tabla < 5){
            var costo_contado = $('#costo_contado').val();
            var total_pagar = $('#pago_total').val();
            var financiamiento = total_pagar - costo_contado;

            var oper = Math.round(financiamiento * 100) / 100;

            if(isNaN(oper)){
                $('#financiamiento').val(0);
            } else { 
               $('#financiamiento').val(oper);
            }
        }
    }

    /*function descuentos(descuento,campo){
        if(descuento > 1){descuento = 1;}
        return Math.round((campo - (descuento * campo)) * 100) / 100;
    }*/

    function total_reestructura(){
        var capital = $('#ca_venc').val();
        var financi = $('#fi_venc').val();
        var morator = $('#mo_venc').val();

        var desc_capital = $('#ca_desc').val();
        var desc_financi = $('#fi_desc').val();
        var desc_morator = $('#mo_desc').val();

        capital = parseFloat(capital) - parseFloat(desc_capital);
        financi = parseFloat(financi) - parseFloat(desc_financi);
        morator = parseFloat(morator) - parseFloat(desc_morator);
        //var interes = $('#int_venc').val();
        //var enganche = $('#enganche').val();

        var total = parseFloat(capital) + parseFloat(financi) + parseFloat(morator) /*- parseFloat(interes) /*- parseFloat(enganche)*/;
        var oper  = Math.round(total * 100)/100;

        if(isNaN(oper)){
            $('#to_rees').val(0);
        } else { 
            $('#to_rees').val(oper);
        }
    }

    function costo_contado(){
        var contado = $('#to_rees').val();
        $('#costo_contado').val(contado);
    }

    function pago_total(){
        var plazo = $('#plazo').val();
        var mensualidad = $('#mensualidad').val();
        var total = plazo * mensualidad;

        var oper = Math.round(total * 100) / 100;
        //$('#pago_total').val(total);

        if(isNaN(oper)){
            $('#pago_total').val(0);
            $('#h_total_pagar').val(0);
        } else { 
            $('#pago_total').val(oper);
            $('#h_total_pagar').val(oper);
        }
    }

    /*
        Formatos
    */
    function formato_money(money){
        var v_money = money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
        return v_money;
    }

    function formato_fecha(fecha){

        var date  = new Date(fecha);
        var day   = date.getDate() + 1;
        var month = date.getMonth() + 1;
        var nuevo = (day > 9 ? day : "0" + day) + "/" + (month > 9 ? month : "0" + month) + "/" + date.getFullYear();

        return nuevo;
    }

});