$(document).ready(function(){
    /* CREDITO */
	// Al cargar el documento ejecutar funciones: 

	// FUNCTIONS
    function CalcularCantidades(){
        var capital            = 0;
        var interes            = 0;
        var moratorios         = 0;
        var DescuentoCapital   = ($('#input_capital').val() === "")   ? 0 : $('#input_capital').val();
        var DescuentoInteres   = ($('#input_interes').val() === "")   ? 0 : $('#input_interes').val();
        var DescuentoMoratorio = ($('#input_moratorio').val() === "") ? 0 : $('#input_moratorio').val();

        $('input[name="pagos[]"]').each(function(pos){
            if (this.checked) {
                $('.h_capital').each(function(index){
                    if(index === pos){
                        capital += parseFloat($(this).val());
                        return false;
                    }
                });
                $('.h_interes').each(function(index){
                    if(index === pos){
                        interes += parseFloat($(this).val());
                        return false;
                    }
                });
                $('.h_moratorio').each(function(index){
                    if(index === pos){
                        moratorios += parseFloat($(this).val());
                        return false;
                    }
                });
            } 
        });

        var TotalDescuento  = parseFloat(DescuentoCapital) + parseFloat(DescuentoInteres) + parseFloat(DescuentoMoratorio);
        var TotalPagoSelect = parseFloat(capital) + parseFloat(interes) + parseFloat(moratorios);
        
        $('#txt-capital').text(formato_dinero(capital));
        $('#txt-interes').text(formato_dinero(interes));
        $('#txt-moratorio').text(formato_dinero(moratorios));
        $('#txt-total-pagar').text(formato_dinero(TotalPagoSelect - TotalDescuento));
        $('#txt-total-descuento').text(formato_dinero(TotalDescuento));

        $('#input_capital').attr('max',Math.round(capital * 100) / 100);
        $('#input_interes').attr('max',Math.round(interes * 100) / 100);
        $('#input_moratorio').attr('max',Math.round(moratorios * 100) / 100);
    }

    function formato_dinero(money){

    	var v_money = money;
		var f_money = '$' + v_money.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,");

		return f_money;
    }

    //EVENTOS
    
    //Checkbox del <th> -> seleccionar todos los checksbox
    $('#CheckAll').click(function(){
        var valor = $(this).prop('checked');

        $('input[name="pagos[]"]').each(function(){
            var CheckboxDisabled = $(this).prop('disabled');
            if (!CheckboxDisabled) {
                $(this).prop('checked',valor);
            }
        });

        CalcularCantidades();
    });

    $('input[name="pagos[]"]').click(function(){
        $('#CheckAll').prop('checked',true);
        var pos       = 0;
        // var PrimerPos = 0;
        // var flag      = false;
        //identificar cuantos checks se seleccionaran al check de la mensualidad
        $('input[name="pagos[]"]').each(function(index){
            var CheckboxDisabled = $(this).prop('disabled');
            if (this.checked) {
                pos = index;
            }

            /*if (!flag && !CheckboxDisabled) {
                PrimerPos = index
                flag = true;
            }*/
        });

        //checkear los checksbox anteriores desde la mensualidad que selecciono
        $('input[name="pagos[]"]').each(function(index){
            var CheckboxDisabled = $(this).prop('disabled');
            if (index <= pos && !CheckboxDisabled) {
                $(this).prop('checked',true);
            }
        });

        CalcularCantidades();
    });

    $('input[name="capital"], input[name="interes"], input[name="moratorio"]').change(function(){
        CalcularCantidades();
    });

    $('input[name="capital"], input[name="interes"], input[name="moratorio"]').keyup(function(){
        CalcularCantidades();
    });
});