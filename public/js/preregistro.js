$(document).ready(function(){

  $('#btn-validar').click(function(){
      var dato  = $('#dato').val();

      $.ajax({
        url : 'GetValidar',
        type : 'GET',
        dataType : 'json',
        data : {'curp': dato},
        success:function(data){
          $('.msj-error').fadeOut();
          $('#sesion').fadeOut();
          $('#validacion').fadeOut();
          $('#formulario').fadeIn();
          $('#curp').val(dato);
          $('#curp').attr('readonly','readonly');
          $('#body-validacion').removeClass().addClass('row col-md-12');
        },
        error:function(msj){
          $('#msj').html(msj.responseJSON.curp);
          $('.msj-error').fadeIn();
        }
      });
  });

  $('#EstadoCivil').change(function(){
    if($('#EstadoCivil').val() == 'VIUDO(A)' || $('#EstadoCivil').val() == 'UNION LIBRE' || $('#EstadoCivil').val() == 'CASADO(A)'){
       //Mostrar 
          $('#Conyuge').fadeIn(); 
          $('.Conyuge').prop('required',true);
          
          if($('#EstadoCivil').val() == 'CASADO(A)'){

          $('.bienes').fadeIn(); 
            $('#bienes').prop('required',true);

        } else { 
          $('.bienes').fadeOut(); 
          $('#bienes').prop('required',false);
        }

        } else {
       //Borrar datos inputs
            $('.Conyuge').val("");

            //Quitar required inputs
            $('.Conyuge').prop('required',false);

            //Ocultar 
            $('#Conyuge').fadeOut(); 

            //bienes del conyuge quitar
            $('.bienes').fadeOut();
            $('#bienes').prop('required',false);
        }
  });

    $('.close-alert').click(function(){
        $('.msj-error').fadeOut();
    });

    //Confirmar contraseña
    $('#pass2').keyup(function(){
        if($(this).val() == $('#pass').val())
        {
            $(this).attr("class","input-success form-control");
        }
        else{
            $(this).attr("class","input-error form-control");
        }
    });

});