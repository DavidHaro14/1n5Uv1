$(document).ready(function(){
    /********************
    *     SUBSIDIOS     *
    *********************/
    $('#sub_fed').change(function(){
    	var id = $(this).val();
    	if(id != ""){
            var numero = $('#txtfed').val();
            validacion_subsidio("FEDERAL",numero,id,"#txtfed");
    		$('#txtfed').prop('required',true).prop('readonly',false);
			var route  = "/SubsidiosGet/" + id;
		      $.ajax({
		        url: route,
		        type: 'GET',
		        dataType: 'json',
		        success:function(data){
		            $('#input_federal').val(data.valor);
		            costo_contado();
		            mensualidad();
		            pago_total();
		            costo_financiamiento();
		        }
		      });
    	} else {
		    $('#input_federal').val("0");
		    costo_contado();
		    mensualidad();
		    pago_total();
		    costo_financiamiento();
    		$('#txtfed').prop('required',false).val("").prop('readonly',true);
	  	}
    });

    $('#sub_est').change(function(){
    	var id = $(this).val();
    	if(id != ""){
            var numero = $('#txtest').val();
            validacion_subsidio("ESTATAL",numero,id,"#txtest");
    		$('#txtest').prop('required',true).prop('readonly',false);
			var route  = "/SubsidiosGet/" + id;
		      $.ajax({
		        url: route,
		        type: 'GET',
		        dataType: 'json',
		        success:function(data){
		            $('#input_estatal').val(data.valor);
		            costo_contado();
		            mensualidad();
		            pago_total();
		            costo_financiamiento();
		        }
		      });
	    } else {
	    	$('#input_estatal').val("0");
	    	costo_contado();
	    	mensualidad();
	    	pago_total();
	    	costo_financiamiento();
    		$('#txtest').prop('required',false).val("").prop('readonly',true);
	  	}
    });

    $('#sub_mun').change(function(){
        var id = $(this).val();
        if(id != ""){
            var numero = $('#txtmun').val();
            validacion_subsidio("MUNICIPAL",numero,id,"#txtmun");
            $('#txtmun').prop('required',true).prop('readonly',false);
            var route  = "/SubsidiosGet/" + id;
              $.ajax({
                url: route,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    $('#input_municipal').val(data.valor);
                    costo_contado();
                    mensualidad();
                    pago_total();
                    costo_financiamiento();
                }
              });
        } else {
            $('#input_municipal').val("0");
            costo_contado();
            mensualidad();
            pago_total();
            costo_financiamiento();
            $('#txtmun').prop('required',false).val("").prop('readonly',true);
        }
    });

    $('#sub_otro').change(function(){
        var id = $(this).val();
        if(id != ""){
            var numero = $('#txtotro').val();
            validacion_subsidio("OTROS",numero,id,"#txtotro");
            $('#txtotro').prop('required',true).prop('readonly',false);
            var route  = "/SubsidiosGet/" + id;
              $.ajax({
                url: route,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    $('#input_otros').val(data.valor);
                    costo_contado();
                    mensualidad();
                    pago_total();
                    costo_financiamiento();
                }
              });
        } else {
            $('#input_otros').val("0");
            costo_contado();
            mensualidad();
            pago_total();
            costo_financiamiento();
            $('#txtotro').prop('required',false).val("").prop('readonly',true);
        }
    });

    $('#txtfed,#txtotro,#txtmun,#txtest').change(function(){
        var numero = $(this).val();
        var campo  = "#" + $(this).attr('id');

        switch(campo){
            case '#txtfed':
                var tipo   = "FEDERAL";
                var id_sub = $('#sub_fed').val();
                break;
            case '#txtmun':
                var tipo   = "MUNICIPAL";
                var id_sub = $('#sub_mun').val();
                break;
            case '#txtotro':
                var tipo   = "OTROS";
                var id_sub = $('#sub_otro').val();
                break;
            case '#txtest':
                var tipo   = "ESTATAL";
                var id_sub = $('#sub_est').val();
                break;
        }
        
        validacion_subsidio(tipo,numero,id_sub,campo);
    });

    function validacion_subsidio(tipo,numero,id,campo){
        $.ajax({
            url: "/SubsidiosValidacion",
            type: 'GET',
            dataType: 'json',
            data: {
                tipo:tipo,
                numero:numero,
                id_sub:id
            },
            success:function(data){
                if(data > 0){
                    alert("YA EXISTE ESTE NUMERO DE SUBSIDIO");
                    $(campo).val("");
                }
            }
        });
    }

    //Obtener la superficie del lote seleccionado
    $('#lote').change(function(){
    	var id = $(this).val();
		var route  = "/SuperficieGet/" + id;
	      $.ajax({
	        url: route,
	        type: 'GET',
	        dataType: 'json',
	        success:function(data){
                $('#superficie').val(data.superficie);
	            $('#format_superficie').val(formato_money(data.superficie));

	            costo_terreno();
		    	valor();
		    	costo_contado();
		    	mensualidad();
		    	pago_total();
		    	costo_financiamiento();
	        },
	        error:function(){
	          	$('#superficie').val("0");

	          	costo_terreno();
		    	valor();
		    	costo_contado();
		    	mensualidad();
                pago_total();
                costo_financiamiento();
            }
          });

          var route2  = "/LoteInfoGet/" + id;
          $.ajax({
            url: route2,
            type: 'GET',
            dataType: 'json',
            success:function(data){
                if (!data.noreste) data.noreste = "NO SE REGISTRO";
                if (!data.noroeste) data.noroeste = "NO SE REGISTRO";
                if (!data.sureste) data.sureste = "NO SE REGISTRO";
                if (!data.suroeste) data.suroeste = "NO SE REGISTRO";

                if (!data.norte) data.norte = "NO SE REGISTRO";
                if (!data.sur) data.sur = "NO SE REGISTRO";
                if (!data.este) data.este = "NO SE REGISTRO";
                if (!data.oeste) data.oeste = "NO SE REGISTRO";

                $(".tbody_lote").empty().append(
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
            },
            error:function(){
                $('.tbody_lote').empty();
            }
          });
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

    /*
        JQUERY CAMPOS CHANGE
     */
    $('#costo_cuadrado').keyup(function(){
    	costo_terreno();
    	valor();
    	costo_contado();
    	mensualidad();
    	pago_total();
    	costo_financiamiento();
    });

    $('#costo_construccion').keyup(function(){
    	valor();
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

    $('#costo_cuadrado').change(function(){
        costo_terreno();
        valor();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#costo_construccion').change(function(){
        valor();
        costo_contado();
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('#plazo, #tasa').change(function(){
        mensualidad();
        pago_total();
        costo_financiamiento();
    });

    $('.btn-corrida').click(function(){
    	corrida();
    });
    
    //Nuevo Tabla 5
    $('#t_cobros').change(function(){
        var tabla = $('#t_cobros').val();
        if (tabla > 4) {
            $('#tasa').prop('readonly',true).val(0);
            $('#financiamiento').val(0);
            $('#format_financiamiento').val(0);
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
    function valor(){
    	var costo_terreno = $('#costo_terreno').val();
    	var costo_construccion = $('#costo_construccion').val();
    	var valor_solucion = parseFloat(costo_terreno) + parseFloat(costo_construccion);

    	//$('#valor_solucion').val(valor_solucion);
        $('#valor_solucion').val(valor_solucion);
    	$('#format_valor_solucion').val(formato_money(Math.round(valor_solucion * 100) / 100));
    }

    function costo_terreno(){
    	var metro_cuadrado = $('#costo_cuadrado').val();
    	var superficie = $('#superficie').val();
    	var costo_terreno = superficie * metro_cuadrado;

    	//$('#costo_terreno').val(costo_terreno);
        $('#costo_terreno').val(costo_terreno);
    	$('#format_costo_terreno').val(formato_money(Math.round(costo_terreno * 100) / 100));
    }

    function costo_contado(){
    	var valor_solucion = $('#valor_solucion').val();
    	var enganche       = $('#enganche').val();
    	var federal        = $('#input_federal').val();
    	var estatal        = $('#input_estatal').val();
        var municipal      = $('#input_municipal').val();
        var otros          = $('#input_otros').val();
    	var contado        = valor_solucion - enganche - federal - estatal - municipal - otros;

    	//$('#costo_contado').val(contado);
    	//$('#costo_contado').val(Math.round(contado * 100) / 100);
        if (contado > 0) {
            $('#format_costo_contado').val(formato_money(Math.round(contado * 100) / 100)).removeAttr('data-toogle data-placement title data-original-title').popover('destroy');
            $('#costo_contado').val(contado);
            $('.btn-success').show();
        } else {
            //$('#costo_contado').val(contado).attr('data-toogle','tooltip');
            $('#format_costo_contado').val(formato_money(Math.round(contado * 100) / 100)).attr({'data-toogle':'popover','data-placement':'bottom','title':'Error','data-content':'El costo de contado no debe ser menor a cero.'}).popover('show');
            $('#costo_contado').val(contado);
            $('.btn-success').hide();
        }
    }

    function mensualidad(){
        var tabla = $('#t_cobros').val();
    	//var interes = ($('#tasa').val() / 100)/12;
    	var plazo = $('#plazo').val();
    	var costo_contado = $('#costo_contado').val();
        var mensualidad = 0;

        if(tabla > 4){
            mensualidad = costo_contado / plazo;
        } else {
            var interes = ($('#tasa').val() / 100)/12;      
            mensualidad = (interes * costo_contado) / (1 - Math.pow(1 + parseFloat(interes), -plazo) );
        }

    	//var mensualidad = (interes * costo_contado) / (1 - Math.pow(1 + parseFloat(interes), -plazo) );

        var oper = Math.round(mensualidad * 100) / 100;
    	//$('#mensualidad').val(mensualidad);

        if(isNaN(oper)){
            $('#mensualidad').val(0);
        } else { 
           $('#mensualidad').val(mensualidad);
           $('#format_mensualidad').val(formato_money(oper));
        }
    }

    function pago_total(){
    	//var plazo = $('#plazo').val() * 12;
    	var plazo = $('#plazo').val();
    	var mensualidad = $('#mensualidad').val();
    	var total = plazo * mensualidad;

        var oper = Math.round(total * 100) / 100;
    	//$('#pago_total').val(total);

        if(isNaN(oper)){
            $('#pago_total').val(0);
        } else { 
           $('#pago_total').val(total);
           $('#format_pago_total').val(formato_money(oper));
        }
    }

    function costo_financiamiento(){
        var tabla = $('#t_cobros').val();
        if(tabla < 5){
        	var costo_contado = $('#costo_contado').val();
        	var total_pagar = $('#pago_total').val();
        	var financiamiento = total_pagar - costo_contado;

            var oper = Math.round(financiamiento * 100) / 100;

        	//$('#financiamiento').val(financiamiento);

            if(isNaN(oper)){
                $('#financiamiento').val(0);
            } else { 
               $('#financiamiento').val(financiamiento);
        	   $('#format_financiamiento').val(formato_money(oper));
            }
        }
    }

    function corrida(){
    	var tasa = ($('#tasa').val() / 100)/12;
    	//var plazo = $('#plazo').val() * 12;
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
    	/*for (var mensualidad = 0; mensualidad < plazo ; mensualidad ++) {
    		var v_contado        = (Math.round(contado * 100) / 100);
            var v_capital      = (Math.round(capital * 100) / 100);
            var v_intereses    = (Math.round(intereses * 100) / 100);
            var v_pago_mensual = (Math.round(pago_mensual * 100) / 100);
            var v_saldo        = (Math.round(saldo * 100) / 100);
    		$('#mostrar_corrida').append(
    			"<tr>"+
    				"<td class='text-center'>" + (parseFloat(mensualidad) + 1) + "</td>" +
    				"<td> $" + formato_money(v_contado) + "</td>" +
    				"<td> $" + formato_money(v_capital) + "</td>" +
    				"<td> $" + formato_money(v_intereses) + "</td>" +
    				"<td> $" + formato_money(v_pago_mensual) + "</td>" +
                    "<td> $" + formato_money(v_saldo) + "</td>" +
    			"</tr>"
    			);
    		contado -= capital;
    		intereses = tasa * contado;
    		capital = pago_mensual - intereses;
            saldo -= pago_mensual;*/
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

    function formato_money(money){
        var v_money = money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");
        return v_money;
    }

});