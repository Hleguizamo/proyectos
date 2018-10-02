$( document ).ready(function() {
  //$('#reqTable').DataTable();
  $.ajax({
  	url : "misRequerimientosById2",
  	type : "GET",
  	data: {},
  	success : function(data){
  		if(data.success){
        console.log(data);
  			mostrarRequerimientos(data.datos);
  		}
  	}
  });
});




function mostrarRequerimientos(datos){
	var code = '';


                 
	$.each(datos,function(index,dato){
		//var porcen = (dato.estado_id == 1 )? 33 : ( dato.estado_id == 2 ? 66 : ( dato.estado_id == 3 ? 100 : 0 ) );
		/*code = code + 
			'<div class="col">'+
        
				'<label style="width:70%">'+dato.numero_requerimiento+"  "+dato.descripcion+'</label>'+
        '<label style="width:30%"  class="text-right">'+dato.nombre_estado+'</label>'+
       
				'<div class="progress">'+
					'<div class="progress-bar copi-azul-claro" role="progressbar" style="width: '+ dato.avance_porcentual + '%" aria-valuenow="'+dato.avance_porcentual+'" aria-valuemin="0" aria-valuemax="100"></div>'+
				'</div>'+
			'</div>';*/

      //llenar(dato);
      html='<tr>'+
                          '<td>'+dato.fecha_creacion+'</td>'+
                          '<td>'+dato.numero_requerimiento+'</td>'+
                          '<td>'+dato.descripcion+'</td>'+
                          '<td>'+dato.nombre_aplicacion+'</td>'+
                          '<td>'+dato.nombre_modulo+'</td>'+
                          '<td>'+dato.nombre_gerencia+'</td>'+
                          '<td>'+dato.nombre_area+'</td>'+
                          '<td>'+dato.estado_requerimiento+'</td>'+
                          '<td>'+dato.fecha_asignacion+'</td>'+
                          '<td>'+dato.fecha_estimada_entrega+'</td>'+
                          '<td>'+dato.fecha_cierre+'</td>'+
                          '<td>'+dato.observaciones+'</td>'+
                        '</tr>';
      console.log(html);
      $("#cuerpo").append(html);


	});
	$("#requerimientos").html(code);

}

function llenar(dato){

  $('#reqTable').DataTable({
            "destroy": true,
            "data": [
                [
                 
            
                
         
     
                 
      
                ],
            ],
        });
}


function filtrar(){
	var area = $("#fArea").val();
	var req = $("#fReq").val();
  if(req==""){
    req='null';
  }
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


















