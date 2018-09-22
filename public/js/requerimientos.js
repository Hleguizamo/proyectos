$( document ).ready(function() {

  $.ajax({
  	url : "misRequerimientosById/1",
  	type : "GET",
  	data: {},
  	success : function(data){
  		if(data.success){
  			mostrarRequerimientos(data.datos);
  		}
  	}
  });
});



function mostrarRequerimientos(datos){
	var code = '';
	$.each(datos,function(index,dato){
		//var porcen = (dato.estado_id == 1 )? 33 : ( dato.estado_id == 2 ? 66 : ( dato.estado_id == 3 ? 100 : 0 ) );
		code = code + 
			'<div class="col">'+
				'<label>'+dato.descripcion+'</label>'+
				'<div class="progress">'+
					'<div class="progress-bar copi-azul-claro" role="progressbar" style="width: '+ dato.avance_porcentual + '%" aria-valuenow="'+dato.avance_porcentual+'" aria-valuemin="0" aria-valuemax="100"></div>'+
				'</div>'+
			'</div>';

	});
	$("#requerimientos").html(code);

}


function filtrar(){
	var area = $("#fArea").val();
	var req = $("#fReq").val();
	var estado = $("#fEstado").val();
	var modulo = $("#fModulo").val();
	$.ajax({
  	url : "filtroRequerimientosById/1/"+area+"/"+req+"/"+estado+"/"+modulo,
  	type : "GET",
  	data: {},
  	success : function(data){
  		if(data.success){
  			mostrarRequerimientos(data.datos);
  		}
  	}
  });

}


















