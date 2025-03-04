<script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
//    $(function() {
//         $("[data-mask]").inputmask();
//     });

    let motopagar = 0;
let final = 0;
let pagoEfectivo = 0;
let pagoCeque = 0;
let pagoTarjeta = 0;
let saldoFavor = 0;

var bandera = true;
var banderas = true;

let Cliente;
let table;
let save_method;

const $imagenContenedor = $('.img-thumbnail');
const $tablaProductos = $('#tablaProductos');

const ruta_de_la_imagen = "<?=base_url('bower_components/uploads/')?>";
const srcImagenNoDisponible = ruta_de_la_imagen + 'imgnull.jpg';

let typingTimer = null;
const doneTypingInterval = 500;

const cache = {};

let timeout = null;


$(function() {


$('#campoBusqueda').on('input', function() {
    clearTimeout(timeout);
    var term = $(this).val();
    timeout = setTimeout(function() {
        if (term.trim() !== '') {
            $.ajax({
                url: "Venta/select2remote",
                method: 'GET',
                delay: 250,
                data: {
                    q: term
                    // Eliminamos la referencia a $('.Cliente').val()
                },
                dataType: 'json',
                success: function(data, params) {
                    const cacheKey = params.term; // Eliminamos la referencia a $('.Cliente').val()
                    cache[cacheKey] = data.items;
                    params.page = params.page || 1;
                    if (data.items.length > 0) {
                        cargarContenido('Venta/loader', 'detalle');
                        $('#campoBusqueda').val('');
                    } else {
                        toastem.error('Sin resultado');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } 
    }, 25);
});

$("#ClienteSearch").select2({
    ajax: {
        url: 'Cliente/select2remote',
        dataType: 'json',
        delay: 250,
        data: function (params) {
        return {
            q: params.term, // Término de búsqueda
            page: params.page // Página actual
        };
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
              results: $.map(data.items, function (item) {
                return {
                  id: item.id,
                  text: item.full_name
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
    minimumInputLength: 5,
    theme: 'bootstrap'
    }).change(function(event) {
    if ($(this).val()) {
      var id = $(this).val();
      if (id == '') {id = 0;}
         $( ".orden" ).load( "Venta/list_orden/"+id);
         if (!$.isEmptyObject(id)) {
                   $('[name="comprobante"]').focus();
         }

    }
    $(this).next('.select2-container').css('width', '-webkit-fill-available');

  });






    
    //    $('.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,.DIR').html("").css({"display":"none"});
    //                 setTimeout(function() {
    //         $('[name="productos"]').select2("open");
    //     }, 300);


    /**
     * [description]
     * @param  {[type]} )
     */
    $(function() {
      cargarContenido('Venta/loader/1', 'detalle');
        $( "select:not(#Estado,#cuotas)" ).select2( {
        allowClear: true,
        placeholder: 'Busca y Selecciona',
        width: null,
        theme: "bootstrap"
        } );
        $( "#V_T_A, #Venta, #VTA" ).addClass( "active text-red" );
        $( "#accion" ).addClass( "fa fa-plus-square" );
        $('#atajos').show();
        $('#validat, #fletes').mask('000.000.000.000.000', {reverse: true});
        $(".Cliente").val('').trigger("change");
        // $('.productos').focus();

        $('#vender').submit(function(e) {
              let mm = $('#montofinal').val();
              motopagar = (mm + '').replace(/[^0-9]/g, '');
              if (motopagar  == final) {
                  var Estado = $('#Estado').val();
                  if (Estado == 0) {
                      $('#Venta_submit')[0].reset();
                      $('#ParcialE,#ParcialC,#ParcialT').html('');
                      $('.hidden').val('');
                      $('#tabefectivo').tab('show');
                      $('#numcheque').removeAttr('required');
                      $("#compra_submit,#modal-header").show();
                      $('#modal-id').modal('show');
                      totalparcial();
                      reloadcheque();
                      $('#deudapagar').val(motopagar);
                      $('#m_total,#spanmontopagar,#spanmontopagarchque,#spanmontopagartar,#spanmontopagarfa').html(formatNum(motopagar));
                      Cliente = $('#Cliente').val();
                      $('.controlajustar').hide();
                      save_method = 'add_add';
                      setTimeout(() => {
                        $( "#EF1" ).focus();
                      }, 500)

                  }else{
                    $serialize = $(this).serialize();
                    add($serialize);
                  }
              }else{
                Swal.fire('Campos Monto no coincides con el Capo Monto Final');
              }

            e.preventDefault();
        });
  /**
   * 
   */
  function add(data) {
    $.ajax({
      url: "Venta/ajax_add",
      type: 'POST',
      dataType: 'json',
      data: data,
      success: function(json) {
        if (json.res == "error") 
                          {
                              if (json.Cliente) {
                                 $(".PRO").append(json.Cliente).show(); // mostrar validation de iten
                              }
                               if (json.comprobante) {
                                 $(".COMP").append(json.comprobante).show(); // mostrar validation de iten
                              }
                               if (json.orden) {
                                 $(".PR").append(json.orden).show(); // mostrar validation de iten
                              }
                              if (json.montofinal) {
                                 $(".FINAL").append(json.montofinal).show(); // mostrar validation de iten
                              }
                               if (json.tipoComprovante) {
                                 $(".TIPO").append(json.tipoComprovante).show(); // mostrar validation de iten
                              }
                               if (json.fecha) {
                                 $(".FECHA").append(json.fecha).show(); // mostrar validation de iten
                              }
                              if (json.inicial) {
                                 $(".INIT").append(json.inicial).show(); // mostrar validation de iten
                              }
                               if (json.condicion) {
                                 $(".COND").append(json.condicion).show(); // mostrar validation de iten
                              }
                               if (json.cuotas) {
                                 $(".CUO").append(json.cuotas).show(); // mostrar validation de iten
                              }
                               if (json.fletes) {
                                 $(".FLE").append(json.fletes).show(); // mostrar validation de iten
                              }
                              if (json.descuento) {
                                 $(".desc").append(json.descuento).show(); // mostrar validation de iten
                              }
                              if (json.Direccion) {
                                 $(".DIR").append(json.Direccion).show(); // mostrar validation de iten
                              }
                               if (json.observaciones) {
                                 $(".OBSER").append(json.observaciones).show(); // mostrar validation de iten
                              }
                          }
                          else
                          {
                            if (Estado == 0) {

                                     $(".NOM,.TE,.RUC,#alertasadd,.aaa,.eee,#alertasadd,.alerter").html("").css({"display":"none"});
                                     $("#Venta_submit,#modal-header").show();
                                     $('#numcheque').removeAttr('required');
                                     $('#modal-id').modal('show');
                                     save_method = 'add_add';
                            }else{

                          var b = $('#loading');
                          b.button("loading"), setTimeout(function() {
                              b.button("reset");
                              $('#alertasad').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                          }, 1000)

                                                  setTimeout(function() {
                                                  $("#alertasad").fadeOut(1);
                                                  $("#pagosdeuda").hide(); // CERRAMOS  EL FORMULARIO
                                                  $("#cheque_tercero").val('').trigger("change"); 
                                                  save_method = 'add';
                                                  $('#vender')[0].reset();
                                                  reload_table(); // recargar la tabla automaticamente
                                                  $( "#num,#comprobante" ).val(parseFloat($( "#comprobante" ).val())+parseFloat(1));
                                                  $( "#Ticket" ).val(parseFloat($( "#Ticket" ).val())+parseFloat(1));
                                                  Limpiar(1);
                                             },2000);
                            }
                         }
      },
      error: function(xhr, status, error) {
        Swal.fire('Disculpe, existió un problema');
      },
      complete: function() {
        console.log("complete");
      }
    });
  }




      // $('#vender').submit(function(e) {
      // $('#alertasad,.desc,.PRO,.COMP,.PR,.FINAL,.TIPO,.FECHA,.INIT,.COND,.CUO,.FLE,.OBSER,.DIR').html("").css({"display":"none"});
      // $('#c,#t,#s,#Cheque,#Tarjeta,#fabor').removeClass('active');
      // $('#e,#Efectivo').addClass('active');
      // $('#Venta_submit')[0].reset();
      // $('#ParcialE,#ParcialC,#ParcialT').html('');
      // $('.hidden').val('');
      // var Estado = $('#Estado').val();
      // var Cliente = $('[name="Cliente"]').val();
      // $('[name="Cliente"]').val(Cliente).trigger("change");
      //   $( "#fabor" ).load( "<?php echo base_url('index.php/Venta/formapago');?>/"+4+'/'+Cliente );
      //   $( "#piesss" ).load( "<?php echo base_url('index.php/Venta/formapago');?>/"+5);

      //   e.preventDefault();
      // })


    });




    $( '#checControl' ).on('change', function() {
      let direcEnvio = $('#direcEnvio');
      let fletes = $('#fletes');
      let direccion = $('#Direccion');

      if ($(this).is(':checked')) {
        $(this).prop('value', '1');
        direcEnvio.removeClass('hidden');
        fletes.prop('disabled', false);
        direccion.prop('disabled', false).prop('required', true);
      } else {
        $(this).prop('value', '0');
        direcEnvio.addClass('hidden');
        fletes.prop('disabled', true);
        direccion.prop('disabled', true).prop('required', false).val('');
      }
    });

  $('#condicion,#Estado').change(function(event) {
    if ( $(this).val() == '2') {
        $('#contenCuotas,#cuotas').val('1').change().show();
        $('#Estado').val('2')
    }else{
        $('#contenCuotas,#cuotas').val('1').change().hide();
        $('#condicion').show();
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

  $('[name="formapago"]').change(function(event) {
    if ($(this).val() == 2 || $(this).val() == 3) {
     $('[name="Cliente"]').attr('required','required');
    }else{
     $('[name="Cliente"]').removeAttr('required');
    }
  });

    $('[name="tipoComprovante"]').change(function(event) {
      if ($(this).val() == 1) {
      $('[name="Cliente"]').attr('required','required');
      }else{
      $('[name="Cliente"]').removeAttr('required');
      }
    });




      $( "#seartt,#seat2,#seat3,.Cliente" ).click( function() {
        $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
      });


      $('.dsssss').change(function() {
       if ($(this).val()) {
        var element =  $(this).val().split(',') ;
        var val = $("."+element[0]).val();
        var id      =  element[0];
        if (val == undefined) {
          val = '1';
        }
        if (id !==  undefined && id !== null && id !== '') {
          $(".productos").val('').trigger("change").focus();
          $.ajax({
              url : "<?php echo base_url('index.php/Venta/agregar_item'); ?>",
              type: "POST",
              dataType: "JSON",
              data: {
                val:  val,
                id:   element[0],
                name: element[1],
                precio:element[2],
                iva:  element[4],
                max:  element[5],
                des:  element[9]
              },
          })
        .done(function(data) {
          if (data !== false && data !== null) {
                $("[name='seat2']").click();

               if (data.ress == "error") {
                  toastem.error(data.max);
               }else{
                cargarContenido('Venta/loader', 'detalle');
                   toastem.abrir(data+ ' '+"Articulo Agregado");
               }
          }else{
            toastem.cerrar('Sin resultado');
          }
        })
        .fail(function() {
          toastem.error('Error');
        });
        }
       }
      });

      $('.orden').change(function(event) {

        var id = $(this).val();
        var nombre;
        if (id == '') {
           var nombre = localStorage.getItem("id");
           if (nombre == '') {
             toastem.success("no hay datos todavia");
           }else{
            // cargarContenido('Venta/loader/1', 'detalle');
           }
        }else{
          localStorage.setItem("id", id);
            if (id !==  undefined && id !== null) {
              $.ajax({
                  url : "Venta/agregar_item/"+id ,
                  type: "POST",
                  dataType: "JSON",
              })
              .done(function(data) {
              if (data !== false && data !== null) {
                // cargarContenido('Venta/loader', 'detalle');

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

    function update_rowid(id) {
      var val      = $("#"+id).val();
      if (parseFloat(val) != '' && parseFloat(val) != 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('index.php/Venta/update_rowid'); ?>/"+id,
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
                 $( "#detalle" ).load( "<?php echo base_url('index.php/Venta/loader');?>" );
              }
        })
        .fail(function() {
           toastem.error("error");
        })
        .always(function() {
        });

      }else{
        toastem.error("Cantidad no Soportado");
         $( "#detalle" ).load( "<?php echo base_url('index.php/Venta/loader');?>" );
      }
    }

    function update2_rowid(id) {
      var prec = $("."+id).val();
      var canti = $("."+id).attr("id");
      if (prec !== '' && prec !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "<?php echo base_url('index.php/Orden_Venta/update2_rowid'); ?>/"+id,
          type: "POST",
          dataType: "JSON",
          data: {qty: canti,price: prec},
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
                $( "#detalle" ).load( "<?php echo base_url('index.php/Venta/loader');?>" );
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

    function Limpiar(id) {
      $( "#detalle" ).load( "<?php echo base_url('index.php/Venta/loader');?>/"+id );
      $(".productos,.Cliente,.orden").val('').trigger("change");
       $('#condicion').val('1');
       $('#contenCuotas').hide();
        $('#Estado').val('0');
        $('#fletes,#Direccion').removeAttr('required').attr({disabled: 'disabled'}).val('');
        setTimeout(function() {$("[name='seat2']").click();}, 100);
        
    }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    $('.validat').keyup(function(event) {
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });


    function addclien(arguments) {
      save_method = 'add_cliente';
     $(".NOM,.TE,.RUC,#alertas").html("").css({"display":"none"});
     $("#inserc,.modal-header").show();
     $('#inserc')[0].reset();
     $('#modal-1').modal('show');
     setTimeout(function() {
       $('#nombre').focus();
     }, 500);
    }

$(function () {
$('#inserc').submit(function(e) {
     $(".NOM,.TE,.RUC,#alertas").html("").css({"display":"none"});
  var nom     = $('#nombre').val();
  var telefon = $('#Telefono').val();
  var ruc     = $('#ruc').val();
  var b = $('#loading');
  $.ajax({
    url: "<?php echo base_url('index.php/Orden_venta/insercliente'); ?>",
    type: "POST",
    dataType: "JSON",
    data:  {nom: nom,telefon: telefon,ruc: ruc},
  })
  .done(function(data) {
      if (data.res == 'error') {
        b.button("reset");
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
          $('[name="Cliente"]').append($('<option>', {value:data.id, text:data.nom+'  ('+data.ruc+')'}));
     
           $(b).button('loading'), setTimeout(function() {
              b.button("reset");
              $("#inserc,#mh").hide(); // CERRAMOS  EL FORMULARIO
              $('#alertas').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
          }, 1000)
            setTimeout(function() {
                  $("#inser_aler").fadeOut(1500);
                  $('[name="Cliente"]').val(data.id).trigger("change");
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

$('#Venta_submit').submit(function(e) {
    $('.alerter').hide();
  var Totalparclal = $('#Totalparclal').val();
  var deudapagar = $('#deudapagar').val();
if (save_method == 'add_add' && parseFloat(Totalparclal) >= parseFloat(deudapagar) ) {
  $.ajax({
    url: "<?php echo base_url('index.php/Venta/ajax_add_pago'); ?>",
    type: 'POST',
    dataType: 'json',
    data: $(this).serialize(),
  })
  .done(function(data) {
          var b = $('#loadingg');
          b.button("loadingg"), setTimeout(function() {
              b.button("reset");
              $("#Venta_submit,#modal-header").hide(); // CERRAMOS  EL FORMULARIO
              $('#_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
              $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
          }, 1000)
            setTimeout(function() {
                     $("#_aler").fadeOut(1500);
                     $('#modal-id').modal('hide');
                     $('#vender')[0].reset();
                     reload_table(); // recargar la tabla automaticamente
                    $( "#num,#comprobante" ).val(parseFloat($( "#comprobante" ).val())+parseFloat(1));
                    $( "#Ticket" ).val(parseFloat($( "#Ticket" ).val())+parseFloat(1));
                     $( ".productos" ).load( "<?php echo base_url('index.php/Venta/list_productos');?>/");
                     $("#contenido,.modal-header2").show(); // CERRAMOS  EL FORMULARIO
                     Limpiar(1);
                     save_method = 'add';
                     setTimeout(function() {
                     $("[name='seat2']").click();
                     pdf_exporte('Venta', data);
                    }, 1100);
            },2000);


  })
  .fail(function() {
    toastem.success("Error ");
  })
  .always(function() {
    console.log("complete");
  });

}else{
  $('.alerter').html("Monto es Inferior al Monto Total a Pagar!! ").show();
  $(".alerter").fadeOut(4000);
}


  e.preventDefault();
});

$(this).on('click', '.close', function(event) {
 save_method = 'add';
});

$('.Cliente').change(function(event) {
  if ($(this).val()) {
   setTimeout(function() {$('.productos').focus();}, 100);
  }
});



$(document).on('click', 'button.search-product', function(event) {
    $('#campoSearch').val('');
    $('.todo-list').html('');
    $('#modalSearch').modal("show");
  });

 // Cuando se deja de escribir, realiza la búsqueda
  $('#campoSearch').keyup(function(){
    clearTimeout(typingTimer);
    if ($('#campoSearch').val()) {
      typingTimer = setTimeout(buscarProductos, doneTypingInterval);
    }
  });

  $(document).on('change', '.todo-list input[type="checkbox"]', function() {
      var isChecked = $(this).is(':checked');
      var id = $(this).data('id'); // Capturar el valor del atributo data-id
      var precio = $(this).data('precio'); 
      var name = $(this).data('name'); 
      var iva = $(this).data('iva'); 
      var descuento = $(this).data('descuento'); 
      if (isChecked) {
        $('#detalle').load('Compra/Additem', {
          id: id,
          iva: iva,
          precio: precio,
          nombre: name,
          descuento: descuento,

        });
      }
  });


  // Realiza la búsqueda de productos
function buscarProductos() {
  var searchTerm = $('#campoSearch').val();
  $('#loadingContainer').show();
  $.ajax({
    url: "Compra/campoSearch",
      type: 'POST',
      dataType: 'json',
      data: { searchTerm: searchTerm },
    })
    .done(function(data) {
     // Vaciar la lista de productos
     var html = '';
      data.forEach(function(producto) {
        html += `

    <tr id="${producto.Img}" class="trproduc">
      <td><input type="checkbox" value="" data-descuento="${producto.Descuento}" data-iva="${producto.Iva}" data-name="${producto.full_name}" data-id="${producto.id}" data-precio="${producto.precio}"></td>
      <td>${producto.stock}</td>
      <td><small class="price">${formatPrice(producto.precio)}</small></td>
      <td><span class="text">${producto.full_name}</span></td>
      <td>${producto.CodigoBarra}</td>
    </tr>
     
        `;
      });
      $('.todo-list').fadeOut(300, function() {
        $(this).html(html).fadeIn(300);
       $('#loadingContainer').hide();

      });

    })
    .fail(function() {
      $('#loadingContainer').hide();
      Swal.fire('Disculpe, existió un problema');
    })
    .always(function() {
      console.log("complete");
    });


}



$tablaProductos.on('mouseenter', 'tr.trproduc', function() {
  var imagen = $(this).attr('id');
  var src = imagen && ruta_de_la_imagen ? ruta_de_la_imagen + imagen : srcImagenNoDisponible;

  $imagenContenedor.fadeOut('fast', function() {
    $imagenContenedor.attr('src', src).fadeIn('fast');
  });
});




});



</script>
