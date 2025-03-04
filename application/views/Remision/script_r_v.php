
<script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Remisiones; //  VARIABLE PARA LA TABLA  DE DADATABLE
    var changeprove;
    $(function(){
function formatRepo (repo) {
  
  if (repo.loading) return repo.text;
  console.log(repo.loading);
  var markup = "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__avatar'><img src='<?php echo base_url("bower_components/uploads") ?>/"+repo.avatar_url+"' /></div>" +
      "<div class='select2-result-repository__meta'>" +
      "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";

  if (repo.Marca) {
    markup += "<div class='select2-result-repository__description'>" + repo.Marca + "</div>";
  }

  markup += "<div class='select2-result-repository__statistics'>" +
    "<div class='select2-result-repository__forks'><i class='fa fa-barcode'></i> " + repo.Codigo + " </div>" +
    "<div class='select2-result-repository__stargazers'><i class='fa fa-money'></i> " + repo.precio + " </div>" +
    "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.Cantidad_A + "</div>" +
    "<div class='select2-result-repository__percent'><i class='fa fa-stack-overflow'></i> " + repo.Iva + "%</div>" +

    "</div>" +
    "</div></div>";

  return markup;
}

function formatRepoSelection (repo) {
  return repo.full_name || repo.text;
}

$("#js-example-data-ajax").select2({

  ajax: {
    url: "<?php echo base_url('Remisiones/select2remote'); ?>",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page,
        id: $('#Estado').val()
      };
    },
    processResults: function (data, params) {
      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; },
  minimumInputLength:2 ,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection,
  theme: "bootstrap",
    allowClear: true,
    placeholder: 'Busca',
    width: null
});

$( ".cliente,#Estado" ).select2( {
        allowClear: true,
        placeholder: 'Selecciona',
        width: null,
          theme: "bootstrap"
      } );
  $( "#seartt,#seat2" ).click( function() {
        $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
      });
   });

  
    $(document).ready(function() 
    {
      $( "#ndr,#Orden" ).addClass( "active" );
      $("#inser_aler,#Orden_venta_aler").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#n_d_r" ).addClass( "text-red" );
         tabla_Remisiones = $('#tabla_Remisiones').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('Remisiones/ajax_list'); ?>",
            "type": "POST"
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],
         "order": [[ 0, 'desc' ], [ 1, 'desc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ]]
      });
    });

     function _add()
    {
        $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>/"+'1' );
        $(".producto,.cliente,#Estado").val('').trigger("change");
        $(".PROVE,.ENTRE,.ENVI,.ESTA,.OBSER,#alerta").html("").css({"display":"none"});
        $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $('#from_Remision')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
   function resetear() {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
          save_method = ''; 
    }
    function Limpiar(id) {
      $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>/"+id );

      toastem.cerrar("Datos Restablecidos");
    }

    $('#Estado').change(function(event) {
      if ($(this).val()==6) {
       $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>/"+1 );
      }
    });

    function _edit(id)
    {
          $(".PROVE,.ENTRE,.ENVI,.ESTA,.OBSER,#alerta").html("").css({"display":"none"}); 
          $('[name ="changeProduc"]').val('').trigger("change");
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Remision')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('Remisiones/ajax_edit/'); ?>/"+id ,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                  $('#tituloboton').text(' Cerrar Edicion de Orde');  // Fijar título para arrancar título 
                  $("#from_Remision").show(); 
                  $('[name ="changecliente"]').val(data.id).trigger("change");
                  $('[name ="idOrden"]').val(id);
                  $('[name ="Entrega"]').val(data.entre);
                  $('#controval').val(data.esta);
                  $('#Estado').val(data.esta).change();
                  $('#observac').val(data.obser);
                  $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>" );
                  $('#btnSave').text('Actualizar');
                  $( "#collapseExample" ).collapse('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                toastem.error("Error al obtener datos");
            }
        });
    }

    function reload_table()
    {
      tabla_Remisiones.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Remision').submit(function(e) {
        var b = $('#loading');
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('Remisiones/ajax_add'); ?>";
        }
        else
        {
            if(save_method == 'update') 
            {
             url = "<?php echo base_url('Remisiones/ajax_update'); ?>";
            }else{
              toastem.error("Error");
            }
          
        }

             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                        beforeSend: function (){

                          $(b).button('loading')
                        },
                        success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                            $(".CA,.DE").html("").css({"display":"none"});
                          if (json.res == "error") 
                          {
                              if (json.changeprove) {
                                 $(".PROVE").append(json.changeprove).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                               if (json.Entrega) {
                                 $(".ENTRE").append(json.Entrega).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Envio_m) {
                                 $(".ENVI").append(json.Envio_m).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Estado) {
                                 $(".ESTA").append(json.Estado).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.observac) {
                                 $(".OBSER").append(json.observac).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }
                          else
                          { 
                               
                                  if (save_method == 'add') {
                                     $tt = '<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>';
                                  } else{
                                     $tt = '<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>';
                                  }
                                          if (save_method == 'add') {
                                            $title = 'Datos Registrado correctamente';
                                          } else{
                                            $title = 'Datos Actualizado correctamente';
                                            save_method == 'add'
                                          }
                                          alertasave($tt);
                                          $('#from_Remision')[0].reset(); // restablecer el formulario
                                          Limpiar(1);
                                          reload_table(); // recargar la tabla automaticamente
                                           b.button("reset");

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

    /**
     * [delete_rowid description]
     * @param  {[type]} rowid [description]
     * @return {[type]}       [description]
     */
    function delete_rowid(rowid)
    {
        Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('Remisiones/delete_item');?>/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {

               $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>" );
            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    /**
     * [delete_rowid description]
     * @param  {[type]} rowid [description]
     * @return {[type]}       [description]
     */
    function _delete(id,id2)
    {
        Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('Remisiones/delete');?>",
            type: "POST",
            cache: false,
            data: {id: id , id2: id2}, // serilizo el formulario
            success: function(data)
            {
                  $( "#collapseExample" ).collapse('hide');
                  $('#tituloboton').text(' Agregar Nueva Orden');
                  save_method = ''; 
                  reload_table();
            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    $(function() {
      $('.producto').change(function() {
        /* Act on the event */
        if ($(this).val()) {
        var element =  $(this).val().split(',') ;
        var id      =  element[0];
        var val      =  element[1];
        }

        if (id !==  undefined && id !== null && id !== '') {
             $(".producto").val('').trigger("change").focus();
          $.ajax({
              url : "<?php echo base_url('Remisiones/agregar_item'); ?>/"+id,
              type: "POST",
              dataType: "JSON",
              data: {data: val}
          })
        .done(function(data) {
          if (data !== false && data !== null) {
            $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>" );
            toastem.abrir(data+ ' '+"Articulo agregador");
            setTimeout(function() { $('.producto').select2('open');}, 300);
          }else{
            toastem.cerrar('Sin resultado');
          }
        })
        .fail(function() {
          toastem.error('Error');
        });
        } 
      });
    });
    /**
     * [update_rowid description]
     * @param  {[type]} id [description]
     * @return {[type]}    [description]
     */
    function update_rowid(id) {
      var val      = $("#"+id).val();
      if (val !== '' && val !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('Remisiones/update_rowid'); ?>/"+id,
          type: "POST",
          dataType: "JSON",
          data: {qty: val},
        })
        .done(function(json) {
            if (json.res == 'error') {
              if (json.qty) {
                toastem.error(json.qty);
              }
            }else{
                $( "#detalle" ).load( "<?php echo base_url('Remisiones/loader');?>" );
              }
        })
        .fail(function() {
           toastem.error("error");
        })
        .always(function() {
        });

      }else{
        toastem.error("Cantidad no Soportado");
      }
    }
    // datetime picker end
     $(function () {
            $('#datetimepicker6').datetimepicker({
              minDate: new Date(),
                   format: 'DD-MM-YYYY',
              disabledHours: [0, 1, 2, 3, 4,] ,
              enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
            });
        });


    function ver_detalles(id) {
      $.ajax({
        url : "<?php echo base_url('Remisiones/ver_detalles/'); ?>/" + id,
        type: "GET",
      })
      .done(function(data) {
              $( "#view" ).load( "<?php echo base_url('Remisiones/loader_deta');?>" );
               $("#ttt").html($('#'+id).attr("data-total"));
               $("#fecha").html($('#'+id).attr("data-fecha"));
               $("#envioss").html($('#'+id).attr("data-monto"));
               $("#nom").html($('#'+id).attr("data-nombre"));
               $("#cli").html($('#'+id).attr("data-apellido"));
               $("#tel").html($('#'+id).attr("data-tel"));
                switch ($('#'+id).attr("data-user")) {
                  case '3':
                    var row = ' Nota de Entrada Productos ';
                    break;
                  case '4':
                    var row = ' Nota de Salida Productos ';
                    break;
                  case '5':
                    var row = ' Nota de Devolucion Productos ';
                    break;
                  case '6':
                    var row = ' Entrada Productos En Produccion ';
                    break;
                  case '7':
                    var row = ' Sin Accion ';
                    break;
                }
               $("#c_v").html(row);
               $("#ordenid").html('#00'+id);
               $( "#collaje_detalle" ).collapse('show');

      })
      .fail(function(res) {
           Swal.fire('Disculpe, existió un problema');
      })
      .always(function(res) {
      });
    }
function addclien(arguments) {
 save_method = 'add_cliente';
 $(".NOM,.TE,.RUC").html("").css({"display":"none"});
 $("#inserc,.modal-header").show();
 $('#inserc')[0].reset();
 $('#modal-1').modal('show');
}
$(function () {
$('#inserc').submit(function(e) {
     $(".NOM,.TE,.RUC,#alertas").html("").css({"display":"none"});
  var nom     = $('#nombre').val();
  var telefon = $('#Telefono').val();
  var ruc     = $('#ruc').val();
  $.ajax({
    url: "<?php echo base_url('Remisiones/insercliente'); ?>",
    type: "POST",
    dataType: "JSON",
    data:  {nom: nom,telefon: telefon,ruc: ruc},
  })
  .done(function(data) {
      if (data.res == 'error') {
        if (data.nom) {
          $('.NOM').append(data.nom).css({"display":"block"});
        }
        if (data.telefon) {
          $('.TE').append(data.telefon).css({'display' : 'block'});
        }
        if (data.ruc) {
          $('.RUC').append(data.ruc).css({'display' : 'block'});
        }
      }else{
           $('.cliente').append($('<option>', {value:data.id, text:data.nom+'  ('+data.ruc+')'}));
          var b = $('#loading');
           $(b).button('loading'), setTimeout(function() {
              b.button("reset");
              $("#inserc,#mh").hide(); // CERRAMOS  EL FORMULARIO
              $('#alertas').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
          }, 1000)
            setTimeout(function() {
                  $("#inser_aler").fadeOut(1500);
                  $('.cliente').val(data.id).trigger("change");
                  $('#modal-1').modal('hide');
                  save_method = 'add';
                    setTimeout(function() {
                        $('[name="productos"]').select2("open");
                    }, 300);
            },2000);
      }
  })
  .fail(function(data) {
    toastem.error('error');
  })
  .always(function(data) {
  });
  
  e.preventDefault();
});
});
</script>
