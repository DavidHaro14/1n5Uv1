$(document).ready(function(){
	//Seleccionar contenido del input
	// $('input').focus(function(){
 //        this.select();
 //    });

	//Traer los tipos de programas del programa seleccionado
	$('#programa').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/TiposProgramasGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            // beforeSend:function(){
            // 	$('#documentos').empty().hide();
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

	//Traer los documentos del tipo de programa seleccionado
	$('#TiposProgramas').change(function(){
		var id = $('#TiposProgramas').val();
		var route  = "/DocumentosGet/" + id;
        $.ajax({
          url: route,
          type: 'GET',
          dataType: 'json',
          beforeSend:function(){
        	  $('.img-carga').show();
			      $('#documentos').empty().hide();
          },
          success:function(data){
            $('#plantilla').val(data.plantilla);
            $('.img-carga').hide();
            $('#documentos').fadeIn();
            $.each(data.documentos, function(index, data){
                var required = (data.opcional) ? "" : "required";
                $('#documentos').append("<label><input type='checkbox' value='1' "+required+"> " + data.nombre + "</label><br>");
            });
          },
          error:function(){
            $('.img-carga').hide();
            $('#documentos').empty().hide();
            $('#plantilla').val(0);
          }
        });
	});

	//Dependiendo del enganche se seleccionara si sera SOLICITANTE o AHORRADOR
	$('#enganche').change(function(){
		var enganche = $(this).val();
		//console.log(enganche);
		if(enganche == ""){
			$('#enganche').val("0");
			$('#tipo_cliente').val("SOLICITANTE");
		} else if(enganche > 0){
			$('#tipo_cliente').val("AHORRADOR");
		} else {
			$('#tipo_cliente').val("SOLICITANTE");
		}
	});
});