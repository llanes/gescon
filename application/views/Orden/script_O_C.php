  <script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Orden_compra; //  VARIABLE PARA LA TABLA  DE DADATABLE
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
    url: "<?php echo base_url('Orden_compra/select2remote'); ?>",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page,
        id: $('.proveedor').val()
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

$( ".proveedor" ).select2( {
        allowClear: true,
        placeholder: 'Seleccione Proveedor',
        width: null,
          theme: "bootstrap"
      } );
  $( "#seartt,#seat2" ).click( function() {
        $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
      });
   });


    $(document).ready(function() 
    {
      $( "#odc,#Orden" ).addClass( "active" );
      $("#Orden_aler").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#o_d_c" ).addClass( "text-red" );
         tabla_Orden_compra = $('#tabla_Orden_compra').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('Orden_compra/ajax_list'); ?>",
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
        $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>/"+'1' );
        $(".producto,.proveedor").val('').trigger("change");
        $(".PROVE,.ENTRE,.DEVO,.ESTA,.OBSER").html("").css({"display":"none"});
        $("#from_Orden_compra").show();
        $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $('#from_Orden_compra')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
   function resetear() {
         $('#tituloboton').text(' Agregar Nueva Orden'); // Fijar título para arrancar título 
          save_method = ''; 
    }
    function Limpiar(id) {
      $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>/"+id );
      $(".producto,.proveedor").val('').trigger("change");
    }

    function _edit(id)
    {
          $(".PROVE,.ENTRE,.DEVO,.ESTA,.OBSER").html("").css({"display":"none"});
          $("#Orden_compra_aler").hide(); // oculto el contenedor de mensaje de confirmacion
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Orden_compra')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
          $('#tituloboton').text(' Cerrar Edicion de Orde');  // Fijar título para arrancar título 
          $("#from_Orden_compra").show(); 
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('Orden_compra/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                  $(".proveedor").val(data.id).trigger("change");
                  $('[name ="idOrden"]').val(id);
                  $('[name ="Entrega"]').val(data.entre);
                  $('[name ="Devolucion"]').val(data.devol);
                  $('#Estado').val(data.esta).change();
                  $('#observac').val(data.obser);
                  $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
                  $('#btnSave').text('Actualizar');

                  $( "#collapseExample" ).collapse('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
    }

    function reload_table()
    {
      tabla_Orden_compra.ajax.reload(null,false); //reload datatable ajax 

    }
    $(function() {
      $('#from_Orden_compra').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('Orden_compra/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('Orden_compra/ajax_update'); ?>";
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
                              if (json.Devolucion) {
                                 $(".DEVO").append(json.Devolucion).css({"display":"block"}); // mostrar validation de iten usuario
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
                                          $('#from_Orden_compra')[0].reset(); // restablecer el formulario
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
            url : "<?php echo base_url('Orden_compra/delete_item');?>/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {

               $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
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
            url : "<?php echo base_url('Orden_compra/delete');?>/"+id,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
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
      $('.proveedor').change(function() {
        /* Act on the event */
        var id      = $( "select[name=changeprove]").val();
        changeprove = id;
      });
    });
    $(function() {
      $('.producto').change(function() {
        /* Act on the event */
        var id      = $( "select[name=changeProduc]").val();
        if (id == null) return false;
        if (id !==  undefined && id !== '' ) {
          $.ajax({
              url : "<?php echo base_url('Orden_compra/agregar_item'); ?>/"+id,
              type: "POST",
              dataType: "JSON",
              cache: false,
          })
        .done(function(data) {
          if (data !== false) {
            $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
            toastem.abrir(data+ ' '+"Articulo agregador");

          }else{
            toastem.cerrar('Sin resultado');
          }
          $('[name="changeProduc"]').val('').trigger("change").focus().select2("open");
        })
        .fail(function() {
          toastem.error('Error');
        });
        } 
      });
    });
    /**
     * [listaTodos description]
     * @param  {[type]} argument [description]
     * @return {[type]}          [description]
     */
    function listaTodos(argument) {
      if (argument == 1) {
              if (changeprove !==  undefined && changeprove !== '') {
        $.ajax({
              url : "<?php echo base_url('Orden_compra/listaTodos'); ?>/"+changeprove,
              type: "POST",
              dataType: "JSON",
              cache: false,
        })
        .done(function(data) {
          if (data !== false) {
             $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
             toastem.abrir(data+ ' '+"Articulo agregador");
          }else{
             $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
            toastem.cerrar('Sin resultado');
          }
        })
        .fail(function() {
          toastem.error('Error');
        });
      } else{
        toastem.error('No has seleccionado Proveedor');
      }
      }
    }
    /**
     * [listaAlertas description]
     * @param  {[type]} arguments [description]
     * @return {[type]}           [description]
     */
    function listaAlertas(ID) {
      if (ID == 1) {
        if (changeprove !==  undefined && changeprove !== '') {
        $.ajax({
              url : "<?php echo base_url('Orden_compra/listaAlertas'); ?>/"+changeprove,
              type: "POST",
              dataType: "JSON",
              cache: false,
        })
        .done(function(data) {
          if (data !== false) {
           $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
           toastem.abrir(data+ ' '+"Articulo agregador");
          }else{
             $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
            toastem.cerrar('Sin resultado');
          }
        })
        .fail(function() {
          toastem.error('Error');
        });
      }else{
        toastem.error('No has seleccionado Proveedor');
      }
      }
    }

    /**
     * [update_rowid description]
     * @param  {[type]} id [description]
     * @return {[type]}    [description]
     */
    function update_rowid(val,id) {
      if (val !== '' && val !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('Orden_compra/update_rowid'); ?>/"+id,
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
                $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
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
   function update2_rowid(price,id,qty,iva,tyf) {
      if (price !== '' && price !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('Orden_compra/update2_rowid'); ?>",
          type: "POST",
          dataType: "JSON",
          data: {
            price : price,
            id    : id,
            qty   : qty,
            iva   : iva,
            tyf   : tyf
          },
        })
        .done(function(json) {
            if (json.res == 'error') {
              if (json.qty) {
                toastem.error(json.qty);
              }
              if (json.price) {
                toastem.error(json.price);
              }

            }else{
                $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
              }
        })
        .fail(function() {
           toastem.error("error");
        })
        .always(function() {
        });

      }else{
        toastem.error("Monto no Soportado");
      }
    }
    $(function() {
      $( "#target" ).click(function() {
         var id      = $( "#target").val();
          toastem.error('No has seleccionado Proveedor');
      });
    });

    function ver_detalles(id) {
      $.ajax({
        url : "<?php echo base_url('Orden_compra/ver_detalles/'); ?>/" + id,
        type: "GET",
      })
      .done(function(data) {
              $( "#view" ).load( "<?php echo base_url('Orden_compra/loader_deta');?>" );
               $("#ttt").html($('#'+id).attr("data-total"));
               $("#fecha").html($('#'+id).attr("data-fecha"));
               $("#nom").html($('#'+id).attr("data-nombre"));
               $("#tel").html($('#'+id).attr("data-tel"));
               $("#user").html($('#'+id).attr("data-user"));
               $("#ordenid").html('#00'+id);
              $( "#collaje_detalle" ).collapse('show');

      })
      .fail(function(res) {
           Swal.fire('Disculpe, existió un problema');
      })
      .always(function(res) {
      });
      
    }
</script>
