    <script type="text/javascript">
    var text      = $("#Usuario").val();
    var list;
    var save_method; // VARIABLE DE CONTROL
    var table;
    var deuda_e;
    var idreload = 0;
        $( "#CDE,#Compra" ).addClass( "active" );
        $( "#CDE,#C_D_E" ).addClass( "text-red" );


        const searchDeudaCliente = (event) => {
          event.preventDefault();
          const formData = $('#facturaCuota').serializeArray(); // Serializa el formulario a un array

          if (deuda_e) {
            deuda_e.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

          deuda_e = $('#Deuda_e').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
              url: "Deuda_empresa/ajax_list_deuda_empresa",
              type: "POST",
              data: function (d) {
                const formObj = {};
                $.each(formData, function(index, field) {
                  formObj[field.name] = field.value; // Convertir el array en un objeto
                });
                return $.extend({}, d, formObj); // Combina datos de DataTables con el objeto del formulario
              }
            },
            "columnDefs": [
              {
                "targets":  [ -1],
                "orderable": false,
              },
            ],
          });
        };

        const searchDeudaCobradas = (event) => {
          event.preventDefault();
          const formData = $('#listaCuotasPagadas').serializeArray(); // Serializa el formulario a un array

          if (table) {
            table.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

            table = $('#Pagadas').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "Deuda_empresa/ajax_list_pagadas",
                "type": "POST",
                "data": function (d) {
                    const formObj = {};
                    $.each(formData, function(index, field) {
                    formObj[field.name] = field.value; // Convertir el array en un objeto
                    });
                    return $.extend({}, d, formObj); // Combina datos de DataTables con el objeto del formulario
                }
            },
            "columns": [
                {
                    "targets":  [ -1],
                    "orderable": false,
                    "className":      'details-control',
                    "data":           null,
                    "defaultContent": '',
                },
                { "data": "0" },
                { "data": "1" },
                { "data": "2" },
                { "data": "3" },
                { "data": "4" },
                { "data": "5" },
                { "data": "6" }


            ],
        } );


        };

$('#facturaCuota').on('submit', searchDeudaCliente);
$('#listaCuotasPagadas').on('submit', searchDeudaCobradas);

    // deuda_e = $('#Deuda_e').DataTable({
    //     "processing": true, //Característica de control del indicador de procesamiento.
    //     "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
    //     // Datos de carga de contenidos de la tabla de un origen Ajax
    //     "ajax": {
    //         "url": "Deuda_empresa/ajax_list_deuda_empresa",
    //         "type": "POST"
    //     },

    //     //Conjunto de columnas propiedades de definición de inicialización .
    //     "columnDefs": [
    //     {
    //       "targets":  [ -1], // ultimass columnas
    //       "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
    //     },
    //     ],
    //       // "order": [[ 0, 'desc' ]]
    //   });


function format ( d ) {
    var element     =  d.slice(6) ;
    $.ajax({
    type : 'POST',
    url: "Deuda_empresa/detale/"+element,
    dataType: 'json',
    })
    .done(function(data) {
      if (data) {
        $.each(data, function(index, val) {
          $('#'+element).append('<tr class="success"><td>'+val.des+'</td><td>'+val.fec+'</td><td>'+val.mon+'</td><td><div class="pull-right hidden-phone"><a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top"  href="javascript:void(0);" title="Eliminar" onclick="'+val.id+'"><i class="fa fa-trash-o"></i></a></div></td></tr>');
        });
      }
    });
    return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+element+'">'+
        '<tr class="danger">'+
            '<td>Descripcion</td>'+
            '<td>Fecha de Emision</td>'+
            '<td>Monto Pagado</td>'+
             '<td>Acciones</td>'+
        '</tr>'+
    '</table>';
}
// $('#clicpagada').on('click', function(event) {
//  $('#Pagadas').dataTable().fnDestroy();
//      table = $('#Pagadas').DataTable( {
//         "processing": true, //Característica de control del indicador de procesamiento.
//         "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
//         // Datos de carga de contenidos de la tabla de un origen Ajax
//         "ajax": {
//             "url": "Deuda_empresa/ajax_list_pagadas",
//             "type": "POST"
//         },
//         "columns": [
//             {
//                 "targets":  [ -1, -0, 2 ], // ultimass columnas
//                 "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
//                 "className":      'details-control',
//                 "data":           null,
//                 "defaultContent": '',



//             },
//             { "data": "0" },
//             { "data": "1" },
//             { "data": "2" },
//             { "data": "3" },
//             { "data": "4" },
//             { "data": "5" },

//         ],
//         // "order": [[ 0, 'asc' ]]
//     } );
    // Add event listener for opening and closing details
    $('#Pagadas tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
});

function listar_deudas(id) {
  $('#list').dataTable().fnDestroy();
  $('#Deudas').removeClass('active');$('#listados').addClass('active');
  $('#addtrpdf').attr('onclick', "pdf_exporte('lisdeuda/"+id+"')");
  $('#addtrexel').attr('href','Reporte_exel/lisdeuda/'+id+'');
  if (id > 0) {
  list = $('#list').DataTable({
              "processing": true, //Característica de control del indicador de procesamiento.
              "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
              // Datos de carga de contenidos de la tabla de un origen Ajax
              "ajax": {
                  "url": "DeudaEmpresa/listar_deudas/"+id,
                  "type": "POST",
              },

              //Conjunto de columnas propiedades de definición de inicialización .
              "columnDefs": [
              {
                "targets": [ -1 ], // ultimass columnas
                "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
              },
              ],
 "order": [[ 0, 'acs' ], [ 1, 'acs' ],[ 2, 'acs' ],[ 3, 'acs' ],[ 4, 'acs' ],[ 4, 'acs' ]]
            });
  }


}

function formatNumber(num) {
        const separador = ",";
        const sepDecimal = ".";
        const numStr = num.toString();
        const splitStr = numStr.split(".");
        const splitLeft = splitStr[0].replace(/\B(?=(\d{3})+(?!\d))/g, separador);
        const splitRight = splitStr.length > 1 ? sepDecimal + splitStr[1] : "";
        return splitLeft + splitRight;
}


function atras() {
  $('#listados').removeClass('active');$('#Deudas').addClass('active');
}

function item_cobrar(id,monto,cantidadRestante,Proveedor)
{
      <?php if ($this->session->userdata('idcaja')){ ?>
           $("#recibo,#numero,#alertasadd,#pendiente,#prove").html("").css({"display":"none"});
                $('#c,#t,#s,#Cheque,#Tarjeta,#fabor').removeClass('active');
                $('#ParcialE,#ParcialC,#ParcialT').html('');
                $('.hidden').val('');
                 $("#pagosdeuda").show(); 
                 $('#pagosdeuda')[0].reset();
                 $("#pendiente").append('Monto Pendiente  :  '+formatNumber(monto, "₲ ")).css({"display":"block"});
                 $("#monto").val(monto);
                 $('#id').val(id);
                 $( "#fabor" ).load( "DeudaEmpresa/formapago/"+4+'/'+Proveedor );
                 $( "#piesss" ).load( "DeudaEmpresa/formapago/"+5);
                 $( "#cheque_tercero" ).load( "DeudaEmpresa/cuenta_bancaria");
          $.ajax({  
            type : 'POST',
            url: "DeudaEmpresa/lis_deuda/"+id,
            dataType: 'json',
          })
          .done(function(data) {
                    $("[name='numcheque']").removeAttr('required')
            if (data != null) {
                $("#recibo").append('Recibo Nº : '+data.Num_Recibo).css({"display":"block"});
                $("#numero").append('Cuota  Nº : '+data.Num_cuota).css({"display":"block"});
                $('#idProveedor').val(data.idProveedor);
                $("#prove").append('Proveedor  : '+data.Razon_Social+' '+  data.Vendedor).css({"display":"block"});
                $('#idF').val(data.idFactura_Compra);
                idreload = data.idFactura_Compra;
                $('#crEstado').val(data.crestado);
                $('#cfEstado').val(data.esta);
                $('#cuotaN').val(data.Num_cuota);
                $('#totalrous').val(cantidadRestante);
                $( "#collapseExample" ).collapse('show');
                 $('#limpp').tab('show');
                save_method ='add';
            }

          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });

      <?php } else { ?>
           alertacaja();
      <?php } ?>
      

}
    function reload_table()
    {
      deuda_e.ajax.reload(); //reload datatable ajax 
      listar_deudas(idreload);
    }

$(function() {

function mostrarMensajeError(mensaje) {
  toastem.error(mensaje);
  $('#loadingg').attr({disabled: 'disabled'});
  $('.alerter').html(mensaje).show();
  setTimeout(function() {
    $('#loadingg').removeAttr('disabled');
    $('.alerter').hide();
  }, 4000);
}
 function procesarRespuestaAjax(data, id) {
  if (data.res == 'error') {
    toastem.error("Disculpe los datos no han sido modificado por fabor intente nuevamente gracias!!");
  } else {
    var b = $('#loadingg');
    b.button("loadingg");
    setTimeout(function() {
      b.button("reset");
      $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
    }, 1000);
     setTimeout(function() {
      $("#pagosdeuda").hide();
      $("#alertasadd").fadeOut(1500);
      $("#collapseExample").collapse('hide');
      $("#cheque_tercero").val('').trigger("change");
      reload_table();
      pdf_exporte('pago', id);
    }, 2000);
  }
}
 $('#pagosdeuda').submit(function(e) {
  e.preventDefault();
   var totalParcial = $('#Totalparclal').val();
  if (totalParcial < 1) {
    mostrarMensajeError("Es necesario ingresar algun Monto!!");
    return;
  }
  var id = $('#id').val();
  var urlAjax;
   if (save_method == 'add' && parseFloat(totalParcial) >= parseFloat($('#monto').val())) {
    toastem.success("Pagar todo");
    urlAjax = "DeudaEmpresa/pagar_todo";
  } else {
    toastem.success("Pagar Parcial");
    urlAjax = "DeudaEmpresa/pagar_parcial";
  }
   $.ajax({
    url: urlAjax,
    type: "POST",
    data: $(this).serialize(),
    cache: false,
  })
  .done(function(data) {
    procesarRespuestaAjax(data, id);
  })
  .fail(function() {
    toastem.error("Disculpe hubo un error los datos no se an modificado");
  })
  .always(function() {
    console.log("complete");
  });
});

        $( "#cuenta_bancaria,#cheque_tercero,#afabor" ).select2( {
              allowClear: true,
              placeholder: 'Buscar',
              width: null,
              theme: "bootstrap"
            } );
        $('#fecha_pago').datetimepicker({
          minDate: new Date(),
               format: 'DD-MM-YYYY',
          disabledHours: [0, 1, 2, 3, 4,] ,
          enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
        });

    function delete_(id,id2,id3,id4)
     {
     Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "Deuda_empresa/ajax_delete",
                  type: "POST",
                  dataType: "JSON",
                  data: {id: id,id2: id2,id3: id3,id4: id4},
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                  $('#clicpagada').click();

               })
                .fail(function() {
                  toastem.error("Error al intentar borrar");
                });
          Swal.fire("Eliminado!", "Empleado ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
});

</script>
