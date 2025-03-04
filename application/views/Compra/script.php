
<script type="text/javascript">
let motopagar = 0;
var final = 0;
let pagoEfectivo = 0;
let pagoCeque = 0;
let pagoTarjeta = 0;
let saldoFavor = 0;

var bandera = true;
var banderas = true;

let proveedor;
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


$('#campoBusqueda').on('input', function() {
    clearTimeout(timeout);
    var term = $(this).val();
    timeout = setTimeout(function() {
        if (term.trim() !== '') {
            $.ajax({
                url: "<?php echo base_url('Compra/select2remote'); ?>",
                method: 'GET',
                delay: 250,
                data: {
                    q: term,
                    id: $('.proveedor').val()
                },
                dataType: 'json',
                success: function(data, params) {
                    const cacheKey = params.term + '_' + $('.proveedor').val();
                    cache[cacheKey] = data.items;
                    params.page = params.page || 1;
                    if (data.items.length > 0) {
                        cargarContenido('Compra/loader', 'detalle');
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

  $("#proveedor").select2({
    ajax: {
        url: 'Proveedor/select2remote',
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
         $( ".orden" ).load( "<?php echo base_url('index.php/Compra/list_orden');?>/"+id);
         if (!$.isEmptyObject(id)) {
                   $('[name="comprobante"]').focus();
         }

    }
    $(this).next('.select2-container').css('width', '-webkit-fill-available');

  });



///////////////////////////////////////////////////////////////////////////////////////////////////// 
$(function() {
  // $( "#detalle" ).load("Compra/loader/1");
  cargarContenido('Compra/loader/1', 'detalle');
  $( "#COMPRA, #Compra, #_COM" ).addClass( "active text-red" );
  $( "#accion" ).addClass( "fa fa-plus-square" );
  $('#atajoscompra').show();
 $('#validat, #fletes').mask('000.000.000.000.000', {reverse: true});
 setTimeout(function() {
            $('#campoBusqueda').focus();
 }, 1000);
  $('#comprar').submit(function(e) {
        let mm = $('#montofinal').val();
        motopagar = (mm + '').replace(/[^0-9]/g, '');
        final = $('#finalcarrito').val();
        if (motopagar  == final) {
            var Estado = $('#Estado').val();
            if (Estado == 0) {
                $('#compra_submit')[0].reset();
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
                proveedor = $('#proveedor').val();
                $('.controlajustar').hide();
                save_method = 'add_add';
                setTimeout(() => {
                  $( "#EF1" ).focus();
                }, 500)

            }else{
              add($(this).serialize());
            }
        }else{
          Swal.fire('Campos Monto no coincides con el Capo Monto Final');
        }

      e.preventDefault();
  });


  function showError(selector, message) {
      $(selector).html(message).show();
      setTimeout(function() {
          $(selector).fadeOut();
      }, 5000);
  }

  /**
   * 
   */
  function add(data) {
    $.ajax({
      url: "Compra/ajax_add",
      type: 'POST',
      dataType: 'json',
      data: data,
      success: function(json) {
        if (json.res == "error") {
          // Ejemplo de uso
          if (json.proveedor) {
              showError(".PRO", json.proveedor);
          }
          if (json.comprobante) {
              showError(".COMP", json.comprobante);
          }
          if (json.orden) {
              showError(".PR", json.orden);
          }
          if (json.montofinal) {
              showError(".FINAL", json.montofinal);
          }
          if (json.tipoComprovante) {
              showError(".TIPO", json.tipoComprovante);
          }
          if (json.fecha) {
              showError(".FECHA", json.fecha);
          }
          if (json.inicial) {
              showError(".INIT", json.inicial);
          }
          if (json.condicion) {
              showError(".COND", json.condicion);
          }
          if (json.cuotas) {
              showError(".CUO", json.cuotas);
          }
          if (json.fletes) {
              showError(".FLE", json.fletes);
          }
          if (json.descuento) {
              showError(".desc", json.descuento);
          }
          if (json.observaciones) {
              showError(".OBSER", json.observaciones);
          }
          if (json.timbrado) {
              showError(".TIM", json.timbrado);
          }
          if (json.vence) {
              showError(".VEN", json.vence);
          }
          if (json.virtual) {
              showError(".VEN", json.virtual);
          }

        } else {
            alertasave('Datos Registrado correctamente');
          setTimeout(function() {
            save_method = 'add';
          }, 1510);
          $('#comprar')[0].reset();
          Limpiar(1);
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

$('#condicion,#Estado').change(function(event) {
  var $this = $(this);
  if ( $this.val() == '2') {
    $('#contenCuotas,#cuotas').prop('value', '1').change().show();
    $('#idformapago').hide();
    $('#Estado').prop('value', '2');
  } else {
    $('#contenCuotas,#cuotas').prop('value', '1').change().hide();
    $('#idformapago,#condicion').show();
    $('#Estado').prop('value', '0');
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




  $( ".select" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
  } );

  $( "#seartt,#seat2,#seat3" ).click( function() {
      $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
  });

  $('.sumar').keyup(function(event) {
    totalfinal()    
  });

  $('.orden').change(function(event) {
    var id = $(this).val();
    var nombre;
    if (id == '') {
       var nombre = localStorage.getItem("id");
       if (nombre == '') {
         toastem.success("no hay datos todavia");
       }else{
             cargarContenido('Compra/loader/1', 'detalle');
       }
    }else{
      localStorage.setItem("id", id);
        if (id !==  undefined && id !== null) {
          $.ajax({
              url : "Compra/agregar_item/"+id ,
              type: "POST",
              dataType: "JSON",
          })
          .done(function(data) {
          if (data !== false && data !== null) {
              cargarContenido('Compra/loader', 'detalle');
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

  $('.validate').click(function() {
         let num = $('#finalcarrito').val();
         num > 0 ? $(this).val(formatPrice(num)) : $(this).val('');

    });

    $('.validat').keyup(function(event) {
       let value = (this.value + '').replace(/[^0-9]/g, '');
       value > 1 ?  $(this).val(formatPrice(value)) : false;
       
    });



$(".multi").select2({
          theme: "bootstrap",
});        
$("#cuenta_bancaria").select2({
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
});   

// Este código maneja el evento de cambio del elemento con el id "cheque_tercero"
$('#cheque_tercero').change(function() {
  // Obtener el valor de efectivo
  const efecti = parseFloat($('#efectivo').val()) || 0;
  
  // Obtener los elementos seleccionados del select "cheque_tercero"
  const elementosSeleccionados = $("#cheque_tercero :selected");
  
  // Utilizar reduce para calcular la suma de los elementos seleccionados
  const resultado = elementosSeleccionados.toArray().reduce((sum, el) => {
    const element = $(el).val().split(',');
    const val = parseFloat(element[0]);
    return val > 0 ? sum + val : sum;
  }, 0);
  
  // Calcular el valor temporal dependiendo del valor de efecti
  const temporal = efecti > 1 ? operaciones(resultado, efecti, '+') : resultado;

  // Actualizar los elementos HTML
  const $parcialC = $('#ParcialC');
  const $parcial2 = $('#parcial2');
  const $toChek = $('#to_chek');
  $parcialC.html(formatNum(temporal));
  $parcial2.val(temporal);
  
  // Crear arrays con valores seleccionados
  const myArray = elementosSeleccionados.toArray().map(el => $(el).val().split(',')[0]);
  const myArray2 = elementosSeleccionados.toArray().map(el => $(el).val().split(',')[1]);
  
  $('#Acheque_tercero').val(myArray2);
  $('#Acheque').val(myArray);
  $toChek.val(resultado);

  // Llamar a la función totalparcial con el argumento 1
  totalparcial(1);
});

//////////////////////////////////////////

$('#efectivotxt').on('keyup', function(event) {
  let valor = (this.value + '').replace(/[^0-9]/g, '');
  let resultado = ($('#to_chek').val() > 0) ? $('#to_chek').val() : 0;
  let $parcialC = $('#ParcialC');
  let $parcial2 = $('#parcial2');
  let $efectivo = $('#efectivo');

  if (valor) {
    $('#numcheque').prop('required', true);
    let temporal = operaciones(resultado, valor, '+');
    $parcialC.text(formatNum(temporal));
    $parcial2.val(temporal);
    $efectivo.val(temporal);
    totalparcial(1);
  } else {
    if (resultado > 0) {
      $parcialC.text(formatNum(resultado));
      $parcial2.val(resultado);
      totalparcial(1);
    } else {
      $parcialC.empty();
      $parcial2.val('');
      $efectivo.val('');
      totalparcial(1);
    }
  }
});

// fin cheque efectivo 

// solo tarjeta
$(document).on('keyup', '#efectivoTarjetatext', function(event) {
  let valor = (this.value + '').replace(/[^0-9]/g, '');
  $('#ParcialT').html(valor ? formatNum(valor) : '');
  $('#parcial3, #efectivoTarjeta').val(valor || '');
  totalparcial(1);
});

// fin solo tarjeta

// cuenta a fabor
$('#multifabor').change(function() {
  let myArray = [];
  let myArray2 = [];
  let resultado = 0;

  $("#multifabor :selected").each(function() {
    const [val1, val2] = $(this).val().split(',');
    myArray.push(parseFloat(val1));
    myArray2.push(val2);
  });

  resultado = myArray.reduce((acc, val) => acc + (val >= 0 ? val : 0), 0);

  $('#ParcialF').html(formatNum(resultado));
  $('#parcial4').val(resultado);
  $('#matris').val(myArray);
  $('#matriscuanta').val(myArray2);
  totalparcial(1);
});

// fin fabor

  // control de cheque
    $("#numcheque").on("input", function() {
      let id = $(this).val();
      if (id > 0) {
        $("#Cliente, #efectivotxt, #fecha_pago").prop('disabled', false).prop('required', true);
      } else {
        $("#Cliente, #efectivotxt, #fecha_pago").prop('disabled', true).prop('required', false);
      }
    });
   // fin
   

   // control checkbox banco
    $("#checkboxbanca").on('change', function() {
      var cuentaBancaria = $("#cuenta_bancaria");

      if ($(this).is(':checked')) {
        cuentaBancaria.prop('disabled', false).prop('required', true);
      } else {
        cuentaBancaria.prop('disabled', true).prop('required', false);
        $('[name="cuenta_bancaria"]').val('').trigger("change");
      }
    });
    // fin


   $("#agregar_cuenta").on( 'change', function() {
        // let diferencia = parseInt($('#diferencia').val());
        let vueltototal = $('#vueltototal').val();

        if( $(this).is(':checked') ) {
              $('#si_no').val('1');
                $('#ajustado').val(vueltototal);
                 $('#valor').html(formatNum(vueltototal));
                $('#vuelto').html('');
                $('#vueltototal').val('');


        } else {
                  $('#vuelto').html(formatNum($('#ajustado').val()));
                  $('#vueltototal').val($('#ajustado').val());
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
        }
   });


    let isLoading = false;

    $('li#c a').click(async function() {
        if (!isLoading) {
        isLoading = true;
        $("#cheque_tercero").html('<p>Cargando...</p>'); // Indicador de carga

        try {
            const response = await fetch("Deuda_empresa/cuenta_bancaria");
            if (response.ok) {
            const data = await response.text();
            $("#cheque_tercero").html(data);
            } else {
            $("#cheque_tercero").html('<p>Error al cargar datos.</p>');
            }
        } catch (error) {
            console.error(error);
            $("#cheque_tercero").html('<p>Error de conexión.</p>');
        } finally {
            isLoading = false;
        }
        }
    });

    let isLoadingFormaPago = false; // Indicador de estado para la carga de formas de pago

    $('li#s a').click(async function() {
    if (!isLoadingFormaPago) {
        isLoadingFormaPago = true;
        $("#multifabor").html('<p>Cargando...</p>'); // Indicador de carga

        try {
        const response = await fetch("Compra/formapago/3/" + proveedor);
        
        if (response.ok) {
            const data = await response.text();
            $("#multifabor").html(data);
        } else {
            $("#multifabor").html('<p>Error al cargar datos.</p>');
        }
        } catch (error) {
        console.error(error);
        $("#multifabor").html('<p>Error de conexión.</p>');
        } finally {
        isLoadingFormaPago = false;
        }
    }
    });







       
});


// Función para realizar un cambio
function cambio(id) {
  const $EF = $(`#EF${id}`);
  const val = Number($EF.val().replace(/[^\d]/g, ''));
  const monto = $EF.data('monto');
  let total = '';

  if (val > 0) {
    if (monto > 0) {
      total = operaciones(val, monto, '*');
    } else {
      $(`#MontoMoneda${id}`).val(val);
      total = val;
    }
  } 

  $(`#MontoMoneda${id}`).val(val);
  const $montoFormat = $(`#montoFormat${id}`);
  $montoFormat.val(formatNum(total));
  $(`#montoCambiado${id}`).val(total);
  $('#ParcialE').html(formatNum(recorrerMoneda()));
  $('#parcial1').val(recorrerMoneda());
  totalparcial(1);
}

const recorrerMoneda = () => {
  let val = $('#valtotalmoneda').val();
  let resultVal = 0;
  for (let i = 1; i <= val; i++) {
    let res = $('#montoCambiado'+i).val();
    if (res > 0) {
       resultVal += parseFloat(res);
    }
  }
  return resultVal;
};

const totalparcial = (xx) => {
  let total = 0;
  for (let i = 1; i < 5; i++) {
    let id = $('#parcial'+i).val()
    if (id > 0) {
      total += parseFloat(id);
    }
  }
  totalparciales(total);
};

const totalparciales = (total) => {
    if (total > 0) {
        $('#Totalp').html(formatNum(total));
        $('#Totalparclal').val(total);
        let final = operaciones(total, motopagar, '-');
        $('#rerer').html(formatNum(final));
        if (final > 0) {
          $('#vuelto').html(formatNum(final));
          $('#vueltototal').val(final);
          $('#rerer').html('');
          if (proveedor > 0) {
            $(".controlajustar").show();
          }
          reerrt();
        } else {
          if (proveedor > 0) {
            $(".controlajustar").hide();
          }
          $('#vueltototal').val('');
          $('#vuelto').html('');
          reerrt();
        }
     } else {
        $('#agregar_cuenta').removeAttr('checked');
        $('#vueltototal,#Totalparclal,#si_no,#ajustado').val('');
        $(".controlajustar").hide();
        $('#valor,#rerer,#vuelto,#Totalp').html('');
     }
};


function reerrt(argument) {
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
}


function reloadcheque(argument) {
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").removeAttr('required');

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(function () {
$('#compra_submit').submit(function(e) {
  e.preventDefault();
  var Totalparclal = parseFloat($('#Totalparclal').val());
  var deudapagar = parseFloat($('#deudapagar').val());

  if (save_method === 'add_add' && Totalparclal >= deudapagar) {
    $.ajax({
      url: "ajax_add_pago",
      type: 'POST',
      dataType: 'json',
      data: $(this).serialize() + '&' + $('#comprar').serialize(),
    })
    .then(function(data) {
      var b = $('#loadingg');
      b.button("loadingg");
      return new Promise(function(resolve, reject) {
        setTimeout(function() {
          b.button("reset");
          $("#compra_submit,#modal-header").hide();
          alertasave('Datos Registrado correctamente');
          resolve();
        }, 100);
      });
    })
    .then(function() {
      return new Promise(function(resolve, reject) {
        setTimeout(function() {
          $('#modal-id').modal('hide');
          $('#compra_submit,#comprar')[0].reset();
          $("#compra_submit select").val(null).change();
          $("#contenido,.modal-header2").show();
          Limpiar(1);
          save_method = 'add';
          resolve();
        }, 400);
      });
    })
    .then(function() {
      setTimeout(function() {
        $("#campoBusqueda").focus();
      }, 800);
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
      toastem.error("Error al registrar los datos");
    });
  } else {
    $('.alerter').html("Monto es Inferior al Monto Total a Pagar!! ").show();
    setTimeout(function() {  $('.alerter').hide();}, 8000);
  }
});

/**
 * Al precionar boton close la variable save_metodo = add
 */
$(this).on('click', '.close', function(event) {
 save_method = 'add';
});


  $('#checkbox_vence').change(function(event) {

      if ($(this).is(':checked')) {
           $('#virtual').val(1);
           $('#vence').prop({disabled: true}).val("");
      }else{
         $('#virtual').val(0);
         $('#vence').prop({disabled: false});
      }
  });

    // Función para mostrar el modal de búsqueda
    function mostrarModalBusqueda() {
        $('#campoSearch').val('');
        $('.todo-list').html('');
        $('#modalSearch').modal("show");
        // Después de 2 segundos, enfocar el campo de búsqueda
        setTimeout(function() {
            $('#campoSearch').focus();
        }, 1000);

    }

    // Evento de clic en el botón "search-product"
    $(document).on('click', 'button.search-product', mostrarModalBusqueda);

    // Evento de tecla presionada en el campo "campoSearch"
    $('#campoBusqueda').on('keydown', function(event) {
        if (event.which === 32) { // 32 es el código de la tecla espacio
            event.preventDefault(); // Evita que el espacio se agregue al campo
            mostrarModalBusqueda();
        }
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

$('#customSwitch1').change(function() {
    if ($(this).is(':checked')) {
        $('.mostrarMoneda').fadeIn(); // Muestra los elementos con un efecto de desvanecimiento
    } else {
        $('.mostrarMoneda').fadeOut(); // Oculta los elementos con un efecto de desvanecimiento
    }
});


  $('#modal-id').on('shown.bs.modal', function () {
    // Coloca aquí el código que deseas ejecutar al abrir el modal
    $('#divisas tbody tr').each(function () {
        var $td = $(this).find('td');
        var signo = $td.data('signo');
        var cambio = $td.data('cambio');

        // Verifica si tanto motopagar como cambio están definidos
        if (motopagar !== undefined && cambio !== undefined) {
          var resultado = motopagar / cambio;
          var equivalente = resultado % 1 === 0 ? resultado.toFixed(0) : Number.parseFloat(resultado).toFixed(2);


            $td.text(equivalente + ' ' + signo);
        } else {
            // Maneja la situación si motopagar o cambio no están definidos
            $td.text('');
        }
    });
});

/////////////////////////////seccion del carrito//////////////////////////////
        $(document).on('change keyup', '.cantidad', function(event) {
            var j = $(this);
            update_rowid(j.val(), j.attr('id'));
        });

        $(document).on('change keyup', '[name="dataprice"]', function(event) {
            var $price = $(this).val();
            var $rowid = $(this).data('id');
            var $qty = $(this).data('qty');
            var $iva = $(this).data('iva');
            var $tyf = $(this).data('tyf');
            update2_rowid($price, $rowid, $qty, $iva, $tyf);
        });

        $(document).on('change', '[name="descuento"]', function(event) {
            var $val = $(this).val();
            var $id = $(this).data('id');
            var $qty = $(this).data('qty');
            var $price = $(this).data('price');
            var $i = $(this).data('i');
            update_descuento($val, $id, $qty, $price,$i);
        });

        $('.idRecorrer td.selec select.descuento').each(function(index, el) {
            $(this).val($(this).data('tyf'));
        });

        $(document).on('click', 'div.pull-right a.deleterow', function(event) {
            var rowid = $(this).data('id');
            deleterowid(rowid);
        });


    
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

                  cargarContenido('Compra/loader', 'detalle');
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
                 cargarContenido('Compra/loader', 'detalle');
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

    function deleterowid(rowid)
    {
        console.log(rowid);
        Swal.fire({
        title: "Estas seguro?",
        text: "No podrá recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
        confirmButtonColor: "#DD6B55",
        showCancelButton: true,
        closeOnConfirm: false
      }).then((result) => {
        if (result.value) {
          $.ajax({
              url : "<?php echo base_url('Orden_compra/delete_item');?>/"+rowid,
              type: "POST",
              cache: false,
              data: $(this).serialize(), // serilizo el formulario
              success: function(data)
              {

                 cargarContenido('Compra/loader', 'detalle');
              },
          });
          Swal.fire("Eliminado!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }

      })
    }

    function update_descuento(val,id,qty,price,i) {
      $.ajax({
        url: "Orden_compra/update_descuento",
        type: 'POST',
        dataType: 'JSON',
        data: {
          val: val,
          id: id,
          qty: qty,
          price: price,
          i: i

        },
      })
      .done(function(data) {
        if (data) {
           cargarContenido('Compra/loader', 'detalle');
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }

////////////////////final seccion carrito////////////////////////////////
  // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    $('#comprobante').on('blur', function() {
        var comprobante = $(this).val();
        var tipoComprovante = $('#tipoComprovante').val();
        var proveedor = $('#proveedor').val();


        // Limpiar mensajes de error anteriores
        $('#comprobante').tooltip('hide').attr('data-original-title', '');
        $('#comprobante').removeClass('is-invalid');
        $('#add01').prop('disabled', false); // Habilitar el botón de enviar

        if (comprobante && tipoComprovante && proveedor) {
            fetch('Compra/verificar_comprobante', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comprobante: comprobante, tipoComprovante: tipoComprovante,proveedor: proveedor  })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'exists') {
                    $('#comprobante').addClass('is-invalid');
                    $('#comprobante').attr('data-original-title', 'El comprobante ya está registrado.').tooltip('show');
                    Swal.fire("El Numero de comprobante ya está registrado.");
                    $('#add01').prop('disabled', true); // Desactivar el botón de enviar
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });




});




    function Limpiar(id) {
          // Cargar contenido de forma asíncrona utilizando fetch API
          cargarContenido('Compra/loader/1', 'detalle');

          // Limpiar los valores de los campos utilizando selectores de ID
          $('#productos, #proveedor, #orden').val('').trigger('change');
          
          // Establecer el valor de los campos utilizando selectores de ID
          $('#condicion').val('1');
          $('#Estado').val('0');
          
          // Habilitar el campo de vencimiento y restaurar el atributo "required" si el checkbox está desactivado
          if (!document.getElementById('checkbox_vence').checked) {
            document.getElementById('vence').disabled = false;
            document.getElementById('vence').required = true;
          }
        }

</script>
