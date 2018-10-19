$(document).ready(function(){

    /* Visualizar informacion del credito */
    /*$('#creditos').change(function(){
        var clave = $(this).val();
        $.ajax({
            url: "/CreditosGet",
            type: "GET",
            dataType: "json",
            data:{
                clave:clave
            },
            success:function(data){
                //alert("ya")
                // console.log(data.demanda);
                $('.tbody_credito').empty().append(
                    "<tr>"+
                        "<td><b>Saldo a pagar:</b></td>"+
                        "<td>$"+formato_money(data.total_pagar)+"</td>"+
                        "<td><b>Pago mensual:</b></td>"+
                        "<td>$"+formato_money(data.pago_mensual)+"</td>"+
                        "<td><b>Total abonado:</b></td>"+
                        "<td>$"+formato_money(data.total_abonado)+"</td>"+
                    "</tr>"+
                    "<tr>"+
                        "<td><b>Plazo:</b></td>"+
                        "<td>"+data.plazo+" Meses</td>"+
                        "<td><b>Fecha inicio:</b></td>"+
                        "<td>"+formato_fecha(data.fecha_inicio)+"</td>"+
                        "<td><b>Enganche:</b></td>"+
                        "<td>$"+formato_money(data.enganche)+"</td>"+
                    "</tr>"+
                    "<tr>"+
                        "<td colspan='6' class='text-center subtitle'><b>Demanda del crédito</b></td>"+
                    "</tr>"+
                    "<tr>"+
                        "<td><b>Programa:</b></td>"+
                        "<td>"+data.demanda.tipo_programa.programa.nombre+"</td>"+
                        "<td><b>Tipo de programa:</b></td>"+
                        "<td>"+data.demanda.tipo_programa.nombre+"</td>"+
                        "<td><b>Modulo:</b></td>"+
                        "<td>"+String(data.demanda.modulo).substring(5)+"</td>"+
                    "</tr>"
                );
                if(data.plantilla == "1"){
                    $('.tbody_credito').append(
                        "<tr>"+
                            "<td colspan='6' class='text-center subtitle'><b>Lote del crédito</b></td>"+
                        "</tr>"+
                        "<tr>"+
                            "<td><b>Manzana:</b></td>"+
                            "<td>"+data.lote.no_manzana+"</td>"+
                            "<td><b>Lote:</b></td>"+
                            "<td>"+data.lote.no_lote+"</td>"+
                            "<td><b>Clave Catastral:</b></td>"+
                            "<td>"+data.lote.clave_catastral+"</td>"+
                        "</tr>"+
                        "<tr>"+
                            "<td><b>Domicilio:</b></td>"+
                            "<td>"+data.lote.calle+" #"+data.lote.numero+"</td>"+
                            "<td><b>Fraccionamiento:</b></td>"+
                            "<td>"+data.lote.fraccionamiento.nombre+"</td>"+
                            "<td><b>Superficie:</b></td>"+
                            "<td>"+data.lote.superficie+" M2</td>"+
                        "</tr>"
                    );
                }
            }

        });
    });*/

    /*
        JQUERY CAMPOS CHANGE
     */
    total_reestructura();
    costo_contado();

    $('#plazo, #tasa').change(function(){
    	mensualidad();
    	pago_total();
    	costo_financiamiento();
    });

    $('.btn-corrida').click(function(){
    	corrida();
    });

    $('#fi_desc, #ca_desc, #mo_desc').change(function(){
        total_reestructura();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#plazo, #tasa').keyup(function(){
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#fi_desc, #ca_desc, #mo_desc').keyup(function(){
        total_reestructura();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    /*$('#ca_desc').change(function(){
        //var descuento = $(this).val();
        //var capital   = $('#h_ca_venc').val();
        //var oper = descuentos(descuento,capital);
        //$('#ca_venc').val(oper);
        total_reestructura();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#fi_desc').change(function(){
        //var descuento = $(this).val();
        //var financi   = $('#h_fi_venc').val();
        //var oper = descuentos(descuento,financi);
        //$('#fi_venc').val(oper);
        total_reestructura();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#mo_desc').change(function(){
        //var descuento = $(this).val();
        //var moratorios = $('#mo').val();
        //var oper = descuentos(descuento,moratorios);
        //$('#mo_venc').val(oper);
        total_reestructura();
        costo_contado();
        mensualidad();
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

    /*
        FUNCIONES PARA CALCULAR LOS CAMPOS INGRESADOS
     */

    /*
        TABLA 4 y 5
    */

    function corrida(){
        var tasa = ($('#tasa').val() / 100)/12;
        //var plazo = $('#plazo').val() * 12;
        // var inicio = $('.f_inicio').val();
        var plazo = $('#plazo').val();
        var pago_mensual = $('#mensualidad').val();
        //console.log(tasa);
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
            if(tabla > 4){
                v_contado      = (Math.round(saldo * 100) / 100);
            }
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
            // if(tabla < 5){
            //     saldo -= pago_mensual;
            // }
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

    // function descuentos(descuento,campo){
    //     //if(descuento > 1){descuento = 1;}
    //     return Math.round((campo - descuento) * 100) / 100;
    // }

    function total_reestructura(){
        var capital = $('#ca_venc').val();
        var financi = $('#fi_venc').val();
        var morator = $('#mo_venc').val();

        var ca_desc = $('#ca_desc').val();
        var fi_desc = $('#fi_desc').val();
        var mo_desc = $('#mo_desc').val();

        capital = parseFloat(capital) - parseFloat(ca_desc);
        financi = parseFloat(financi) - parseFloat(fi_desc);
        morator = parseFloat(morator) - parseFloat(mo_desc);

        //var interes = $('#int_venc').val();

        var total = parseFloat(capital) + parseFloat(financi) + parseFloat(morator);// - parseFloat(interes);
        var oper  = Math.round(total * 100)/100;

        $('#to_rees').val(oper);
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
        } else { 
           $('#pago_total').val(oper);
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