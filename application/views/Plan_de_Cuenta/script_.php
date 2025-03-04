<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
   $(function() {
        $("[data-mask]").inputmask();
    });
var table;
var save_method;

$( "#PL,#Contabilidad" ).addClass( "active" );
$( "#P_L" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
    $(function() {
    table = $('#tabla_Plan').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_list'); ?>",
            "type": "POST"
        },

        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });
    });

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Producir');
    }


$(function() {
  $( "#add" ).click(function() {
    var atr = $('#accion').attr('class');
    if (atr == 'fa fa-plus-square') {
      $('#collapseExample').collapse('show');
       $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
       save_method = 'add';
          $('#btnSave').html('&nbsp;Guardar');
       $('#from_Plan')[0].reset();
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
    }else{
      $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
     save_method = '';
           $('#collapseExample').collapse('hide');
              $('#btnSave').html('&nbsp;Actualizar');
    }
  });

    $('#from_Plan').submit(function(e) {
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add':
          url = "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_add'); ?>";  
            break;
          case 'update':
          url = "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_update'); ?>";
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
                              if (json.Codigo) {
                                 $(".ccc").append(json.Codigo).show(); // mostrar validation de iten
                              }
                               if (json.nombre) {
                                 $(".nnn").append(json.nombre).show(); // mostrar validation de iten
                              }
                               if (json.Categorias) {
                                 $(".ttt").append(json.Categorias).show(); // mostrar validation de iten
                              }
                           }
                          else
                          { 
                                              if (save_method == 'add') {
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                                             save_method = 'add';
                                          } else{
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                                 $('#collapseExample').collapse('hide');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Producir');
                                          }
                                                  $("#alertasadd").fadeOut(1500);
                                                  setTimeout(function() {
                                             },1510);
                                          reload_table(); // recargar la tabla automaticamente
                                          Limpiar(1);
                          }
                       },
                        // código a ejecutar si la petición falla;
                        error : function(xhr, status) {
                            Swal.fire('Disculpe, existió un problema');
                            console.log('error(jqXHR, textStatus, errorThrown)');
                        },
                    });
        e.preventDefault();
      })
    });


  $( "#selectrequired" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );





    function Limpiar(id) {
      resetet();
    }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    function resetet() {
           $('#from_Plan')[0].reset();
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
                  url : "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_delete'); ?>/"+id,
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
          Swal.fire("Eliminado!", "Plan de Cuenta ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
    function _edit(id)
    {
       $('#from_Plan')[0].reset();
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
      if (id) {
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              $('[name ="id"]').val(data.idPlandeCuenta);
              $('[name ="Codigo"]').val(data.Codificacion);
              $('[name ="nombre"]').val(data.Balance_General);
              $('[name ="Categorias"]').val(data.Control).change();
              setTimeout(function() {
              $('#collapseExample').collapse('show');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              $('#btnSave').html('&nbsp;Actualizar');
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
