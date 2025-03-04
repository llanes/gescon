<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
   $(function() {
        $("[data-mask]").inputmask();
    });
var table;
var save_method;

$( "#BAN,#bancos" ).addClass( "active" );
$( "#B_A_N" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
    $(function() {
    table = $('#tabla_Banco').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Banco/ajax_list'); ?>",
            "type": "POST"
        },

        "columns": [
            {
                "targets":  [ -1 ], // ultimass columnas
                "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
                "className":      'details-control',
                "data":           null,
                "defaultContent": '',

           },
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },
            // { "data": "4" },
        ],
        "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ]]
      });
    $('#tabla_Banco tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            setTimeout(function() {           
            row.child( format(row.data()) ).show();
            tr.addClass('shown');}, 450);

        }
    } );

    });

  function format ( d ) {
      var id     =  d.slice(4) ;
      $.ajax({
      type : 'POST',
      url: "<?php echo base_url('index.php/Banco/detale');?>/"+id,
      dataType: 'json',
      })
      .done(function(data) {
        if (data) {
          $.each(data, function(index, val) { 
           var sub ;
           if (val.PlandeCuenta_idPlandeCuenta > 0) {
             sub = val.Balance_General;
           }else{

             sub = val.ConceptoSalida;
           }
           if (val.NumeroCheque > 0) {
             var NumeroCheque = 'Cheque';
           }else{
            var NumeroCheque  = 'Efectivo';
           }
            $('#'+id).append('<tr class="success"><td>'+NumeroCheque+'</td><td>'+sub+'</td><td>'+formatNumber.new(val.Importe)+' ₲.</td><td>'+val.FechaExpedicion+'</td><td>'+val.Entrada_Salida+' </td></tr>');
          });
        }
      })
      .fail(function(data) {
        toastem.error(data);
      })

              return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+id+'">'+
                  '<tr class="danger">'+
                      '<td>Cheque/Efectivo</td>'+
                      '<td>Plan de Cuenta</td>'+
                      '<td>Importe</td>'+
                       '<td>Fecha Expedicion</td>'+
                       '<td>Entrada Salida</td>'+
                  '</tr>'+
              '</table>';
  }

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;Agregar');
    }


$(function() {
  $( "#add" ).click(function() {
    var atr = $('#accion').attr('class');
    if (atr == 'fa fa-plus-square') {
      $('#collapseExample').addClass('collapse in');

       $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;Cerrar');
       save_method = 'add';
          $('#btnSave').html('Guardar');
       $('#from_Banco')[0].reset();
       $('.mmm,.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
             setTimeout(function() {$('[name="nombre"]').focus()}, 50);
    }else{
      $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;Agregar');
     save_method = '';
           $('#collapseExample').removeClass('collapse in').addClass('collapse');
              $('#btnSave').html('Actualizar');
    }
  });

    $('#from_Banco').submit(function(e) {
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add':
          url = "<?php echo base_url('index.php/Banco/ajax_add'); ?>";  
            break;
          case 'update':
          url = "<?php echo base_url('index.php/Banco/ajax_update'); ?>";
            break;
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                          success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error") 
                          {
                              if (json.nombre) {
                                 $(".ccc").append(json.nombre).show(); // mostrar validation de iten
                              }
                                             if (json.monto) {
                                 $(".mmm").append(json.monto).show(); // mostrar validation de iten
                              }
                               if (json.numero) {
                                 $(".nnn").append(json.numero).show(); // mostrar validation de iten
                              }
                           }
                          else
                          { 
                                              if (save_method == 'add') {
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                                             save_method = 'add';
                                          } else{
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                                 $('#collapseExample').removeClass('collapse in').addClass('collapse');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                                          }
                                                  $("#alertasadd").fadeOut(1500);
                                                  setTimeout(function() {
                                             },1510);
                                          reload_table(); // recargar la tabla automaticamente
                                          Limpiar(1);
                          }
                       },
                        // código a ejecutar si la petición falla;
                        error : function(data) {
                            Swal.fire('Disculpe, existió un problema');
                            console.log('error(jqXHR, textStatus, errorThrown)');
                        },
                    });
        e.preventDefault();
      })
    });


    function Limpiar(id) {
      resetet();
    }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    function resetet() {
           $('#from_Banco')[0].reset();
    }

        function _delete(id)
     {
     Swal.fire({
        title: "Estas seguro?",
        text: "No podrá recuperar el los datos Eliminado!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Banco/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    reload_table(); 
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Banco  ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
    function _edit(id)
    {
       $('#from_Banco')[0].reset();
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
      if (id) {
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/Banco/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              $('[name ="id"]').val(data.idGestor_Bancos);
              $('[name ="nombre"]').val(data.Nombre);
              $('[name ="numero"]').val(data.Numero);
              $('[name ="monto"]').val(data.MontoActivo);
              setTimeout(function() {
              $('#collapseExample').addClass('collapse in');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              $('#btnSave').html('Actualizar');
              },100);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
      }
    }


</script>
