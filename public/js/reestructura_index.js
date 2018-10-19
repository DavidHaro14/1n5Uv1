$(document).ready(function(){
	//Input de busqueda
	$('#tipo').change(function(){
		var type = $(this).val();
		if(type == "curp"){
			$('#dato').attr('maxlength','18').val("");
		} else {
			$('#dato').attr('maxlength', false).val("");
		}	
	});

	//Desplegar los creditos del cliente seleccionado
	var pintar_creditos = "";
	$(document).on("click",".view-credito",function(){
		$('.client').fadeOut();
		var dato = $(this).val();
		var tipo = "cliente";

		$.ajax({
	        url : '/GetAcreditado',
	        type : 'GET',
	        dataType : 'json',
	        async: false,
	        data : {'buscar': dato, 'tipo': tipo, 'Filtro':false},
	        success:function(data){
	        	if(data != ""){
	        		$('#creditos').empty();
		        	$.each(data,function(index,credito){
		        		//if(((credito.clave_credito && credito.estatus == "PAGANDOLA") || (credito.clave_credito && credito.estatus == "SUSPENDIDA")) && credito.plantilla != "3"){
		        			pintar_creditos +=
		        				'<div class="panel">'+
		        					'<div class="panel-heading" style="background-color: rgb(81,81,81);color:white;">CREDITO <b>' + credito.clave_credito + '</b> - MODULO: <b>' + String(credito.modulo).substring(5) + '</b></div>'+
		        						'<table class="table table-hover">'+
		        							'<tbody>'+
		        								'<tr>'+
							                        '<td><b>Saldo a pagar:</b></td>'+
							                        '<td>$'+formato_money(Math.round(credito.total_pagar * 100) / 100 )+'</td>'+
							                        '<td><b>Pago mensual:</b></td>'+
							                        '<td>$'+formato_money(Math.round(credito.pago_mensual * 100) / 100 )+'</td>'+
							                        '<td><b>Total abonado:</b></td>'+
							                        '<td>$'+formato_money(Math.round(credito.total_abonado * 100) / 100 )+'</td>'+
							                    '</tr>'+
							                    '<tr>'+
							                        '<td><b>Plazo:</b></td>'+
							                        '<td>'+credito.plazo+' Meses</td>'+
							                        '<td><b>Fecha inicio:</b></td>'+
							                        '<td>'+credito.fecha_inicio+'</td>'+
							                        '<td><b>Enganche:</b></td>'+
							                        '<td>$'+formato_money(Math.round(credito.enganche * 100) / 100 )+'</td>'+
							                    '</tr>';
							GetMensualidades(credito.clave_credito, credito.pago_mensual, credito.moratorio);
							pintar_creditos +=
						                    '<tr>'+
						                        '<td colspan="6" class="text-center"><a href="/Reestructura/'+credito.clave_credito+'" class="btn BtnOrange"><span class="icon icon-wrench"></span> Reestructurar</a></td>'+
						                    '</tr>'+
					                    '</tbody>'+
				                    '</table>'+
			                    '</div>'
		        			;
		        			$('#creditos').append(pintar_creditos);
		        			pintar_creditos = "";
		        		//}
		        	});
					$('#creditos').append('<div style="margin-bottom:25px;" class="text-center"><a href="/Reestructura" class="btn BtnRed"><span class="icon icon-reply"></span> Regresar</a></div>');
	        	} else {
	        		alert("Error no se encontro ningun credito")
	        	}
	        }

	    });
	});

	//Buscar cliente por Ajax
	$('#btn-search').click(function(){
		var dato = $('#dato').val();
		var tipo = $('#tipo').val();

		$.ajax({
	        url : '/GetAcreditado',
	        type : 'GET',
	        dataType : 'json',
	        async: false,
	        data : {'buscar': dato, 'tipo': tipo, 'Filtro':true},
	        success:function(data){

	            if(data != ""){
	            	append_client(data);
	            } else {
	            	$('.t-body').empty().append("<tr><td colspan='5' class='text-center'><b>NO SE ENCONTRO RESULTADOS</b></td></tr>");
	            }
	        }

	    });
	});

	//Pintar los clientes en la tabla
	function append_client(datos){
		$('.t-body').empty();
		$.each(datos, function(index, data){
			if (data.clave_credito) {
				$('.t-body').append(
					"<tr>"+
						"<th class='text-center'>"+ pad(data.id_cliente,4) + "</th>"+
						"<td>"+ data.nombre + " " + data.ape_paterno + " " + data.ape_materno + "</td>"+
						"<td>"+ data.curp + "</td>"+
						"<td>"+ data.estado_civil + "</td>"+
						"<td class='text-center'><button type='button' class='btn BtnOrange btn-xs view-credito' value='"+ data.id_cliente +"'><span class='icon icon-eye'></span> Ver</button></td>"+
					"</tr>"
				);
			}
		});
	}

	function pad(n, length) {

	    var  n = n.toString();

	    while(n.length < length)

	         n = "0" + n;

	    return n;
	    
	}

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

    function GetMensualidades(clave,mensualidad,moratorio){
    	var total_moratorio = 0;
    	$.ajax({
    		url : '/GetMensualidadesVenc',
	        type : 'GET',
	        dataType : 'json',
	        async: false,
	        data : {'clave': clave},
	        success:function(data){
	        	if(data != ""){
	        		total_moratorio = ((parseFloat(mensualidad) * parseFloat(moratorio)) / 30) * parseFloat(data[0].dias_venc);
	        		//console.log(data);
		        	pintar_creditos +=
	                    '<tr>'+
	                        '<td><b>Mens. no vencidos:</b></td>'+
	                        '<td>'+data[0].no_pagados+'</td>'+
	                        //'<td>'+data[0].pagados+'</td>'+
	                        '<td><b>Mens. vencidos:</b></td>'+
	                        '<td>'+data[0].vencidos+'</td>'+
	                        '<td><b>Moratorios:</b></td>'+
	                        '<td>$'+formato_money(total_moratorio)+'</td>'+
	                    '</tr>'
        			;
	        	}
	        }
    	});
    }
});