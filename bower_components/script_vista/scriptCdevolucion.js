var table;
var save_method;
let formEnviado = false;
let motopagar = 0;
const swalConfig = {
  title: "<span id='mensajeAgradecimiento' >¡Gracias por su Pago!</span>",
  icon: "info",
  showCloseButton: true,
  showCancelButton: false,
  focusConfirm: false,
  confirmButtonText: "<i class='fa fa-thumbs-up'></i> Cerrar",
};


$( "#DEV,#Compra" ).addClass( "active" );
$( "#DEV,#D_E_V" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
function format(d) {
  var id = d.slice(7); // Obtener el ID de la cadena
  $.ajax({
      type: 'POST',
      url: "CDevoluciones/detale/" + id,
      dataType: 'json',
  })
  .done(function(data) {
      if (data && Array.isArray(data) && data.length > 0) {
          // Si existen datos, procesarlos
          $.each(data, function(index, val) {
              var sub = '';
              if (val.Descuento != '') {
                  sub = (parseInt(val.Cantidad) * parseInt(val.Precio));
              } else {
                  sub = (parseInt(val.Cantidad) * parseInt(val.Precio));
              }

              // Añadir una fila a la tabla
              $('#' + id).append('<tr class="success"><td>' + val.Cantidad + '</td><td>' + val.Nombre + '</td><td>' + formatNumber.new(val.Precio) + ' ₲.</td><td>' + formatNumber.new(val.mo) + '</td><td>' + val.es + '</td><td>' + formatNumber.new(sub) + ' ₲.</td><td><div class="pull-right hidden-phone"><a class="btn btn-danger btn-xs" href="javascript:void(0);" title="Hapus" onclick="delete_(' + val.id + ',' + val.Estado + ',' + val.Cantidad + ',' + val.Motivo + ',' + val.id2 + ',' + val.del + ',' + val.Precio + ',' + val.id6 + ')"><i class="fa fa-trash-o"></i></a></div></td></tr>');
          });
      } else {
          // Si no hay datos, mostrar un mensaje o no hacer nada
          $('#' + id).append('<tr><td colspan="7" class="text-center">No se encontraron datos para mostrar.</td></tr>');
      }
  })
  .fail(function() {
      // Manejar posibles errores de la solicitud AJAX
      $('#' + id).append('<tr><td colspan="7" class="text-center">Error al cargar los datos.</td></tr>');
  });

  // Estructura básica de la tabla
  return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="' + id + '">' +
      '<tr class="danger">' +
      '<td>Cantidad</td>' +
      '<td>Nombre</td>' +
      '<td>Precio</td>' +
      '<td>Motivo</td>' +
      '<td>Estado</td>' +
      '<td>Subtotal</td>' +
      '<td>Acción</td>' +
      '</tr>' +
      '</table>';
}

    $(function() {


      $('#validat, #fletes').mask('000.000.000.000.000', {reverse: true});
        $('#tipooccion').change(function() {
            var tipoOpcion = $(this).val();
            var fechaGroup = $('#fecha_pago_group');
            var fechaLabel = $('#fecha_label');
            var fechaInput = $('#fecha_pago');
            save_method = 'add';
    
            if (tipoOpcion === '9') { // Mercadería Devuelta y Cambio Posterior
                fechaGroup.show();
                fechaLabel.text('Fecha de Cambio');
                fechaInput.prop('required', true);
            } else if (tipoOpcion === '10') { // Mercadería Devuelta y Cobro Posterior
                fechaGroup.show();
                fechaLabel.text('Fecha de Pago');
                fechaInput.prop('required', true);
            } else if (tipoOpcion === '2') { // Mercadería Devuelta y Cobro Posterior
              save_method = "cobrar";
            }else{
              fechaGroup.hide();
              fechaInput.prop('required', false);
            }
        });

      table = $('#tabla_CD').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "CDevoluciones/ajax_list",
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
            { "data": "4" },
            { "data": "5" },
            { "data": "6" },


        ],
        // "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ]]
      });

$('#tabla_CD tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);

    if (row.child.isShown()) {
        // Si la fila ya está abierta, la cerramos
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Cerrar todas las filas abiertas antes de abrir una nueva
        table.rows().every(function () {
            if (this.child.isShown()) {
                this.child.hide();
                $(this.node()).removeClass('shown');
            }
        });

        // Ahora abrimos la nueva fila
        row.child(format(row.data())).show();
        tr.addClass('shown');
    }
});

    
// Función para recargar la tabla
const reload_table = () => {
  table.ajax.reload();
};





// Función para cargar el detalle
const carga = async () => {
  await $( "#detalle" ).load( "CDevoluciones/loader/1" );
  save_method = 'add';
  $('#devolver')[0].reset();
  $(".proveedor, .Comprobante").val('').trigger("change");
  $('.desc, .PRO, .COMP, .PR, .FINAL, .TIPO, .FECHA, .INIT, .COND, .CUO, .FLE, .OBSER, #alerta').html("").css("display", "none");
};

carga(); // Llama a la función para cargar el detalle



$('#devolver').submit(async function(e) {

  e.preventDefault();
  motopagar = $('#finalcarrito').val();
  if (save_method === 'add') {
      const $loadingButton = $('#loading');
      const $errorFields = $('.PRO, .COMP, .TIPO, .mov');
      const url = "CDevoluciones/ajax_add";

      try {
          const response = await $.ajax({
              type: 'POST',
              url: url,
              data: $(this).serialize(),
          });

          const json = JSON.parse(response);

          if (json.res === "error") {
              $loadingButton.button("reset");
              Object.entries(json).forEach(([key, value]) => {
                  if (value) $(`.${key}`).append(value).show();
              });
          } else {

              $loadingButton.button('loading');
              setTimeout(() => {
                  $loadingButton.button("reset");
                  alertasave('Datos Registrados correctamente');
              }, 1000);
              setTimeout(() => {
                  $("#alerta").fadeOut(1500);
                  save_method = 'add';
                  // $('#devolver')[0].reset();
                  Limpiar(1);
                  reload_table();
              }, 2000);
          }
      } catch (error) {
          Swal.fire('Disculpe, existió un problema');
      }
  } else if (save_method === 'cobrar' || save_method === 'add_add') {
    $('#modal-id').on('hidden.bs.modal', function () {
      $(this).find('input[type="hidden"]').not('.hiddennone').val('');
      
    }).modal('show');
        formEnviado = false;
        $('#Venta_submit')[0].reset();
        $('#ParcialE, #ParcialC, #ParcialT').empty();
        $('.hidden').val('');
        $('#tabEfectivo').tab('show');
        $('#numcheque').prop('required', false);
        $("#Venta_submit,#modal-header").show();

 
        totalparcial();
        reloadcheque();
        $('#deudapagar').val(motopagar);
        $('#m_total, #spanmontopagar, #spanmontopagarchque, #spanmontopagartar, #spanmontopagarfa').text(formatNum(motopagar));
        $('.controlajustar').hide();
        save_method = 'add_add';

        setTimeout(() => {
            $("#EF1").focus();
        }, 1000);
  }
});



const mostrarMensajesValidacion = (data) => {
  const elementos = {
    EF1: '.EF1',
    EF2: '.EF2',
    EF3: '.EF3',
    EF4: '.EF4',
    EF5: '.EF5',
    EF6: '.EF6',
    Totalparclal: '.Totalparclal',
    numcheque: '.numcheque',
    Cliente: '.Cliente',
    fecha_pago: '.fecha_pago',
    efectivotxt: '.efectivotxt',
    efectivoTarjeta: '.efectivoTarjeta',
    Tarjeta: '.Tarjeta',
    multi: '.multi'
  };

  Object.keys(data).forEach(key => {
    if (data[key]) {
      $(elementos[key]).html(data[key]).show();
    }
  });
}

// Método para mostrar el swal para imprimir o no
const mostrarSwalImprimir = (vuelto) => {
  Swal.fire({
      title: '¿Desea imprimir el recibo?',
      text: 'La venta se ha guardado correctamente.',
      icon: "question",
      showCancelButton: true,
      confirmButtonText: 'Imprimir',
      cancelButtonText: 'No Imprimir',
      didRender: () => {
          // Focus en el botón de imprimir cuando se abre el swal

          setTimeout(() => {
            document.querySelector('.swal2-confirm').focus();
            document.addEventListener('keydown', handleKeyboardNavigation); // Agregar el evento de teclado al mostrar el swal
          }, 500);
      }
  }).then((result) => {
      if (result.isConfirmed) {
          // Aquí colocas el código para imprimir el recibo si el usuario elige imprimir
          // Puedes llamar a una función específica para imprimir el recibo
          // imprimirRecibo();
      }

      // Ahora muestras el swal original
      setTimeout(function() {
          if (vuelto > 0) {
              swalConfig.html = `<span id="mensajeVuelto" >Vuelto: ${formatPrice(vuelto)}</span>`;
          } else {
              swalConfig.html = `<span id='mensajeexacto'>Pago Exacto</span>`;
          }
          Swal.fire(swalConfig);
          $campoBusqueda.val('').focus();
      }, 100);
  });
}


$('#Venta_submit').submit(async function(e) {
  e.preventDefault();
  if (formEnviado) {
    return; // Evitar envío del formulario si ya ha sido enviado
  }

  const Totalparclal = parseFloat($('#Totalparclal').val());
  const deudapagar = parseFloat($('#deudapagar').val());
  const parcial2 = $("#parcial2").val();
  const vuelto = $("#vueltototal").val();
  const b = $('#loadingg');

  try {
    // Check if save_method is 'add_add' and Totalparclal is greater than or equal to deudapagar
    if (save_method === 'add_add' && Totalparclal >= deudapagar) {
      formEnviado = true;
      b.button("loadingg");

      const data = await $.ajax({
        url:   "CDevoluciones/ajax_add",
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize() + '&' + $('#devolver').serialize()

      });

      if (data.res === "error") {
        // Aquí puedes mostrar los mensajes de validación
        mostrarMensajesValidacion(data);
      } else {
        await new Promise((resolve) => {
          setTimeout(() => {
            $('#Venta_submit,#devolver')[0].reset();
            b.button("reset");
            $("#Venta_submit,#modal-header").hide();

            if ($('#tipoComprovante').val() == 1) {
              $("#devolver input[id=TerceraSeccion]").text(data.TerceraSeccion);
            } else {
              $("#devolver input[id=inputTicket]").val(data.NumeroTicket);
            }
            Limpiar(1);
            resolve();
          }, 200);
        });

        await new Promise((resolve) => {
          setTimeout(() => {
            // $('#tipoComprovante').val('').change();
            $('#modal-id').modal('hide');
            $("#devolver input[type=hidden], #Venta_submit input[type=hidden]")
              .filter(function() {
                return $(this).attr("class") !== "hiddennone";
              }).val(''); // Limpia solo los elementos que pasaron el filtro

            $("#Venta_submit select").val(null).change();
            $("#contenido,.modal-header2").show();
            save_method = 'add';
            resolve();
          }, 400);
        });

        if (parcial2 > 0) {
          $("#numcheque").val("").trigger('input');
        }
        $(".mostrarMoneda").hide();
        mostrarSwalImprimir(vuelto);
      }
    } else {
      $('.alerter').html("Monto es Inferior al Monto Total a Pagar!! ").show();
      setTimeout(() => {
        $('.alerter').hide();
      }, 8000);
    }
  } catch (error) {
    console.error("Error en la solicitud AJAX:", error);
    toastem.error("Error al registrar los datos");
  }
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

  $('#tipoComprovante').change((event) => {
    const $this = $(event.currentTarget);
    const clienteInput = $('[name="proveedor"]');
    const comprobante = $('#comprobante');
    const Ticket = $('#Ticket');
    
    const tipoComprovanteVal = $this.val();
    const estadoVal = $('#Estado').val();

    if (tipoComprovanteVal && (tipoComprovanteVal === '1' || estadoVal === '2')) {
        clienteInput.prop('required', true);
        comprobante.removeClass('hidden');
        Ticket.addClass('hidden');

        const openSelect2 = () => {
            if (typeof Cliente === 'undefined' || Cliente <= 1) {
                setTimeout(() => {
                    clienteInput.select2("open");
                }, 500);
            } else {
                setTimeout(() => {
                    $("#add01").focus();
                }, 100);
            }
        };
        openSelect2();
    } else {
        clienteInput.prop('required', false);
        comprobante.addClass('hidden');
        Ticket.removeClass('hidden');

        setTimeout(() => {
            $("#add01").focus();
        }, 100);
    }
});
 
  $( ".orden,.Comprobante,.select2" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );
    $( "#seartt,#seat2,#seat3" ).click( function() {
          $( "#" + $( this ).data( "select2-open" ) ).select2( "open" );
        });

$('.Comprobante').change(async function(event) {
  const [id, descuento, fletes, deposito_stock] = $(this).val().split(',');
      // Obtener el <option> seleccionado
      const selectedOption = this.options[this.selectedIndex];
      $( "#comprobanteA").val(selectedOption.getAttribute('data-factura'));
      $( "#timbradoA").val(selectedOption.getAttribute('data-timbrado'));

  $('#descuento').val(descuento);
  $('#fletes').val(fletes);
  $('#id').val(id);

  if (id === '') {
    const nombre = localStorage.getItem('id');
    if (nombre === '') {
      toastem.success('no hay datos todavia');
    } else {
      $('#detalle').load('CDevoluciones/loader/1');
    }
  } else {
    localStorage.setItem('id', id);
    if (id) {
      try {
        const response = await $.ajax({
          url: `CDevoluciones/agregar_item/${id}`,
          type: 'POST',
          dataType: 'JSON',
          async: true,
        });
        if (response) {
          $('#detalle').load('CDevoluciones/loader');
          $('.productos').val('').trigger('change');
          toastem.abrir(`${response} Articulo agregador`);
        } else {
          toastem.cerrar('Sin resultado');
        }
      } catch (error) {
        toastem.error('Ha ocurrido un error');
      }
    }
    $('#inventario').val(deposito_stock).trigger('change');
  }
});
    $(".proveedor").select2({
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
                          id: item.id, // ID del proveedor
                          text: item.full_name + " - " + item.ruc, // Combina nombre y RUC
                          nombre: item.full_name, // Guarda el nombre
                          ruc: item.ruc // Guarda el RUC
                      };
                  }),
                  pagination: {
                      more: false // Puedes habilitar paginación si es necesario
                  }
              };
          },
          cache: true
      },
      placeholder: 'Busca y Selecciona',
      minimumInputLength: 5, // Mínimo de caracteres para activar la búsqueda
      theme: 'bootstrap' // Tema de Select2
    }).on('select2:select', function (event) {
      // Obtén los datos del proveedor seleccionado
      const selectedData = event.params.data;

      // Actualiza los campos de entrada
      $("#nombreProveedor").val(selectedData.nombre);
      $("#rucProveedor").val(selectedData.ruc);

      // Carga los comprobantes asociados al proveedor seleccionado
      const id = selectedData.id;
      if (id) {
          $(".Comprobante").load("CDevoluciones/list_comprobante/" + id);
      }

      // Ajusta el ancho del Select2
      $(this).next('.select2-container').css('width', '-webkit-fill-available');
    });
///////////////////////////////////////seccion carrito --------------------------------////////////////////////////////
// var inpuesto_cinco  =0,iva  =0,inpuesto_diez  =0,total_cinco  =0,total_diez  =0,total_iva  =0,num1  =0,num2 =0;
 // Delegar el evento keyup a los inputs con clase 'valid' dentro de la tabla con id 'principal'
    $(document).on('keyup', '#principal .valid', function(event) {
      var longit = parseInt($(this).data('id'));
      var stock = parseInt($(this).data('id2'));
      var num = parseFloat($(this).val());

      // Reemplazar caracteres no numéricos
      this.value = this.value.replace(/\D/g, '');

      // Validar que el valor no exceda stock o longit
      if (num > stock || num > longit) {
          $(this).val('');
      } else {
          $('#' + this.id).attr({max: longit, maxlength: longit.toString().length});
      }
    });

    $(document).on('input', '.sumar', function(event) {

        totalfinal();
    });

    $(document).on('focusout', '#principal input', function(event) {
      // Obtener el campo de entrada y sus atributos
      const $input = $(this);
      const id = $input.attr('id');
      let val = parseFloat($input.val());
      let perdida = parseFloat($input.data('monto'));
      const iva = parseInt($input.data('iva'));
    
      // Eliminar los atributos de IVA antes de hacer los cálculos, para asegurarse de empezar sin valores residuales
      $('.' + id).removeAttr('data-iva10').removeAttr('data-iva5').removeAttr('data-grabadas10').removeAttr('data-grabadas5');
    
      // Validar valores de entrada para asegurar que son números válidos
      if (isNaN(val) || isNaN(perdida)) {
        // Si el valor o pérdida no son válidos, restablecerlos a 0 para evitar cálculos incorrectos
        val = 0;
        perdida = 0;
    
        // Restablecer los atributos de IVA a 0 y limpiar la caché de .data()
        $('.' + id).attr('data-iva10', 0).removeData('iva10');
        $('.' + id).attr('data-iva5', 0).removeData('iva5');
        $('.' + id).attr('data-grabadas10', 0).removeData('grabadas10');
        $('.' + id).attr('data-grabadas5', 0).removeData('grabadas5');
        $('.' + id).html(0); // Mostrar resultado en 0
      } else {
        // Calcular el resultado
        const resultado = perdida * val;
        $('.' + id).html(resultado.toFixed(0)); // Mostrar resultado con dos decimales
    
        // Calcular impuesto basado en el valor de iva
        let impuesto = 0;
        let grabada = 0;
        if (iva === 11) {
            impuesto = resultado / 11;
            grabada = resultado - impuesto;
            $('.' + id).attr('data-iva10', impuesto.toFixed(0)); // Asignar el valor de IVA 10
            $('.' + id).attr('data-grabadas10', grabada.toFixed(0)); // Asignar monto grabado con IVA 10
            $('.' + id).removeData('iva5').removeAttr('data-iva5'); // Asegurarse de que IVA 5 está en 0
        } else if (iva === 5) {
            impuesto = resultado / 21;
            grabada = resultado - impuesto;
            $('.' + id).attr('data-iva5', impuesto.toFixed(0)); // Asignar el valor de IVA 5
            $('.' + id).attr('data-grabadas5', grabada.toFixed(0)); // Asignar monto grabado con IVA 5
            $('.' + id).removeData('iva10').removeAttr('data-iva10'); // Asegurarse de que IVA 10 está en 0
        }
      }
    
      // Actualizar totales
      actualizarTotales();
    });
    

    function actualizarTotales() {
      let resultVal = 0;
      let resultIva10 = 0;
      let resultIva5 = 0;
      let resultGrabadas10 = 0;  // Nueva variable para el total de montos grabados con IVA 10
      let resultGrabadas5 = 0;   // Nueva variable para el total de montos grabados con IVA 5
    
      const rows = $("#principal tbody tr");
      rows.each(function(index, row) {
          // Simplificado para mayor legibilidad
          const $row = $(row).find(".recorerr").eq(0);
          let montototal = parseFloat($row.text()) || 0;
          
          // Usar .attr() en lugar de .data() para obtener el valor actual en el DOM
          let iva10temp = parseFloat($row.attr('data-iva10')) || 0;
          let iva5temp = parseFloat($row.attr('data-iva5')) || 0;
          
          // Usar los atributos grabados para obtener los valores de los montos grabados
          let grabadas10Temp = parseFloat($row.attr('data-grabadas10')) || 0;
          let grabadas5Temp = parseFloat($row.attr('data-grabadas5')) || 0;
    
          // Acumular valores para los totales
          resultVal += montototal;
          resultIva10 += iva10temp;
          resultIva5 += iva5temp;
          resultGrabadas10 += grabadas10Temp; // Sumar los montos grabados con IVA 10
          resultGrabadas5 += grabadas5Temp;   // Sumar los montos grabados con IVA 5
      });
    
      // Habilitar/deshabilitar el botón #loading según el total calculado
      $('#loading').prop('disabled', resultVal === 0);
    
      // Actualizar campos con los valores calculados
      $('#cartotal,#total').val(resultVal);
      $('.finales,.montoTotal').html(resultVal);
      $('.totalesiva').html(resultIva10 + resultIva5);
      $('.total_iva_cinco').html(resultIva5);
      $('.total_iva_diez').html(resultIva10);
      $('#lesiva').val(resultIva10 + resultIva5);
      $('#iva_cinco').val(resultIva5);
      $('#iva_diez').val(resultIva10);
    
      // Asignar los totales grabados con IVA 10 y 5
      $('#grabadas10').val(resultGrabadas10);
      $('#grabadas5').val(resultGrabadas5);
    
      // Llamar a una función adicional si es necesario
      totalfinal();
    
      // Establecer el valor máximo permitido para los inputs con la clase .sumar
      $('.sumar').attr('max', resultVal);
    }
    

let inputs;
function updateInputs() {
  inputs = $('.devolver-input').not(':disabled');
}

  $(document).on('change', '.micheckbox', function() {
      let isChecked = $(this).is(':checked');
      inputDevolver = $(this).closest('tr').find('.devolver-input');
      inputs = $('.devolver-input').not(':disabled');

      // Habilitar/deshabilitar elementos según estado del checkbox
      $(".valid, #loading").prop('disabled', !isChecked).val(isChecked ? $(".valid").val() : '');
      $('#cart_total').val(isChecked ? $('#cart_total').val() : '');
      $('.totalesiva, #recorerr, .recorerr').html(isChecked ? $('.totalesiva').html() : '');

      // Habilitar y enfocar el campo de devolución si está marcado
      inputDevolver.prop('disabled', !isChecked).val(isChecked ? inputDevolver.val() : '').trigger(isChecked ? 'focus' : 'blur');

      // Si se desmarca, limpiar valores y actualizar totales
      if (!isChecked) actualizarTotales();  
       // Actualizar la lista de campos habilitados
      updateInputs();
  });

    // Asignar el evento keydown a los campos "devolver"
  $(document).on('keydown', '.devolver-input', function(event) {
      var currentIndex = inputs.index(this);

      // Tecla "Abajo" (↓)
      if (event.key === 'ArrowDown') {
          event.preventDefault();
          var nextIndex = currentIndex + 1;

          if (nextIndex >= inputs.length) {
              nextIndex = 0;
          }

          if (inputs[nextIndex]) {
              inputs[nextIndex].focus();
              inputs[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
      }

      // Tecla "Arriba" (↑)
      if (event.key === 'ArrowUp') {
          event.preventDefault();
          var prevIndex = currentIndex - 1;

          if (prevIndex < 0) {
              prevIndex = inputs.length - 1;
          }

          if (inputs[prevIndex]) {
              inputs[prevIndex].focus();
              inputs[prevIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
      }
  });

$(document).on('input', 'input[name="devolver"]', function() {
  var rowid = $(this).attr('id'); // Obtener el rowid del producto
  var devolver = $(this).val();   // Obtener el valor del campo "devolver"

  // Enviar los datos al servidor para actualizar el carrito
  $.ajax({
      url: 'CDevoluciones/actualizar_carrito', // URL del controlador
      type: 'POST',
      data: {
          rowid: rowid,
          devolver: devolver
      },
      success: function(response) {
          console.log('Carrito actualizado');
      },
      error: function(xhr, status, error) {
          console.error('Error al actualizar el carrito');
      }
  });
});




$(document).on('keyup', '.validat', function(event) {
  let value = (this.value + '').replace(/[^0-9]/g, '');
  if (value > 1) {
    $(this).val(formatPrice(value));
  }
});




///////////////////////////////////////////////fin del carrito//////////////////////////////////////



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
            url : "Compra/delete_item/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
               $( "#detalle" ).load( "CDevoluciones/loader");
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
          url : "CDevoluciones/update_rowid/"+id,
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
                 $( "#detalle" ).load( "CDevoluciones/loader" );
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
      $( "#detalle" ).load( "CDevoluciones/loader/"+id );
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
                  url : "CDevoluciones/ajax_delete",
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





//////////////////////Seccion cobros/////////////////////////////////////
// Función para realizar un cambio




const cambio = (id) => {
  // console.log(`Función cambio ejecutada para ID: ${id}`);

  const $EF = $(`#EF${id}`);
  if (!$EF.length) {
      console.error(`No se encontró el elemento #EF ${id}`);
      return;
  }

  const val = Number($EF.val().replace(/[^\d]/g, ''));
  // console.log(`Valor ingresado: ${val}`);

  const monto = $EF.data('monto');
  console.log(`Monto: ${monto}`);

  let total = '';
  if (val > 0) {
      if (monto > 0) {
          total = operaciones(val, monto, '*');
      } else {
          $(`#MontoMoneda${id}`).val(val);
          total = val;
      }
  }

  console.log(`Total calculado: ${total}`);

  $(`#MontoMoneda${id}`).val(val);
  const $montoFormat = $(`#montoFormat${id}`);
  $montoFormat.val(formatNum(total));
  $(`#montoCambiado${id}`).val(total);
  $('#ParcialE').html(formatNum(recorrerMoneda()));
  $('#parcial1').val(recorrerMoneda());
  totalparcial(1);
};

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
          if (Cliente > 0) {
            $(".controlajustar").show();
          }
          reerrt();
        } else {
          if (Cliente > 0) {
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
let bandera = true;
let banderas = true;

function reloadcheque(argument) {
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").removeAttr('required');
                  banderas = true;  

}

/////////////////////////////////////parte de reaysy/////////////////////////////////////////////

$('#customSwitch1').change(function() {
    if ($(this).is(':checked')) {
        $('.mostrarMoneda').fadeIn(); // Muestra los elementos con un efecto de desvanecimiento
    } else {
        $('.mostrarMoneda').fadeOut(); // Oculta los elementos con un efecto de desvanecimiento
    }
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
  // control de cheque
  $("#numcheque").on("input", function() {
      let id = $(this).val();
      if (id > 0) {
        $(".chequeControl").prop('disabled', false).prop('required', true);
      } else {
        $(".chequeControl").prop('disabled', true).prop('required', false);
        $('.chequeControl,#efectivotxt').val('').trigger('keyup');
        $(this).prop('required', false);
        totalparcial(1);
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

$('a[data-inputName]').on('click', function() {
  var inputName = $(this).data('inputName');
  setTimeout(function() {
    var activeTab = $('.tab-pane.active');
    var firstInput = activeTab.find('input:not(:checkbox, :hidden):first, select:first, .select2:first');
    if (firstInput.length > 0) {
      if (firstInput[0].classList.contains('select2')) {
        firstInput.select2('open');
      } else {
        firstInput[0].focus();
      }
    }
  }, 200);
});


$('ul li#s a').click(function() {
  if (banderas && Cliente > 1) {
    cargarFormaPago();
  }
});

async function cargarFormaPago() {
  try {
    const response = await fetch("Venta/formapago/3/" + Cliente);
    if (!response.ok) {
      throw new Error("Error al cargar forma de pago");
    }
    const data = await response.text();
    $("#multifabor").html(data);
    banderas = false;
  } catch (error) {
    console.log(error);
  }
}

$(document).ready(function() {
  // Función para manejar el evento keyup
  const handleKeyup = function() {
      const id = this.id.replace('EF', ''); // Extrae el ID de la moneda
      cambio(id); // Llama a la función cambio
  };

  // Asignar el evento onkeyup dinámicamente
  $(document).on('keyup', 'input[type="text"]', handleKeyup);

  // Cuando el modal se abre
  $('#modal-id').on('shown.bs.modal', function () {
      $('#valtotalmoneda').val(4); // Establece un valor fijo
      $('input[type="text"]').off('keyup').on('keyup', handleKeyup); // Reasignar el evento
  });

 
});
function editar_devolucion(devolucion) {
    if (!confirm("¿Estás seguro de que deseas revertir esta devolución?")) {
        return; // Cancelar si el usuario no confirma
    }

    // Mostrar un indicador de carga (opcional)
    $("#loading-indicator").show();

    // Enviar los datos al servidor mediante AJAX
    $.ajax({
        url: "CDevoluciones/revertir_devolucion", // URL del controlador para revertir
        type: "POST",
        dataType: "json",
        data: { devolucion: devolucion }, // Enviar los datos de la devolución
        success: function(response) {
            if (response.success) {
                alert("La devolución ha sido revertida correctamente.");
                // location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert("Error al revertir la devolución: " + response.message);
            }
        },
        error: function() {
            alert("Error al comunicarse con el servidor.");
        },
        complete: function() {
            // Ocultar el indicador de carga (opcional)
            $("#loading-indicator").hide();
        }
    });
}
