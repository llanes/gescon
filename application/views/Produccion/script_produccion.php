<script type="text/javascript">

var table;
var save_method;
$( "#PRO,#Producto" ).addClass( "active" );
$( "#PRO,#P_R_O" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
    $(function() {
    table = $('#tabla_Produccion').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "Produccion/ajax_list",
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
            { "data": "4" },
            { "data": "5" },
            // { "data": "6" },




        ],
        "order": [[ 0, 'desc' ], [ 1, 'desc' ],[ 2, 'desc' ],[ 3, 'desc' ]]
      });

    $('#tabla_Produccion tbody').on('click', 'td.details-control', function () {
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


  function format ( d ) {
      var id     =  d.slice(6) ;
      // toastem.success(id);
      $.ajax({
      type : 'POST',
      url: "Produccion/detalle/"+id,
      dataType: 'json',
      })
      .done(function(data) {
        if (data) {
          // toastem.success(data);
          $.each(data, function(index, val) { 
           var sub ='';
          sub = (parseInt(val.Cantidad) * parseInt(val.Precio));

            $('#'+id).append('<tr class="success"><td>'+val.Cantidad+'</td><td>'+val.Nombre+'</td><td>'+formatNumber.new(val.Precio)+' ₲.</td><td>'+formatNumber.new(sub)+' ₲.</td></tr>');
          });
        }
      });
      return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+id+'">'+
          '<tr class="danger">'+
              '<td>Cantidad</td>'+
              '<td>Nombre</td>'+
              '<td>Precio</td>'+
               '<td>Subtotal</td>'+
          '</tr>'+
      '</table>';
  }
    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Producir');
    }


  $( "#add" ).click(function() {
      <?php if (($this->session->userdata('idcaja'))) { ?>
            var atr = $('#accion').attr('class');
            if (atr == 'fa fa-plus-square') {
              $('#collapseExample').collapse('show');
               $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
               save_method = 'add';
               $('#Producir')[0].reset();
               $(".productos,.proveedor,.Ingredientes").val('').trigger("change");
               $('.Ingredientes').focus();
               $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,.DIR,#alerta').html("").css({"display":"none"});
               $( "#detalle" ).load( "Produccion/load/"+1 );
            //    $( ".productos" ).load( "Produccion/list_productos/"+1);
            //    $( ".Ingredientes" ).load( "Produccion/list_productos");

            }else{
              $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Producir');
             save_method = '';
                   $('#collapseExample').collapse('hide');
            }
      <?php } else { ?>
           alertacaja();
      <?php } ?>
  });


      $(window).keypress(function(event) {
          if (event.which == 77){
           $("button[name=save]").click();
          }else{
            return  true;
          }
          event.preventDefault();
      });
    $('#Producir').submit(function(e) {
                                   var b = $('#loading');
    $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,.DIR,#alerta').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add':
          url = "Produccion/ajax_add";  
            break;
          case 'update':
          url = "Produccion/ajax_update";
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
                            b.button("reset");
                              if (json.proveedor) {
                                 $(".PR").append(json.proveedor).show(); // mostrar validation de iten
                              }
                               if (json.idProduct) {
                                 $(".COMP").append(json.idProduct).show(); // mostrar validation de iten
                              }
                               if (json.Estado_produccion) {
                                 $(".TIPO").append(json.Estado_produccion).show(); // mostrar validation de iten
                              }
                              if (json.cantidad) {
                                 $(".COU").append(json.cantidad).show(); // mostrar validation de iten
                              }
                           }
                          else
                          { 

                             $(b).button('loading'), setTimeout(function() {
                                b.button("reset");
                                  if (save_method == 'add') {
                                      $data = 'Datos Registrado correctamente';
                                  } else{
                                      $data = 'Datos Actualizado correctamente';
                                  }
                                      let timerInterval
                                      Swal.fire({
                                        // position: 'top-end',
                                        type: 'success',
                                        title: $data,
                                        timer: 2000,
                                         onBeforeOpen: () => {
                                            Swal.showLoading()
                                            timerInterval = setInterval(() => {
                                              Swal.getContent().querySelector('strong')
                                                .textContent = (Swal.getTimerLeft() / 1000)
                                                .toFixed(0)
                                            }, 100)
                                          },
                                          onClose: () => {
                                            clearInterval(timerInterval)
                                          }
                                      });
                            }, 1000);
                              setTimeout(function() {
                                    save_method = '';Limpiar(1);
                                    reload_table();
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
    });


      
    $('#selectproveedor,#selectproductos,#selectIngredientes,#selectrecetas').select2({

        ajax: {
            url: function () {
                return $(this).data('url');
            },
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                q: params.term, // Término de búsqueda
                page: params.page, // Página actual
                control: $(this).data('id')
            };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                results: $.map(data.items, function (item) {
                    return {
                    id: item.id,
                    text: item.full_name,
                    precio: item.precio,
                    img: item.Img,
                    maximo: item.Cantidad_A,
                    unidad: item.Unidad,
                    medida: item.Medida,
                    marca: item.Marca
                    };
                }),
                pagination: {
                    more: false
                }
                };
            },
            
            cache: true
        },
        placeholder: 'Busca y Selecciona',
        width: null,
        allowClear: true,
        minimumInputLength: 5,
        theme: 'bootstrap'
    });




  $( ".select" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );

      $('.productos').change(function (e) {
        var $this = $(this);
        if ($this.val()) {
         var selectedData = $this.select2('data')[0];
         $('#idProduct').val(selectedData.id)
         $('#Montoidproduct').val(selectedData.precio)
        }

      });

    $('.Ingredientes').change(function() {
    var $this = $(this);
    if ($this.val()) {
        var selectedData = $this.select2('data')[0];
        var id = selectedData.id || '1';
        var max = save_method == 'update' ? $('.'+id ).attr('data-val') : selectedData.maximo;
        var data = {
            id:     selectedData.id,
            name:   selectedData.text,
            precio: selectedData.precio,
            img:    selectedData.img,
            iva:    selectedData.iva,
            maximo: max,
            unidad: selectedData.unidad,
            medida: selectedData.medida,
            marca:  selectedData.marca,
        };
        if (id) {
            $.ajax({
                url: "Produccion/agregar_item/" + id,
                type: "POST",
                dataType: "html",
                data: data
            })
            .done(function(response) {
              if (!response.ress) {
                // toastem.abrir(response);
                $this.val('').trigger('change').focus();
                  toastem.abrir('Articulo Agregado');
                  // Actualizar el carrito con la vista del carrito actualizada
                  $("#detalle").html(response);
     
              } else {
                toastem.cerrar(response.max);
              }
            })
            .fail(function() {
              toastem.error('Error');
            });

            } 
        }
    });
    $('#selectrecetas').change(function() {
    var $this = $(this);
    if ($this.val()) {
        var selectedData = $this.select2('data')[0];
        var id = selectedData.id || '1';
        $('#cantidad_producir').val(selectedData.maximo);

        if (id) {
            $.ajax({
                url: "Produccion/add_allIngrediente/" + id,
                type: "POST",
                dataType: "html",

            })
            .done(function(response) {
              if (!response.ress) {

                  toastem.abrir('Articulos Agregados');
                  // Actualizar el carrito con la vista del carrito actualizada
                  $("#detalle").html(response);
     
              } else {
                toastem.cerrar(response.max);
              }
            })
            .fail(function() {
              toastem.error('Error');
            });

          } 
      }
  });



});
    function delete_rowid(rowid)
    {

        if (rowid) {
          $.ajax({
            url : "Produccion/delete_item/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
 
                $("#detalle").html(data);

            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }

    }

    function update_rowid(id) {
      var val      = $("#"+id).val();
      if (parseFloat(val) != '' && parseFloat(val) != 0 ) {
        var parametro = {}
        $.ajax({
          url : "Produccion/update_rowid/"+id,
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
                 $( "#detalle" ).load( "Produccion/load" );
              }
        })
        .fail(function() {
           toastem.error("error");
        })
        .always(function() {
        });

      }else{
        toastem.error("Cantidad no Soportado");
        $( "#detalle" ).load( "Produccion/load" );
      }
    }


    function Limpiar(id) {
      resetet();
    }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    $(document).ready(function() {
      $('#estado_produccion').on('change', function(e) {
        if ($(this).val() == 2) {
          $('#cantidad_producir').prop('required', true);
        } else {
          $('#cantidad_producir').prop('required', false).val('');
        }
      });
    });
      function resetet() {
        var cantidadProducir = $('#cantidad_producir');
        var productos = $(".productos");
        var proveedor = $(".proveedor");
        var ingredientes = $(".Ingredientes");
        cantidadProducir.removeAttr('required').val('');
        save_method = 'add';
        $('#Producir')[0].reset();
        productos.val('').trigger("change");
        proveedor.val('').trigger("change");
        ingredientes.val('').trigger("change");
        ingredientes.focus();
        $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,.DIR').empty().css({"display":"none"});
        $( "#detalle" ).load( "Produccion/load/"+'1' );
      }

        function _delete(id,id2,idp)
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
                  url : "Produccion/ajax_delete",
                  type: "POST",
                  dataType: "JSON",
                  data: {id: id , idp: idp,date: id2}
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    reload_table(); 
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Produccion ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
    function editar_datos(id,idp,esta,fecha,can,iddp)
    {
       $('#Producir')[0].reset();
       $( ".productos" ).load( "Produccion/list_productos/"+1);
       $( ".Ingredientes" ).load( "Produccion/list_productos");
      if (id) {
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "Produccion/ajax_edit/" + iddp,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              setTimeout(function() {
              $('.productos').val($('#'+id).val()).change();
              $('[name ="Estado_produccion"]').val(esta).change();
              $('#idProduct').val(id)
              $('#iddp').val(iddp)
              $('#date').val(fecha);
              $('[name ="proveedor"]').val(idp).change();
              $( "#detalle" ).load( "Produccion/load" );
              $('#collapseExample').collapse('show');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              },100);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
      }
    }
    function volver_producir(id,idp,esta,fecha,can,iddp)
    {
       $('#Producir')[0].reset();
       $( ".productos" ).load( "Produccion/list_productos/"+1);
       $( ".Ingredientes" ).load( "Produccion/list_productos");
      if (id) {
        save_method = 'add'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "Produccion/ajax_edit/" + iddp,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              setTimeout(function() {
              $('.productos').val($('#'+id).val()).change();
              $('[name ="Estado_produccion"]').val(esta).change();
              if (esta == 2 ) {
                if (can > 0) {
                 $('#cantidad').val(can)
                }else{
                 $('#cantidad').val('0');
                }

              }
              $('#idProduct').val(id)
              $('#date').val(fecha);
              $('[name ="proveedor"]').val(idp).change();
              $( "#detalle" ).load( "Produccion/load" );
              $('#collapseExample').collapse('show');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              },100);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
      }
    }
    function addprovee(arguments) {
      save_method = 'add_cliente';
     $(".NOM,.TE,.RUC,#alertas").html("").css({"display":"none"});
     $("#inserc,.modal-header").show();
     $('#inserc')[0].reset();
     $('#modal-1').modal('show');
     setTimeout(function() {
       $('#Ruc').focus();
     }, 500);
    }

$(function () {
  $('#inserc').submit(function(e) {
       $(".RS,.Ruc,.VE,#alertas").html("").css({"display":"none"});
    $.ajax({
      url: "Produccion/ajaxadd",
      type: "POST",
      dataType: "JSON",
      data:  $(this).serialize(),
    })
    .done(function(data) {
        if (data.res == 'error') {
          if (data.Ruc) {
            $('.Ruc').append(data.Ruc).css({"display":"block"});
          }
          if (data.Razon_Social) {
            $('.RS').append(data.Razon_Social).css({'display' : 'block'});
          }
          if (data.Vendedor) {
            $('.VE').append(data.Vendedor).css({'display' : 'block'});
          }
        }else{
            $('[name="proveedor"]').append($('<option>', {value:data.id, text:data.nom+'  ('+data.ruc+')'}));
            var b = $('#loading');
             $(b).button('loading'), setTimeout(function() {
                b.button("reset");
                $("#inserc,#mh").hide(); // CERRAMOS  EL FORMULARIO
                $('#alertas').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
            }, 1000)
              setTimeout(function() {
                    $("#inser_aler").fadeOut(1500);
                    $('[name="proveedor"]').val(data.id).trigger("change");
                    $('#modal-1').modal('hide');
                    save_method = 'add';
                      // setTimeout(function() {
                      //     $('[name="productos"]').select2("open");
                      // }, 300);
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

  $('#myproduccion').submit(function(e) {
      var b = $('#loading_gt');
    $.ajax({
      url: "Produccion/close_my_produc",
      type: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
    })
    .done(function(data) {
           $(b).button('loading_gt'), setTimeout(function() {
              b.button("reset");
              $("#myproduccion,#mh").hide(); // CERRAMOS  EL FORMULARIO
              $('#alertasmyproduc').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
              table.ajax.reload();
               $("#alertasmyproduc").fadeOut(1800);
          }, 1000)
           setTimeout(function(){ $('#mymodal').modal('hide');},1800)
           save_method ='';
           reload_table();
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
    e.preventDefault();
  });

  $('#general_produccion').submit(function(e) {
          $('.alertaterminar').hide();
          var b = $('#loading_t');
          var montototal = $('#montototal').val();
          var Montoapagar = $('#Montoapagar').val();
      if (save_method == 'add_add_' && parseFloat(Montoapagar) >= parseFloat(montototal) ) 
      {
        $.ajax({
          url: "Produccion/close_in_produc",
          type: 'POST',
          dataType: 'json',
          data: $(this).serialize(),
        })
        .done(function(data) {
          if (data.error == 'error') {
             b.button("reset");
          }else{
           $(b).button('loading_t'), setTimeout(function() {
              b.button("reset");
              $("#general_produccion").hide(); // CERRAMOS  EL FORMULARIO
              $('#alertaterminar').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
              reload_table();
               $("#alertaterminar").fadeOut(1800);
          }, 1000)
           setTimeout(function(){ $('#generalterminar').modal('hide');},1800)
           save_method ='';
          }
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        

      }
      else 
      {
         $(b).button('loading_t'), setTimeout(function() {
            if (Montoapagar < 1) {
            $('.alerter').html("Ingrese algun Monto!! ").show();
             $(".alerter").fadeOut(4000);
            } else {
            $('.alerter').html("Monto es Inferior al Monto Total a Pagar!! ").show();
             $(".alerter").fadeOut(4000);
            }
              b.button("reset");
          },10);

      }


  e.preventDefault();
  });

});



function terminar(id1,id2,id3,id4,id5,id6,id7){

<?php if ($this->session->userdata('idcaja')){ ?>
  if (id2>1) {
       save_method = 'add_add_';
      $('#tabpanel').hide();
      $('#Deudarestante,#Totalp,#ParcialE,#ParcialC,#ParcialT,#Parcialf,#vuelto,#Totalp').html('');
      $('#pagoparcial1,#pagoparcial2,#pagoparcial3,#pagoparcial4,#Deudarestante,#vueltototal').val('');
       $('#limpp').tab('show'); 
       $("#alertaterminar").html("").css({"display":"none"});

       $('#general_produccion')[0].reset();
       $("#general_produccion,#mh").show(); 
       $('#id_Producto').val(id1);
       $('#id_Detalle').val(id6);
       $('#MontoTotal').val(id7);
  

       $('#cantidadProduc').val(id5);

        $('#idproveedore').val(id2);
      $( "#deudaingrediente" ).load( "Produccion/ListarTodas/"+id2);
       $( "#cheque_tercero" ).load( "DeudaEmpresa/cuenta_bancaria");
       $('#generalterminar').modal('show');
      setTimeout(function(){
        $('#montototal').val(id7).focus().keyup();
      },500)
  }else{
      save_method = 'add_add_';
       $("#alertasmyproduc").html("").css({"display":"none"});
       $('#myproduccion')[0].reset();
       $("#myproduccion,#mh").show(); 
       $('#idProducto').val(id1);
       $('#idDetalle').val(id6);
       $('#Monto_Total').val(id7);
       $('#cantidadProducido').val(id5);
       $('#mymodal').modal('show');

  }
      <?php } else { ?>
           alertacaja();
      <?php } ?>
  
}
$(function() {


   $('.validat').keyup(function(event) {
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

   $('#montototal').keyup(function(event) {
      // var este =+ parseFloat($(this).val());
      if ($(this).val()<1) {
         var valor = '0';
         $('#parcial1').val(valor);
         $('#tabpanel').hide();
         totalparcial();
      }else{
               $('#parcial1').val($(this).val());
               $('#tabpanel').show();
      }

          totalparcial();

    });



});
function cambio(id) {
  var pagoparcial=0;
     var val = $("#EF"+id).val();
     var monto = $("#EF"+id).attr('data-monto');
     if (val>0) {
        $('#Moneda'+id).val(id);
        $('#cam'+id).val(monto);
     }else{
       $('#Moneda'+id).val('');
       $('#cam'+id).val('');
     }

     if (monto>0 && val>0 ) {
        var total = operaciones(val,monto,'*');
     }else{
      if (val > 0) {
        var total = val;
      }else{
        var total = '';
      }

     }
     $('#cm'+id).val(formatNumber.new(total));
     $('#ex'+id).val(total);
     $('#ParcialE').html(formatNumber.new(recorrer()));
     $('#pagoparcial1').val(recorrer());
     totalparcial();
}

function recorrer(arguments) {
                    var val = $('#val').val();
                    var resultVal = 0;
                    for (var i = 1; i <= val; i++) {
                      var res = $('#ex'+i).val();
                      if (res>0) {
                         resultVal += parseFloat(res);

                      }
                    }
                    return resultVal;
                    // toastem.success(resultVal);

}

function totalparcial() {
  var  total=0,pagoparcial = 0 , totalrestados=0;
  total = $('#parcial1').val();
  var parcial4 = $('#pagoparcial4').val();
  for (var i = 1; i < 5; i++) {
    var id = $('#pagoparcial'+i).val()
    if (id > 0) {
      pagoparcial += parseFloat(id);
    }
  }
  if (total > 1) {
    if (pagoparcial > 1) {
      if (pagoparcial > total) {
          totalrestados += operaciones(parseFloat(pagoparcial),parseFloat(total),'-');
          if (parseInt(parcial4) > parseInt(total)) {
               $('#vuelto').html('');
               $('#vueltototal').val('');  
               $('#Deudarestante').html(formatNumber.new(totalrestados));
               $('#valor').html('');
               $('#Totalp').html('');
          }else{
               $('#vuelto').html(formatNumber.new(totalrestados));
               $('#vueltototal').val(totalrestados);
               $('#Deudarestante').html('');
               $('#valor').html('');
               $('#Totalp').html('');
          }

          $('#Montoapagar').val(pagoparcial);
      } else {
          totalrestados += operaciones(parseFloat(total),parseFloat(pagoparcial),'-');
         $('#Deudarestante,#vuelto').html('');
         $('#vueltototal').val('');
         $('#Totalp').html(formatNumber.new(totalrestados));
         $('#Montoapagar').val(pagoparcial);
      }

      return totalrestados;
    } else {
      totalrestados += parseFloat(total);
      $('#Totalp').html(formatNumber.new(totalrestados));
      $('#Montoapagar').val('');
      $('#Deudarestante,#ParcialE,#ParcialC,#ParcialT,#Parcialf,#vuelto').html('');
      $('#Montoapagar,#pagoparcial1,#pagoparcial2,#pagoparcial3,#pagoparcial4,#vueltototal').val('');
      return totalrestados;
    }

  } else {
      $('#Deudarestante,#Totalp,#ParcialE,#ParcialC,#ParcialT,#Parcialf,#vuelto').html('');
      $('#Montoapagar,#pagoparcial1,#pagoparcial2,#pagoparcial3,#pagoparcial4,#vueltototal').val('');
      return totalrestados;
  }
}

function reloadcheque(argument) {
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');
}


$("#numcheque").keyup(function () {
   var id      = $(this).val();
   if (id > 00 ) {
       $("#Cliente,#efectivo,#fecha_pago").removeAttr('disabled');
       $("#Cliente,#efectivo,#fecha_pago").attr('required','required');
   } else{
        $("#Cliente,#efectivo,#fecha_pago").attr('disabled','disabled');
        $("#Cliente,#efectivo,#fecha_pago").removeAttr('required');

   }
});

$("#checkboxbanca").on( 'change', function() {
  if( $(this).is(':checked') ) {
           $("#cuenta_bancaria").removeAttr('disabled');
           $("#cuenta_bancaria").attr('required','required');
  } else {
          $("#cuenta_bancaria").attr('disabled','disabled');
          $("#cuenta_bancaria").removeAttr('required');
          $('[name="cuenta_bancaria"]').val('').trigger("change");
  }
});


   $('#cheque_tercero').change(function() {
    var  resultado=0,  pagoparcial = 0;
    var myArray = [ ];
    var myArray2 = [ ];
     var efecti = $('#efectivo').val() ;
              $("#cheque_tercero :selected").map(function(i, el) {
                    var element = $(el).val().split(',');
                    myArray.push(element[0]);
                    myArray2.push(element[1]);
                });
                $.each( myArray, function( key, val ) {
                    if (val >= 0) {
                      resultado += parseFloat(val);
                    }
                });
               if (efecti > 0) {
                 var temporal =  operaciones(resultado,efecti,'+');
                }else{
                 var temporal  = resultado;
                }
              $('#ParcialC').html(formatNumber.new(temporal));
              $('#pagoparcial2').val(temporal);
              $('#Acheque_tercero').val(myArray2);
              $('#Acheque').val(myArray);
              totalparcial()

  });
  $('#deudaingrediente').change(function() {
    var  resultado=0;
    var myArray = [ ];
    var myArray2 = [ ];
              $("#deudaingrediente :selected").map(function(i, el) {
                    var element        = $(el).val().split(',');
                    myArray.push(element[1]);
                    myArray2.push(element[0]);
                });
                $.each( myArray, function( key, val ) {
                    if (val >= 0) {
                      resultado += parseFloat(val);
                    }
                });

                if (resultado > 0) {
                    $('#Parcialf').html(formatNumber.new(resultado));
                    $('#pagoparcial4').val(resultado);
                     $('#matris').val(myArray);
                     $('#matriscuanta').val(myArray2);

                } else {
                     $('#Parcialf').html('');
                     $('#pagoparcial4').val('');
                     $('#matris').val('');
                     $('#matriscuanta').val('');
                }
                totalparcial();


     });



    $('#efectivo').keyup(function(event) {
          var  resultado=0,pagoparcial=0;
          var myArray = [ ];
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()) {
        $('#numcheque').attr('required','required');
         var valor = $(this).val();
         $("#cheque_tercero :selected").map(function(i, el) {
              var element        = $(el).val().split(',');
              myArray.push(element[0]);
          });
          $.each( myArray, function( key, val ) {
              if (val >= 0) {
                resultado += parseFloat(val);
              }
          });
         var temporal =  operaciones(resultado,valor,'+');
         $('#ParcialC').html(formatNumber.new(temporal));
         $('#pagoparcial2').val(temporal);
         totalparcial()

      }else{
         $('#numcheque').removeAttr('required');
         $("#cheque_tercero :selected").map(function(i, el) {
              var element        = $(el).val().split(',');
              myArray.push(element[0]);
          });
          $.each( myArray, function( key, val ) {
              if (val >= 0) {
                resultado += parseFloat(val);
              }
          });
          if (resultado>0) {
             $('#ParcialC').html(formatNumber.new(resultado));
             $('#pagoparcial2').val(resultado);
               for (var i = 1; i < 5; i++) {
                    var id = $('#pagoparcial'+i).val()
                    if (id > 0) {
                      pagoparcial += parseFloat(id);
                    }
               totalparcial()
                }
               if (pagoparcial<1) {
                        $('#agregar_cuenta').removeAttr('checked');
                        $('#vuelto').html('');
                        $('#vueltototal').val('');
                        $("#controlajustar").hide();
                        $('#si_no').val('');
                        $('#ajustado').val('');
                        $('#valor').html('');
                         $('#rerer').html('');

                 }
          }else{
             $('#ParcialC').html('');
             $('#pagoparcial2').val('');
             totalparcial()
          }

      }
    });

   $('#efectivoTarjeta').keyup(function(event) {
    var pagoparcial=0;
      var este =+ parseFloat($(this).val());
      if ($(this).val()<1) {
        $(this).val('')
      }
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()) {
         var valor = $(this).val();
         $('#ParcialT').html(formatNumber.new(valor));
         $('#pagoparcial3').val(valor);
               for (var i = 1; i < 5; i++) {
                    var id = $('#pagoparcial'+i).val()
                    if (id > 0) {
                      pagoparcial += parseFloat(id);
                    }
                }
               totalparcial();
         if (pagoparcial<0) {
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#vuelto').html('');
                  $('#vueltototal').val('');
                  $("#controlajustar").hide();
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                   $('#rerer').html('');
           }
      }else{
         $('#ParcialT').html('');
         $('#pagoparcial3').val('');
            totalparcial();
      }
    });

</script>
