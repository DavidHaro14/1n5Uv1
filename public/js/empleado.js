$(document).ready(function() {
    $('#operacion').change(function(){
        //$(this).trigger('chosen:updated');
        var valor = $(this).val();
        $('#permisos').val('').prop('disabled',false);
        $('#jefes').val('0').prop('disabled',false);
        if (valor != null) {
            for (var i = 0; i < valor.length; i++) {
                if(valor[i] === "CAJA"){
                    $(this).val(["CAJA"]).trigger('chosen:updated');
                    $('#permisos').val('50').prop('disabled',true);
                    return false;
                } else if (valor[i] === "ADMON"){
                    $(this).val(["ADMON"]).trigger('chosen:updated');
                    $('#permisos').val('100').prop('disabled',true);
                    $('#jefes').val('0').prop('disabled',true);
                    return false;
                }
            }
        }
    });

    $('#confirm_password, #password').keyup(function(){
        ValidarPassword();
    });

    function ValidarPassword(){
        var pass = document.getElementById('password');
        var pass2 = document.getElementById('confirm_password');

        if (pass.value != pass2.value) {
            pass2.setCustomValidity("Las contraseñas no coinciden");
        } else {
            pass2.setCustomValidity('');
        }
    }
});
