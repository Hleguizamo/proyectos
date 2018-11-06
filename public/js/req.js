function mostrarCambiarEstado(){
	getEstados();
	$.confirm({
    title: 'Agregar',
    columnClass : "xl",
    content: getChangeStateHtml(),
    buttons: {
        aceptar: {
            text: 'Aceptar',
            btnClass: 'btn-success',
            action: function () {
              changeState();
                
            }
        },
        Cancelar: {
            text: 'Cancelar',
            btnClass: 'btn-danger',
            action: function () {
            }
        },
        
    },
    
  });
}

function changeState(){
	$.ajax({
		url : "UpdateStates",
		type : "post",
		data : {
			estado : $("#selectEstadosChange").val(),
			req : $("#numReqChange").val()
		},
		success:function(data){
			tablaDatos.ajax.reload();
		}
	});
}


function getEstados(){
	$.ajax({
		url : "ListaEstados",
		type : "get",
		data : {},
		success:function(data){
			console.log(data);
			code = '';
			$.each(data.estados,function(index,estado){
				code = code + '<option value="'+estado.value+'" > '+estado.name+' </option>';
			});
			$("#selectEstadosChange").html(code);
		}
	});
}

function getChangeStateHtml(){
	var code = "";
	code = code + '<div class="col-xs-12" > <input type="number" calss="form-control" placeholder="Número de requerimiento" id="numReqChange"> </div>';
	code = code + '<label>Estado Actual</label>';
	code = code + '<span id="spanEstado"> </span>';
	code = code + "<div>";
	code = code + '<select class="form-control" id="selectEstadosChange">';
	code = code + "</select>";
	return code;
}