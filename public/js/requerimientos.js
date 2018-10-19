var columns = [];
var distribucion = null;
var saveUrl = null;  //Url donde se mandan a crear los datos
var editUrl = null; //Url donde se mandan a ediatar los datos
var dataEditUrl = null; //Url donde se consultan los datos a editar
var id_registro = null; //nombre de la columna que es id para el registro actual
var tablaDatos = null;
var id_edit = null; //id del registro que se esta actualizando actualmente

$( document ).ready(function() {
  loadDataConfig();


  
});

function edit(event,control){
  event.preventDefault();
  var index = $(control).parent().parent().index();
  var pagina = tablaDatos.page();
  var numReg = pagina == 0? 0 : tablaDatos.page.len();
  pagina = pagina == 0? 1 : pagina;
  index = (index * pagina) + numReg;
  var index2 = tablaDatos.rows()[0][index];
  //console.log("index "+index+" index2: "+index2)
  var data = tablaDatos.row(index2).data();

  //console.log(data);
  var id = data[id_registro];
  getDataEdit(id);
  
}

function getDataEdit(id_reg){
  $.ajax({
    url : dataEditUrl,
    type : "POST",
    data : {id : id_reg},
    success : function(data){
      if(data.success){
        console.log(data);
        showEdit(data.data[0],id_reg);

      }
    },
    error : function(errors){
      conosle.log(errors);
    }
  });
}

function showEdit(data,id_reg){
  $.confirm({
    title: 'Editar',
    columnClass : "xl",
    content: getFormUpdate(data),
    buttons: {
        aceptar: {
            text: 'Aceptar',
            btnClass: 'btn-success',
            action: function () {
              var form = $("#updateForm");
                var data = getFormJsonData(form);
                id_edit = id_reg;
                save(data,editUrl);
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

function update(data){

}

function setColumnFilters(tableId){

  $('#'+tableId+' thead tr').clone(true).appendTo( '#'+tableId+' thead' );
    $('#'+tableId+' thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" class="form-control" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( tablaDatos.column(i).search() !== this.value ) {
                tablaDatos
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
}

function loadDataConfig(){
  var segment = $(location).attr('href').split("/")[3];
  $.ajax({
    url : segment+"/crudDatas",
    type : "GET",
    data: {},
    success : function(data){
      $("#contentTitle").html(data.PageTitle);
      columns = data.columns;
      distribucion = data.dist;
      saveUrl = data.saveUrl;
      dataEditUrl = data.getDataEdit;
      id_registro =  data.idColumn;
      editUrl = data.editUrl;
      addTableHead(data.columns);
      loadDataTable(data.dataRoute,data.dataSrc,data.columns);
      loadButtons(data.buttons);
    }
  });

}

function loadButtons(buttons){
  $.each(buttons,function(buttonName,buttonAction){
      $("#buttons").append('<button type="button" onclick="'+buttonAction+'" class="btn btn-primary btn-sm"> '+buttonName+' </button>');
  });

}

function getDistrib(){
  distribucion = null;
  switch(distribucion){
    case "2-cols":
        return "col-xs-6";
    case "3-cols":
        return "col-xs-4";
    case "4-cols":
        return "col-xs-12 col-sm-6 col-md-3 col-lg-3";
    default:
        return "col-xs-12";
  }
}

function addTableHead(columns){
  var code = '';
  $.each(columns,function(index,col){
    var name = col.name != undefined && col.name != "" ? col.name : col.data;
    code = code + '<th> '+name+'</th>';
  });
  $('#tableHead').html(code);
}



function loadDataTable(url,dataSrc,columns){
  setColumnFilters("reqTable");
  tablaDatos = $('#reqTable').DataTable({
    orderCellsTop: true,
    ajax: {
        url: url,
        dataSrc: dataSrc,

    },

     language: {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
      },


    columns: columns,
    "initComplete": function(settings, json) {
          hideColumns(columns);
    }
  });
  
}

function hideColumns(columns){
  $.each(columns, function(index,col){
      console.log(col.CURD);
      tablaDatos.column(index).visible(col.CRUD[1]);
  });
}

function showAdd(){
  $.confirm({
    title: 'Agregar',
    columnClass : "xl",
    content: getForm(),
    buttons: {
        aceptar: {
            text: 'Aceptar',
            btnClass: 'btn-success',
            action: function () {
              var form = $("#saveForm");
                var data = getFormJsonData(form);
                save(data);
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

function getFormJsonData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function getForm(){
  var code = '<form id="saveForm">';
  
  $.each(columns,function(index,col){
    if(col.CRUD[0]){
      code = code + '<div class="'+getDistrib()+'">';
      code = code +   '<label>'+col.name+'</label>';
      if(col.type =="select"){
        code = code + '<select class="form-control" name="'+col.data+'">';
        code = code + getSelectOptions(col.options);
        code = code + '</select>';
      }else{
        code = code +   '<input type="'+col.type+'" class="form-control" name="'+col.data+'">';  
      } 
      code = code + '</div>';
    }
    
  });
  code = code + '</form>';
  return code;
}



function getFormUpdate(data){
  var code = '<form id="updateForm">';
  $.each(columns,function(index,col){
    if(col.CRUD[2]){
      code = code + '<div class="'+getDistrib()+'">';
      code = code +   '<label>'+col.name+'</label>';
      if(col.type =="select"){
        code = code + '<select class="form-control" name="'+col.data+'">';
        code = code + getSelectOptions(col.options,data[col.data]);
        code = code + '</select>';
      }else{
        value = "";
        if(col.type == "date"){
          d = new Date(data[col.data]);
          var month = (d.getMonth() + 1) < 10 ? ("0"+(d.getMonth() + 1)) : (d.getMonth() + 1);
          var day = (d.getDate() < 10 )? ( "0"+d.getDate() ) : d.getDate();
          value = d.getFullYear() + "-" + month + "-" + day;
        }else{
          value = data[col.data]
        }
        code = code +   '<input type="'+col.type+'" class="form-control" name="'+col.data+'" value="'+ value +'">';  
      } 
      code = code + '</div>';
    }
    
  });
  code = code + '</form>';
  return code;
}




function getSelectOptions(options, value=null ){
  var code = "";
  $.each(options,function(indx,opt){
    var selected = indx == 0? "selected" : "";
    selected = value == null ? selected : ( opt.value == value? 'selected' : '' );
    code = code + '<option value="'+opt.value+'" '+selected+'>'+opt.name+'</option>';
    
  });
  return code;
}

function save(data, url = null){
  url = url == null ? saveUrl :  url;
  if(id_edit != null){
    data.id = id_edit;
  }
  $.ajax({
    url : url,
    type:"POST",
    data : data,
    success : function(ReqData){
      if(ReqData.success){
        if (ReqData.msg) {}
      }
      var color = (data.success)? "green" : "red";
      if(ReqData.msg){
        $.confirm({
            title: 'Alerta',
            content: ReqData.msg,
            type: 'red',
            typeAnimated: true,
            buttons: {
                
                close: function () {
                }
            }
        });
        if(ReqData.success){
          tablaDatos.ajax.reload();
        }
      }
      id_edit = null;

    },
    error : function(errors){
      $.confirm({
            title: 'Error Inesperado',
            content: 'Ha ocurrido un error inesperado',
            type: 'red',
            typeAnimated: true,
            buttons: {
                
                close: function () {
                }
            }
        });
      console.log(errors);
      id_edit = null;
    }
  });
}





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


















