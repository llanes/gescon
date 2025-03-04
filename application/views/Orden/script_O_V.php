<script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Orden_venta; //  VARIABLE PARA LA TABLA  DE DADATABLE
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
    url: "<?php echo base_url('Orden_venta/select2remote'); ?>",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      console.log(data, params);
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


$( ".cliente" ).select2( {
        allowClear: true,
        placeholder: 'Seleccione Cliente',
        width: null,
          theme: "bootstrap"
      } );
  $( "#seartt,#seat2" ).click( function() {
        $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
      });
   });


    $(document).ready(function() 
    {
      $( "#odv,#Orden" ).addClass( "active" );
      $("#inser_aler,#Orden_venta_aler").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#o_d_v" ).addClass( "text-red" );
         tabla_Orden_venta = $('#tabla_Orden_venta').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('Orden_venta/ajax_list'); ?>",
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
        $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>/"+'1' );
        $(".producto,.cliente").val('').trigger("change");
        $(".PROVE,.ENTRE,.ENVI,.ESTA,.OBSER").html("").css({"display":"none"});
        $("#from_Orden_venta").show();
        $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $("#Orden_venta_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Orden_venta')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
   function resetear() {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
          save_method = ''; 
    }
    function Limpiar(id) {
      $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>/"+id );
      $(".producto,.Cliente").val('').trigger("change");

    }

    function _edit(id)
    {
          $(".PROVE,.ENTRE,.ENVI,.ESTA,.OBSER").html("").css({"display":"none"}); 
          $('[name ="changeProduc"]').val('').trigger("change");
          $("#Orden_venta_aler").hide(); // oculto el contenedor de mensaje de confirmacion
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Orden_venta')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('Orden_venta/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                  $('#tituloboton').text(' Cerrar Edicion de Orde');  // Fijar título para arrancar título 
                  $("#from_Orden_venta").show(); 
                  $('[name ="changecliente"]').val(data.id).trigger("change");
                  $('[name ="idOrden"]').val(id);
                  $('[name ="Entrega"]').val(data.entre);
                  $('#Estado').val(data.esta).change();
                  $('[name ="Envio_m"]').val(data.invi);
                  $('#observac').val(data.obser);
                  $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>" );
                  $('#btnSave').text('Actualizar');
                  $( "#collapseExample" ).addClass( "in" );
                  $("#collapseExample").attr('aria-expanded', 'true');
                  $("#collapseExample").removeAttr( 'style' );
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                toastem.error("Error al obtener datos");
            }
        });
    }

    function reload_table()
    {
      tabla_Orden_venta.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Orden_venta').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('Orden_venta/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('Orden_venta/ajax_update'); ?>";
        }

             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario

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
                                            $title = 'Datos Registrado correctamente';
                                          } else{
                                            $title = 'Datos Actualizado correctamente';
                                            save_method == 'add'
                                          }
                                          alertasave($title);
                                          $('#from_Orden_venta')[0].reset(); // restablecer el formulario
                                          Limpiar(1);
                                          reload_table(); // recargar la tabla automaticamente
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
            url : "<?php echo base_url('Orden_venta/delete_item');?>/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {

               $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>" );
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
    function _delete(id)
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
            url : "<?php echo base_url('Orden_venta/delete');?>/"+id,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
                  $( "#collapseExample" ).removeClass( "in" );
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
        var id      = $( "select[name=changeProduc]").val();
        if (id !==  undefined && id !== null && id !== '') {
          $.ajax({
              url : "<?php echo base_url('Orden_venta/agregar_item'); ?>/"+id,
              type: "POST",
              dataType: "JSON",
              cache: false,
          })
        .done(function(data) {
          if (data !== false && data !== null) {
            $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>" );
            toastem.abrir(data+ ' '+"Articulo agregador");
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
    function update_rowid(val,id) {

      if (val !== '' && val !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('Orden_venta/update_rowid'); ?>/"+id,
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
                $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>" );
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
            $('#datetimepicker7').datetimepicker({
               minDate: new Date(),
                   format: 'DD-MM-YYYY',
              disabledHours: [0, 1, 2, 3, 4,] ,
              enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
            });

        });


    function ver_detalles(id) {
      $.ajax({
        url : "<?php echo base_url('Orden_venta/ver_detalles/'); ?>/" + id,
        type: "GET",
      })
      .done(function(data) {
              $( "#view" ).load( "<?php echo base_url('Orden_venta/loader_deta');?>" );
               $("#ttt").html($('#'+id).attr("data-total"));
               $("#fecha").html($('#'+id).attr("data-fecha"));
               $("#envioss").html($('#'+id).attr("data-monto"));
               $("#nom").html($('#'+id).attr("data-nombre"));
               $("#cli").html($('#'+id).attr("data-apellido"));
               $("#tel").html($('#'+id).attr("data-tel"));
               $("#user").html($('#'+id).attr("data-user"));
               $("#ordenid").html('#00'+id);
               $( "#collaje_detalle" ).addClass( "in" );
              $("#collaje_detalle").attr('aria-expanded', 'true');
              $("#collaje_detalle").removeAttr( 'style' );

      })
      .fail(function(res) {
           Swal.fire('Disculpe, existió un problema');
      })
      .always(function(res) {
      });
    }
function addclien(arguments) {
 $(".NOM,.TE,.RUC").html("").css({"display":"none"});
 $("#inserc,.modal-header").show();
 $('#inserc')[0].reset();
 $('#modal-1').modal('show');
}


function inserCliente(arguments) {
  $(".NOM,.TE,.RUC").html("").css({"display":"none"});
  var nom     = $('#nombre').val();
  var telefon = $('#Telefono').val();
  var ruc     = $('#ruc').val();
  $.ajax({
    url: "<?php echo base_url('Orden_venta/insercliente'); ?>",
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
          b.button("loading"), setTimeout(function() {
              b.button("reset");
              $("#inserc,.modal-header").hide(); // CERRAMOS  EL FORMULARIO
              $('#inser_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
              $('.title').text('Datos Registrado correctamente');
          }, 1000)
            setTimeout(function() {
                  $("#inser_aler").fadeOut(1500);
                  $(".cliente").val(data.id).trigger("change");
                  $('#modal-1').modal('hide');
            },2000);
      }
  })
  .fail(function(data) {
    toastem.error('error');
  })
  .always(function(data) {
  });
  
}

</script>
