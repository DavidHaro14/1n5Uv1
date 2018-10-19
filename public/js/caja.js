$(document).ready(function(){

    /* CREDITO */
	// Al cargar el documento ejecutar funciones: 
	MensualidadPendienteAbono();
	CalcularTotalPagarSelected();

	// FUNCTIONS
	function MensualidadPendienteAbono(){
		var cont = 0;
		$('input[name="pagos[]"]').each(function(){
			var CheckboxDisabled = $(this).prop('disabled');
			if(CheckboxDisabled){
				cont++;
			} 
		});

		if(cont > 0){
			$('#CheckAll').prop('disabled',true);
            $('.PagoAbono').prop('checked',true);
		}
	}

	function Limite(){
    	var limite = $('#limite').val();
    	var tipo   = $('#tipo_limite').val();
    	var saldo  = $('#saldo').val();//Total a pagar (Contado + Financiamiento)
        // alert(limite)
        // alert(tipo)
        // alert(saldo)
    	if(tipo != "monto"){
    		return parseFloat(saldo) * parseFloat(limite);
    	}

    	return limite;
	}

	function CalcularTotalPagarSelected(contado = false){
    	if(contado){
            var DescuentoCapital   = 0;
            var DescuentoInteres   = 0;
            var DescuentoMoratorio = 0;
            var moratorios         = 0;
            $('.DescuentoCapital').each(function(){
                DescuentoCapital += parseFloat($(this).val());
            });
            $('.DescuentoInteres').each(function(){
                DescuentoInteres += parseFloat($(this).val());
            });
            $('.DescuentoMoratorio').each(function(){
                DescuentoMoratorio += parseFloat($(this).val());
            });
            $('.moratorio').each(function(){
                moratorios += parseFloat($(this).val());
            });
            var tabla = $('#tabla_cobro').val();
            var saldo = $('#contado').val();//Contado -> Sin Financiamiento
            if (tabla != "4") {
                saldo = $('#saldo_tabla_5').val();
            }
            
            //var moratorios = $('#total_moratorio').val();
            var format     = Math.round((parseFloat(saldo) + (parseFloat(moratorios) - DescuentoMoratorio) - DescuentoInteres - DescuentoCapital) * 100) / 100;

    		$('.total_pagar_credito').val(format);
    		$('.txt-abono').prop('readonly',true).val('0.00');
    		return contado;
    	}

    	var limite             = Limite();
        var pagar              = 0;
        var moratorios         = 0;
		var cont               = 0; // Si es mas de uno no aplica abono -> porque es mas de una mensualidad
        var DescuentoCapital   = 0;
        var DescuentoInteres   = 0;
        var DescuentoMoratorio = 0;
		$('input[name="pagos[]"]').each(function(pos){
    		if (this.checked) {
    			cont++;
    			pagar += parseFloat($('#mensualidad').val());
                $('.moratorio').each(function(index){
                    if(index === pos){
                        moratorios += parseFloat($(this).val());
                        return false;
                    }
                });
                $('.DescuentoCapital').each(function(index){
                    if(index === pos){
                        DescuentoCapital += parseFloat($(this).val());
                        return false;
                    }
                });
                $('.DescuentoInteres').each(function(index){
                    if(index === pos){
                        DescuentoInteres += parseFloat($(this).val());
                        return false;
                    }
                });
                $('.DescuentoMoratorio').each(function(index){
                    if(index === pos){
                        DescuentoMoratorio += parseFloat($(this).val());
                        return false;
                    }
                });
    		} 
    	});
        //alert(moratorios)
        var tabla = $('#tabla_cobro').val();
    	if (pagar /*(- moratorios)*/ > limite && moratorios < 1 && tabla === "4") {
    		LimiteRevasado();
    	} else {
    		//$('#reestructura').hide();
            $('.RadioReestructura').prop('checked',false).prop('disabled',true);
            $('.title-reestructura').removeAttr('data-toogle data-placement title data-original-title').popover('destroy');
    		$('.RadioNinguno').prop('checked',true);
    	}

    	var pago       = $('#mensualidad').val();
        var abonados   = $('#abonado').val();
        var MoraAbon   = $('#moratorios_abonados').val();
        /*var indexo = 1;
        $('.moratorio').each(function(index, element){
            if(index === indexo){
                console.log($(this).val());
                console.log(index);
                console.log(element);
            }
        });*/
		//var UltimoMora = $('#ultimo_moratorio').val();
		//var moratorios = $('#moratorios').val();
        var resto = (parseFloat(pago) + parseFloat(moratorios) - DescuentoInteres - DescuentoMoratorio - DescuentoCapital) - (parseFloat(abonados) - parseFloat(MoraAbon)) /*+ parseFloat(moratorios)*/;
        //console.log(moratorios);
        //$('.total_pagar_credito');
        
        if(cont === 1){
            $('.PagoAbono').prop('checked',true);
            $('.txt-abono').prop('readonly',false).val(Math.round((resto) * 100) / 100);
            $('.total_pagar_credito')/*.prop('readonly',true)*/.val(Math.round((resto) * 100) / 100);
            $('.PagoMensual').prop('checked',false);
        } else {
            $('.PagoAbono').prop('checked',false);
            $('.txt-abono').prop('readonly',true).val('0.00');
            $('.total_pagar_credito')/*.prop('readonly',false)*/.val(Math.round((pagar + moratorios - DescuentoInteres - DescuentoMoratorio - DescuentoCapital) * 100) / 100);
        }

        if (cont > 1) {
            $('.PagoMensual').prop('checked',true);
        }
    }

    function ValidarAbono(){
        var abono      = $('.txt-abono').val();
        var pago       = $('#mensualidad').val();
        var abonados   = $('#abonado').val();
        var MoraAbon   = $('#moratorios_abonados').val();
        var moratorios = $('#moratorio').val();

        var total    = parseFloat(abono) + parseFloat(abonados);
        var resto      = (parseFloat(pago) + parseFloat(moratorios)) - (parseFloat(abonados) - parseFloat(MoraAbon)) /*+ parseFloat(moratorios)*/;
        resto = Math.round(resto * 100) / 100;

        if(parseFloat(total) > (parseFloat(pago) + parseFloat(moratorios))){
            $('.txt-abono').val(resto);
            $('.total_pagar_credito').val(resto);
        }
    }

	function LimiteRevasado(){
        $('.RadioReestructura').prop('disabled',false);
        $('.title-reestructura').attr({'data-toogle':'popover','data-placement':'top','title':'Reestructura','data-content':'Se puede realizar reestructura'}).popover('show');
		//$('#reestructura').show();
	}

    function formato_dinero(money){

    	var v_money = money;
		var f_money = '$' + v_money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");

		return f_money;
    }

    //EVENTOS
    $('.txt-abono').keyup(function(){
        ValidarAbono();
    });

    $('.txt-abono').change(function(){
        ValidarAbono();
    });

    $('input[name="pagos[]"]').click(function(){
        $('#CheckAll').prop('checked',true);
        var pos  = 0;
        var cont = 0;
        //identificar cuantos checks se seleccionaran al check de la mensualidad
        $('input[name="pagos[]"]').each(function(index){
            if (this.checked) {
                pos = index;
            }
        });

        //checkear los checksbox anteriores desde la mensualidad que selecciono
        $('input[name="pagos[]"]').each(function(index){
            if (index <= pos) {
                $(this).prop('checked',true);
            } else {
                cont++;
            }
        });

        if(cont > 0){
           CalcularTotalPagarSelected();
            //$('#paga_todo').prop('checked',false);
            //$('input[name="forma_pago"]').prop('checked',false);
            //$('.total_pagar_credito').prop('readonly',false);
        } else {
            CalcularTotalPagarSelected(true);
            $('input[name="forma_pago"]').prop('checked',false);
            $('.PagoContado').prop('checked',true);
            //$('.total_pagar_credito').prop('readonly',true);
            //$('#paga_todo').prop('checked',true);
        }
    });
    
    //Checkbox del <th> -> seleccionar todos los checksbox
    $('#CheckAll').click(function(){
        var valor = $(this).prop('checked');

        $('input[name="pagos[]"]').each(function(){
            $(this).prop('checked',valor);
        });

        //$('#paga_todo').prop('checked',valor);
        if(valor){
            CalcularTotalPagarSelected(valor);
            $('input[name="forma_pago"]').prop('checked',false);
            $('.PagoContado').prop('checked',true);
            //$('.total_pagar_credito').prop('readonly',true);
        } else {
            //$('#reestructura').hide();
            $('input[name="forma_pago"]').prop('checked',false);
            $('.RadioReestructura').prop('checked',false).prop('disabled',true);
            $('.title-reestructura').removeAttr('data-toogle data-placement title data-original-title').popover('destroy');
            $('.RadioNinguno').prop('checked',true);
            $('.txt-abono').prop('readonly',true).val('0.00');
            $('.total_pagar_credito').val('0.00')/*.prop('readonly',false)*/;
        }
    });

    //Calcular el cambio -> Paga con $ -> Cambio $
    $('.txt-paga').keyup(function(){

        var total = $('.total_pagar_credito').val();
        var paga = $(this).val();
        var cambio = paga - total;

        if (cambio <= 0) {

            $('.txt-cambio').val("0.00");

        } else {

            $('.txt-cambio').val(cambio.toFixed(2));

        }
        
    });

    /* AHORRADOR */
    /*$('.txt-pago-ahorrador').keyup(function(){
        ValidarPagoAhorrador();
    });

    $('.txt-pago-ahorrador').change(function(){
        ValidarPagoAhorrador();
    });

    function ValidarPagoAhorrador(){
        var pago    = $('.txt-pago-ahorrador').val();
        var abonado = $('.total_abonado').val();
        var monto   = $('.enganche').val();

        var total_pago = parseFloat(pago) + parseFloat(abonado);

        if(parseFloat(total_pago) > parseFloat(monto)){
            var resto = parseFloat(monto) - parseFloat(abonado);
            $('.txt-pago-ahorrador').val(resto);
        }
    }*/

    /* CONVENIO */
    /*$('.txt-pago-convenio').keyup(function(){
        ValidarPagoConvenio();
    });

    $('.txt-pago-convenio').change(function(){
        ValidarPagoConvenio();
    });

    function ValidarPagoConvenio(){
        var pago    = $('.txt-pago-convenio').val();
        var abonado = $('.abonado_convenio').val();
        var monto   = $('.pago_convenio').val();

        var total_pago = parseFloat(pago) + parseFloat(abonado);
        
        if(parseFloat(total_pago) > parseFloat(monto)){
            var resto = parseFloat(monto) - parseFloat(abonado);
            $('.txt-pago-convenio').val(resto);
        }
    }*/
});