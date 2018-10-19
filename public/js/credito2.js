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

    /*
        JQUERY CAMPOS CHANGE
     */

    $('#monto_credito').keyup(function(){
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

    $('#monto_credito').change(function(){
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

    //Nuevo
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

    function costo_contado(){
    	var m_credito      = $('#monto_credito').val();
    	var enganche       = $('#enganche').val();
    	var federal        = $('#input_federal').val();
    	var estatal        = $('#input_estatal').val();
        var municipal      = $('#input_municipal').val();
        var otros          = $('#input_otros').val();
    	var contado        = m_credito - enganche - federal - estatal - municipal - otros;

    	//$('#costo_contado').val(contado);
        if (contado > 0) {
            $('#costo_contado').val(contado);
    	    $('#format_costo_contado').val(formato_money(Math.round(contado * 100) / 100)).removeAttr('data-toogle data-placement title data-original-title').popover('destroy');
            $('.btn-success').show();
        } else {
            //$('#costo_contado').val(contado).attr('data-toogle','tooltip');
            $('#costo_contado').val(contado);
            $('#format_costo_contado').val(formato_money(Math.round(contado * 100) / 100)).attr({'data-toogle':'popover','data-placement':'bottom','title':'Error','data-content':'El costo de contado no debe ser menor a cero.'}).popover('show');
            $('.btn-success').hide();
        }
    }

    function mensualidad(){
        var tabla = $('#t_cobros').val();
    	//var interes = ($('#tasa').val() / 100)/12;
    	var plazo = $('#plazo').val();
    	var costo_contado = $('#costo_contado').val();
        var mensualidad = 0;

    	//var mensualidad = (interes * costo_contado) / (1 - Math.pow(1 + parseFloat(interes), -plazo) );
        
        if(tabla > 4){
            mensualidad = costo_contado / plazo;
        } else {
            var interes = ($('#tasa').val() / 100)/12;      
            mensualidad = (interes * costo_contado) / (1 - Math.pow(1 + parseFloat(interes), -plazo) );
        }

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
    		var v_contado      = (Math.round(contado * 100) / 100);
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
            saldo -= pago_mensual;
    	}*/
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