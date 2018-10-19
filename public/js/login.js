/*
	MOSTRAR/OCULTAR DESPUES DE VALIDAR LA CURP
 */
$(function(){
	$('#btn-mostrar').click(function(){
		$('#div-oculto').show();
		$('#btn-mostrar').hide();
		$('#span-valido').show();
	});
});



/*
	Fecha en los selects DIA-MES-ANO (options) EJEMPLO
 */
/*$(function(){
	for (var i = 1; i <= 31; i++) 
	{
		var z = document.createElement("option");
	    if(i > 9){
	    	var t = document.createTextNode(i);
		    z.appendChild(t);
		    document.getElementById("select-dia").appendChild(z);
	    }
	    else
	    {
	    	var t = document.createTextNode("0"+i);
		    z.appendChild(t);
		    document.getElementById("select-dia").appendChild(z);
	    }
	};
});*/

/*
	CALENDARIO FECHA DE NACIMIENTO
 */

// $('#date').datepicker({
//         dateFormat: "dd-mm-yy",
//         firstDay: 1,
//         dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
//         dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
//         monthNames: 
//             ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
//             "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
//         monthNamesShort: 
//             ["Ene", "Feb", "Mar", "Abr", "May", "Jun",
//             "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
// });