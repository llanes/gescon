$(function() {
    const text      = $("#Usuario").val();
    const alertaCaja      = $("#alertaCaja").val();
    let detallesCuotas;
    let save_method ; // letIABLE DE CONTROL
    let table;
    let deuda_e;
    let idreload = 0;
    let bandera = true;
    let banderas = true;
    let posicionSieteArray = [];
    let cliente;


        $( "#CDC,#Venta" ).addClass( "active" );
        $( "#CDC,#C_D_C" ).addClass( "text-red" );


        const searchDeudaCliente = (event) => {
          event.preventDefault();
          const formData = $('#facturaCuota').serializeArray(); // Serializa el formulario a un array

          if (deuda_e) {
            deuda_e.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

          deuda_e = $('#Deuda_c').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
              url: "Deuda_cliente/ajax_list_deudas_clientes",
              type: "POST",
              data: function (d) {
                const formObj = {};
                $.each(formData, function(index, field) {
                  formObj[field.name] = field.value; // Convertir el array en un objeto
                });
                return $.extend({}, d, formObj); // Combina datos de DataTables con el objeto del formulario
              }
            },
            "columnDefs": [
              {
                "targets":  [ -1],
                "orderable": false,
              },
            ],
          });
        };

        const searchDeudaCobradas = (event) => {
          event.preventDefault();
          const formData = $('#listaCuotasPagadas').serializeArray(); // Serializa el formulario a un array

          if (table) {
            table.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

            table = $('#Pagadas').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "Deuda_cliente/ajax_list_pagadas",
                "type": "POST",
                "data": function (d) {
                    const formObj = {};
                    $.each(formData, function(index, field) {
                    formObj[field.name] = field.value; // Convertir el array en un objeto
                    });
                    return $.extend({}, d, formObj); // Combina datos de DataTables con el objeto del formulario
                }
            },
            "columns": [
                {
                    "targets":  [ -1],
                    "orderable": false,
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
                { "data": "6" }


            ],
        } );


        };

$('#facturaCuota').on('submit', searchDeudaCliente);
$('#listaCuotasPagadas').on('submit', searchDeudaCobradas);

// Código corregido:
$('#Pagadas tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row( tr );
    if ( row.child.isShown() ) {
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        row.child( format(row.data()) ).show();
        tr.addClass('shown');
    }
});

function format(d) {
        var val = d[7]; // Assuming the id is in the 7th column (index 6)
        var element = 'details_' + d[0]; // Asumiendo que d[0] es único para cada fila
        var html = '<div id="wrapper_' + element + '">' +
                   '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="table_' + element + '">' +
                   '<thead><tr class="danger">' +
                   '<td>#</td>' +
                   '<td>Descripcion</td>' +
                   '<td>Fecha de Emision</td>' +
                   '<td>Monto Pagado</td>' +
                   '<td>Acciones</td>' +
                   '</tr></thead><tbody id="tbody_' + element + '"></tbody></table></div>';

        $.ajax({
            type: 'POST',
            url: "Deuda_cliente/detale/" + val,
            dataType: 'json',
        })
        .done(function(data) {
            console.log('Data received for element ' + element + ':', data);

            if (data && data.length > 0) {
                let rows = '';
                for (let i = 0; i < data.length; i++) {
                    let val = data[i];
                    let rowClass = val.tipopago != null ? 'highlight' : 'success';
                    let position = i + 1;
                    rows += `
                      <tr class="${rowClass}">
                        <td>${position}</td>
                        <td>${val.des}</td>
                        <td>${val.fec}</td>
                        <td>${val.mon}</td>
                        <td>
                          <div class="pull-right hidden-phone">
                            <a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="deleteItem(${val.idCCE},${val.idCA},${val.idFC},${val.idCF},${data.length},${val.tipopago},${val.mon})">
                              <i class="fa fa-trash-o"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    `;
                }
                $('#tbody_' + element).html(rows);
            } else {
                console.log('No data found');
            }
        })
        .fail(function() {
            console.log('Error en la llamada AJAX');
        });
        // console.log(html);

        return html;
    }


function listar_deudas(id) {
    $('#list').DataTable().destroy();
    $('#addtrpdf').attr('onclick', "pdf_exporte('listadeuda/"+id+"')");
    $('#addtrexel').attr('href','Reporte_exel/listadeuda/'+id+'');
  if (id > 0) {
        $('#Deudas').removeClass('active');$('#listados').addClass('active');
        detallesCuotas = $('#list').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "Deuda_cliente/listar_deudas/"+id,
        "type": "POST",
    },
    "columnDefs": [
        {
            "targets": [ -1 ],
            "orderable": false,
        },
    ],
    "order": [[ 0, 'asc' ], [ 1, 'asc' ], [ 2, 'asc' ], [ 3, 'asc' ], [ 4, 'asc' ], [ 5, 'asc' ]], // Corregido 'acs' a 'asc'
    "initComplete": function () {
        var datos = detallesCuotas.rows().data().toArray(); // Obtener los datos de todas las filas
        posicionSieteArray = datos.map(function (fila) {
            return fila[7]; // Obtener el valor de la posición 7 de cada fila
        });
    }
        
});
  }
       $('#desu').tab('show');
}

function atras() {
  $('#listados').removeClass('active');$('#Deudas').addClass('active');
}












































let recibosAcumulados = [];
let montoTotal = 0;
let cantidadTotal = 0;
let totalrousArray = 0;
let cantidadCuota = 0;

const $cobrarButton = $('#cobrarSeleccionados');
  const $cantidadSeleccionada = $('#cantidadSeleccionada');

  // Delegación de eventos para checkboxes
  $(document).on('change', 'input[name="seleccionar_cuota[]"]', function() {
    updateCobrarButtonText();
  });

  $cobrarButton.on('click', function(event) {
    event.preventDefault(); // Para evitar el comportamiento predeterminado del enlace
    montoTotal = 0; // Inicializar montoTotal dentro del evento click
    const $selectedCheckboxes = $('input[name="seleccionar_cuota[]"]:checked');
    recibosAcumulados = [];
    totalrousArray = 0;
    cantidadCuota = 0;


    $selectedCheckboxes.each(function() {
        const cuotaId = $(this).val();
        const monto = $(this).data('monto');
        const totalrous = $(this).data('recordstotal');


        // Encuentra los detalles de la cuota correspondiente en el array detallesCuotas
        posicionSieteArray.forEach((detalle) => {
            if (detalle.id === cuotaId) {
              recibosAcumulados.push(detalle);
              totalrousArray = totalrous;

                montoTotal += monto;
            }
        });
    });
    cantidadCuota = recibosAcumulados.length;
    if (cantidadCuota > 0) {
      item_cobrar(recibosAcumulados);
    } else {
        alert('Por favor, selecciona al menos un recibo.');
    }
});


  function updateCobrarButtonText() {
    const selectedCount = $('input[name="seleccionar_cuota[]"]:checked').length;
    $cantidadSeleccionada.text(selectedCount);
  }



const calculateIndividualQuota = (cuotaId,montoPendiente,recordsTotal,idCliente) => {
      $('input[name="seleccionar_cuota[]"]:checked').each(function() {
        $(this).prop('checked', false);
      });

    recibosAcumulados = [];
    cantidadTotal = 0;
    totalrousArray = 0;
    cantidadCuota = 0;
    posicionSieteArray.forEach((detalle) => {
            if (detalle.id === cuotaId) {
                recibosAcumulados.push(detalle);
                totalrousArray = recordsTotal;
                montoTotal = montoPendiente;
            }
        });
    item_cobrar(recibosAcumulados);  
};



function item_cobrar(recibos) {	
    actualizarModalCobros();
    mostrarDetalles(recibos);
    cambio(1) ;
    // Mostrar el modal (si no está ya visible)
    if (alertaCaja == 1 ) {
        if (!$('#modal-id').is(':visible')) {
            $("#modal-id").modal('show');
        }  
    } else {
        alertacaja();
        
    }

}
// Función para actualizar el contenido del modal con los datos acumulados
function actualizarModalCobros() {
    limpiarElementos();
    mostrarFormularioPagos();
    mostrarMontoPendiente();

    // Mostrar detalles acumulados
    $("#cantidadTotal").text(cantidadTotal);
    $("#montoTotal").text(formatPrice(montoTotal, "₲ "));

    // Aquí puedes agregar más lógica para mostrar los detalles de todos los recibos acumulados
}

// Función para limpiar y ocultar elementos innecesarios
function limpiarElementos() {
    $("#recibo, #numero, #alerta, #pendiente, #prove").html("").css({"display": "none"});
    $('#c, #t, #s, #Cheque, #Tarjeta, #fabor').removeClass('active');
    $('#ParcialE, #ParcialC, #ParcialT').html('');
    $('.hidden').val('');
}

// Función para mostrar el formulario de pagos
function mostrarFormularioPagos() {
    $('#pagosdeuda').show();
    $('#pagosdeuda')[0].reset();
    $('#modal-header').show();
    $('#modal-id').modal('show');
}

// Función para mostrar el monto pendiente
function mostrarMontoPendiente() {
    $("#pendiente").html("Monto Pendiente: " + formatPrice(montoTotal, "₲ ")).css({"display": "block"});
    $("#monto").val(montoTotal);
    motopagar = montoTotal;
}



function mostrarDetalles(data) {
    // Remover el atributo 'required' del elemento con nombre 'numcheque'
    $("[name='numcheque']").removeAttr('required');

    // Limpiar el contenedor de detalles de recibos
    $('#detallesRecibos').empty();

    let totalMonto = 0;
    let idFacturas = [];
    let crEstados = [];
    let cfEstados = [];
    let cuotaNs = [];
    let cuotaPrecio = [];
    let idCC = [];




    // Recorrer cada recibo y agregar sus detalles al contenedor
    data.forEach((dataitem) => {
      let montoPendiente = parseFloat(dataitem.inporte_total - dataitem.total_caja_pagos);
        const detalleHTML = `
            <tr>
                <td><a href="pages">${dataitem.Num_Recibo}</a></td>
                <td>${dataitem.Num_cuota}</td>
                <td><span class="label label-success">${formatPrice(montoPendiente, "₲ ")}</span></td>
            </tr>
        `;
        $('#detallesRecibos').append(detalleHTML);
        totalMonto += parseFloat(montoPendiente);

        // Mostrar información del cliente o cliente según el caso
        if (dataitem.idCliente != null) {
            $('#idCliente').val(dataitem.idCliente);
            cliente = dataitem.idCliente;
            $("#prove").text(`Cliente: ${liteStringmncv(dataitem.Nombres, 10)} ${dataitem.Apellidos}`).css({"display": "block"});
        } else {
            $('#idCliente').val(dataitem.Proveedor_idProveedor);
            cliente = dataitem.Proveedor_id;
            $("#prove").text(`Proveedor: ${liteStringmncv(dataitem.Razon_Social, 10)}`).css({"display": "block"});
        }

        // Añadir el idFactura_Venta al array si está presente en dataitem
        idFacturas.push(dataitem.idFactura_Venta);
        // Añadir valores de crEstado, cfEstado y cuotaN a los arrays correspondientes
        crEstados.push(dataitem.creestado);
        cfEstados.push(dataitem.esta);
        cuotaNs.push(dataitem.Num_cuota);
        cuotaPrecio.push(montoPendiente);
        idCC.push(dataitem.id);
   
    });

    // Convertir los arrays en cadenas separadas por coma
    let idFacturasConcat = idFacturas.join(',');
    let crEstadosConcat = crEstados.join(',');
    let cfEstadosConcat = cfEstados.join(',');
    let cuotaNsConcat = cuotaNs.join(',');
    let cuotaPrecioConcat = cuotaPrecio.join(',');
    let idCCConcat = idCC.join(',');



    // Asignar los valores concatenados a los elementos del DOM
    $('#idF').val(idFacturasConcat);
    $('#crEstado').val(crEstadosConcat);
    $('#cfEstado').val(cfEstadosConcat);
    $('#cuotaN').val(cuotaNsConcat);
    $('#cuotaPrecio').val(cuotaPrecioConcat);
    $('#id').val(idCCConcat);
    $('#totalrous').val(totalrousArray);




    // Asignar otros valores según sea necesario
    $('#deudapagar').val(totalMonto);
    $('#m_total, #spanmontopagar, #spanmontopagarchque, #spanmontopagartar, #spanmontopagarfa').html(formatNum(totalMonto));

    // Limpiar y ajustar la interfaz según tus necesidades
    $('#ParcialE, #ParcialC, #ParcialT').html('');
    $('.hidden').val('');
    $('#tabefectivo').tab('show');
    $('#numcheque').removeAttr('required');
    $('.controlajustar').hide();

    // Establecer el foco en el elemento con id EF1 después de 500ms
    setTimeout(function() {
        $("#EF1").focus();
    }, 500);

    // Llamar a otras funciones necesarias, como reloadcheque()
    reloadcheque();

    // Restaurar el valor de save_method si es necesario
    save_method = 'add';

    // Ejecutar cualquier otra acción final si es necesario
}

































    function reload_table()
    {
     deuda_e.ajax.reload(); //reload datatable ajax 
     listar_deudas(idreload);
    }


function mostrarMensajeError(mensaje) {
  $('#loadingg').attr({disabled: 'disabled'});
  $('.alerter').html(mensaje).show();
  setTimeout(function() {
  $('#loadingg').removeAttr('disabled');
  $('.alerter').hide();
  }, 4000);
}
function procesarRespuestaAjax(data, id,msn) {
    if (data.res == 'error') {
    toastem.error("Disculpe los datos no han sido modificado por fabor intente nuevamente gracias!!");
    } else {
      var b = $('#loadingg');
      b.button("loadingg");
      setTimeout(function() {
        b.button("reset");
        alertasave(msn);
      }, 1000);
      setTimeout(function() {
        $("#pagosdeuda,#modal-header").hide();
        $('#modal-id').modal('hide');
        $("#cheque_tercero").val('').trigger("change");
        reload_table();
        atras();
      }, 2000);
    }
}
$('#pagosdeuda').submit(function(e) {
    e.preventDefault();

    var totalParcial = parseFloat($('#Totalparclal').val());
    var monto = parseFloat($('#monto').val());
    var cantidadCuota = 1; // Asegúrate de definir la variable cantidadCuota
    var alertasave = 'Pago Cuota Total'; // Asegúrate de	

    if (totalParcial < 1) {
        alertError("Es necesario ingresar algún monto.");
        return;
    }

    if (cantidadCuota > 1 && totalParcial < monto) {
        alertError("Agrega un monto igual o mayor al total. No se permiten pagos parciales en múltiples cuotas.");
        return;
    }

    var id = $('#id').val();
    var urlAjax;

    if (save_method === 'add' && totalParcial >= monto) {
        urlAjax = "cobroDeuda/1";
    } else {
        urlAjax = "cobroDeuda/2";
        alertasave = 'Pago Cuota Parcial'; 
    }

    $.ajax({
        url: urlAjax,
        type: "POST",
        data: $(this).serialize(),
        cache: false,
    })
    .done(function(data) {
        procesarRespuestaAjax(data, id,alertasave);
    })
    .fail(function() {
        toastem.error("Disculpa, hubo un error y los datos no se han modificado.");
    })
    .always(function() {
        console.log("complete");
    });
});


  $( "#cuenta_bancaria,#cheque_tercero,#afabor" ).select2( {
        allowClear: true,
        placeholder: 'Buscar',
        width: null,
        theme: "bootstrap"
      } );



  $("#cuenta_bancaria").select2({
    allowClear: true,
    placeholder: 'Busca y Selecciona',
    width: null,
    theme: "bootstrap"
}); 



$(".multi").select2({
theme: "bootstrap",
});        

});


$('.validat').keyup(function(event) {
let value = (this.value + '').replace(/[^0-9]/g, '');
value > 1 ?  $(this).val(formatPrice(value)) : false;

});
function reloadcheque(argument) {
  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").removeAttr('required');

}
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


  $('li#c a').click(async function() {
    if (bandera == true) {
      try {
        const response = await fetch("Deuda_empresa/cuenta_bancaria");
        const data = await response.text();
        $("#cheque_tercero").html(data);
        bandera = false;
      } catch (error) {
        console.log(error);
      }
    }
  });

  $('li#s a').click(async function() {
    if (banderas == true) {
      try {
        const response = await fetch("Compra/formapago/"+3+'/'+proveedor);
        const data = await response.text();
        $("#multifabor").html(data);
        banderas = false;
      } catch (error) {
        console.log(error);
      }
    }
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
        if (cliente > 0) {
          $(".controlajustar").show();
        }
        reerrt();
      } else {
        if (cliente > 0) {
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

const reerrt = () => {
$('#agregar_cuenta').removeAttr('checked');
$('#si_no').val('');
$('#ajustado').val('');
$('#valor').html('');
}

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



function deleteItem(id, id2, id3, id4, id5, cantidadRestante, tipopago, monto) {
    Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX delete datos de la base de datos
            $.ajax({
                url: "Deuda_cliente/ajax_delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    id1: id,
                    id2: id2,
                    id3: id3,
                    id4: id4,
                    cantidad: cantidadRestante,
                    tipopago: tipopago,
                    monto: monto
                }
            })
            .done(function(response) {  // done es igual a success solo que es más seguro
                Swal.fire("Eliminado!", "Empleado ha sido borrado.", "success");
                $('#listaCuotasPagadas').submit();
            })
            .fail(function() {
                Swal.fire("Error", "Error al intentar borrar", "error");
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Cancelled", "Sin acción:)", "error");
        }
    });
}

