<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 

<script type="text/javascript">
var table;
var save_method;
$( "#DEV,#Compra" ).addClass( "active" );
$( "#DEV,#D_E_V" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
  function format ( d ) {
      var id     =  d.slice(4) ;
      $.ajax({
      type : 'POST',
      url: "<?php echo base_url('index.php/CDevoluciones/detale');?>/"+id,
      dataType: 'json',
      })
      .done(function(data) {
        if (data) {
          $.each(data, function(index, val) { 
           var sub ='';
           if (val.Descuento != '') {
            sub = (parseInt(val.Cantidad) * (parseInt(val.Precio)) );
           }else{
            sub = (parseInt(val.Cantidad) * parseInt(val.Precio));
           }
            $('#'+id).append('<tr class="success"><td>'+val.Cantidad+'</td><td>'+val.Nombre+'</td><td>'+formatNumber.new(val.Precio)+' ₲.</td><td>'+formatNumber.new(val.mo)+'</td><td>'+val.es+'</td><td>'+formatNumber.new(sub)+' ₲.</td><td><div class="pull-right hidden-phone"><a class="btn btn-danger btn-xs" href="javascript:void(0);" title="Hapus" onclick="delete_('+val.id+','+val.Estado+','+val.Cantidad+','+val.Motivo+','+val.id2+','+val.del+','+val.Precio+','+val.id6+')"><i class="fa fa-trash-o"></i></a></div></td></tr>');
          });
        }
      });
      return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+id+'">'+
          '<tr class="danger">'+
               '<td>Cantidad</td>'+
               '<td>Nombre</td>'+
               '<td>Precio</td>'+
               '<td>Motivo</td>'+
               '<td>Estado</td>'+
               '<td>Subtotal</td>'+
                '<td>Accion</td>'+
          '</tr>'+
      '</table>';
  }
    $(function() {

        $('#tipooccion').change(function() {
        var tipoOpcion = $(this).val();
        var fechaPagoGroup = $('#fecha_pago_group');
        alert(tipoOpcion);        
        if (tipoOpcion === '10') { // Si la opción seleccionada es "Mercadería Devuelta y Cobro Posterior"
            fechaPagoGroup.show();
        } else {
            fechaPagoGroup.hide();
        }
    });



    table = $('#tabla_CD').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/CDevoluciones/ajax_list'); ?>",
            "type": "POST"
        },

        "columns": [
            {
                "targets":  [ -1], // ultimass columnas
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
            // { "data": "5" },
        ],
        "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ]]
      });
        $('#tabla_CD tbody').on('click', 'td.details-control', function () {

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

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Devolver');
    $('#tabla_CD').show();
    }


$(function() {
  $( "#add" ).click(function() {
    var b = $('#loading');
    $( "#detalle" ).load( "<?php echo base_url('index.php/CDevoluciones/loader');?>/"+'1' );
    var atr = $('#accion').attr('class');
    if (atr == 'fa fa-plus-square') {
      $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
       save_method = 'add';
       $('#devolver')[0].reset();
       $(".proveedor,.Comprobante").val('').trigger("change");
       $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,#alerta').html("").css({"display":"none"});
           $('#tabla_CD').hide();
    }else{
      $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Devolver');
          $('#tabla_CD').show();
     save_method = '';
    }
  });

    /**
     * [description]
     * @param  {[type]} )
     */
    $(function() {
      $('#devolver').submit(function(e) {
      var b = $('#loading');
      $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,#alerta').html("").css({"display":"none"});
        var url = "<?php echo base_url('index.php/CDevoluciones/ajax_add'); ?>";
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                          success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error") 
                          { 
                              b.button("reset");
                              if (json.proveedor) {
                                 $(".PRO").append(json.proveedor).show(); // mostrar validation de iten
                              }
                               if (json.Comprobante) {
                                 $(".COMP").append(json.Comprobante).show(); // mostrar validation de iten
                              }
                               if (json.tipooccion) {
                                 $(".TIPO").append(json.tipooccion).show(); // mostrar validation de iten
                              }
                              if (json.mov) {
                                 $(".mov").append(json.mov).show(); // mostrar validation de iten
                              }
                          }
                          else
                          { 
                             $(b).button('loading'), setTimeout(function() {
                                b.button("reset");
                                   $('#alerta').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();

                            }, 1000);
                            setTimeout(function() {
                                    $("#alerta").fadeOut(1500);
                                    save_method = 'add';
                                    $('#devolver')[0].reset();
                                    Limpiar(1);
                                    reload_table(); // recargar la tabla automaticamente
                           },2000);
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


  $('#condicion,#Estado').change(function(event) {
    if ( $(this).val() == '2') {
        $('#contenCuotas').show();
        $('#Estado').val('2')
    }else{
        $('#contenCuotas').hide();
        $('#Estado').val('0')
    }
  });

  $('#Estado').change(function(event) {
       if ($(this).val() == '2') {
        $('#condicion').val('2');
        $('#contenCuotas').show();
      }else{
        $('#condicion').val('1');
        $('#contenCuotas').hide();
      }
  });

  $('.proveedor').change(function(event) {
    var id = $(this).val();
    if (id == '') {id = 0;}
       $( ".Comprobante" ).load( "<?php echo base_url('index.php/CDevoluciones/list_comprobante');?>/"+id);
       $( "#detalle" ).load("<?php echo base_url('index.php/CDevoluciones/loader');?>/"+'1');
  });
  $( ".proveedor,.orden,.Comprobante,.select2" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );
    $( "#seartt,#seat2,#seat3" ).click( function() {
          $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
        });

     $('.Comprobante').change(function(event) {
        var element = $(this).val().split(',');
        var id = element[0];
        $('#descuento').val(element[1]);
        $('#fletes').val(element[2]);
        $('#id').val(element[0]);
        var nombre;
        if (id == '') {
           var nombre = localStorage.getItem("id");
           if (nombre == '') {
             toastem.success("no hay datos todavia");
           }else{
                $( "#detalle" ).load("<?php echo base_url('index.php/CDevoluciones/loader');?>/"+'1');
           }
        }else{
          localStorage.setItem("id", id);
            if (id !==  undefined && id !== null) {
              $.ajax({
                  url : "<?php echo base_url('index.php/CDevoluciones/agregar_item'); ?>/"+id ,
                  type: "POST",
                  dataType: "JSON",
              })
              .done(function(data) {
              if (data !== false && data !== null) {
                 $( "#detalle" ).load("<?php echo base_url('index.php/CDevoluciones/loader');?>");
                 $(".productos").val('').trigger("change");
                toastem.abrir(data+ ' '+"Articulo agregador");
              }else{
                toastem.cerrar('Sin resultado');
              }
              })
              .fail(function() {
                console.log("error");
              })
              .always(function() {
                console.log("complete");
              });

            }
        }

      });
});
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
            url : "<?php echo base_url('index.php/Compra/delete_item');?>/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
               $( "#detalle" ).load( "<?php echo base_url('index.php/CDevoluciones/loader');?>" );
            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    function update_rowid(id) {
      var val      = $("#"+id).val();
      // toastem.success(val);
      if (val !== '' && val !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('index.php/CDevoluciones/update_rowid'); ?>/"+id,
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
                 $( "#detalle" ).load( "<?php echo base_url('index.php/CDevoluciones/loader');?>" );
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

    function Limpiar(id) {
      $( "#detalle" ).load( "<?php echo base_url('index.php/CDevoluciones/loader');?>/"+id );
      $(".Comprobante,.proveedor").val('').trigger("change");
     $('#Estado').val('0');
    }

    function delete_(id,id2,id3,id4,id5,del,pre,id6)
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
                  url : "<?php echo base_url('index.php/CDevoluciones/ajax_delete'); ?>",
                  type: "POST",
                  dataType: "JSON",
                  data: {id: id,id2: id2,id3: id3,id4:id4,id5:id5,del:del,pre:pre,id6:id6},
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                  reload_table();

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

</script>
