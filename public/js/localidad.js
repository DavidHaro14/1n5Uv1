$(document).ready(function(){
	$('#state').change(function(){
        var id = $(this).val();
        $.ajax({
            url:"/MunicipiosGet",
            type:"GET",
            dataType:"json",
            data:{'id':id},
            beforeSend:function(){
                $('#town').append('<option selected value=""> Cargando... </option>');
                $('#town').prop('disabled',true);
            },
            success:function(data){
                //Si data no esta vacio
                if (data != "") {
                    $('#town').prop('disabled',false);
                    $('#town').empty();
                    $('#town').append('<option selected value=""> Seleccionar </option>');
                    $.each(data, function(index, dato){
                        $('#town').append('<option value="'+dato.id_municipio+'"> '+dato.nombre+' </option>');
                    });
                } else {
                    $('#town').empty();
                    $('#town').append('<option selected value=""> No se encontro... </option>');
                }
            }
        });
    });

	/*$('.btn-editar').click(function(){
		$('.townEdit').empty();
		$('.townEdit').append('<option selected value=""> Seleccionar </option>');
	});*/

	/*$('.stateEdit').change(event => {
		$.get(`MunicipiosGet/${event.target.value}`, function(res, sta){
			$('.townEdit').empty();
			$('.townEdit').append('<option selected value=""> Seleccionar </option>');
			res.forEach(element => {
				$('.townEdit').append(`<option value=${element.id_municipio}> ${element.nombre} </option>`);
			});
		});
	});*/
});