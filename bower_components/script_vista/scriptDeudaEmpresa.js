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


let proveedor;
    $( "#CDE,#Compra" ).addClass( "active" );
    $( "#CDE,#C_D_E" ).addClass( "text-red" );


    
    const searchDeudaEmpresa = (event) => {
      event.preventDefault();
      const formData = $('#facturaCuota').serializeArray(); // Serializa el formulario a un array

      if (deuda_e) {
        deuda_e.destroy(); // Destruir la tabla existente antes de crear una nueva
      }

      deuda_e = $('#Deuda_e').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "Deuda_empresa/ajax_list_deuda_empresa",
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
            "url": "Deuda_empresa/ajax_list_pagadas",
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

$('#facturaCuota').on('submit', searchDeudaEmpresa);
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
  var val = d.slice(7);
  var element = 'details_' + d[0]; // Asumiendo que d[0] es único para cada fila

 var html = '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="' + element + '">' +
  '<tr class="danger">' +
  '<td>#</td>' +
  '<td>Descripcion</td>' +
  '<td>Fecha de Emision</td>' +
  '<td>Monto Pagado</td>' +
  '<td>Acciones</td>' +
  '</tr>';

$.ajax({
  type: 'POST',
  url: "Deuda_empresa/detale/" + val,
  dataType: 'json',
})
    .done(function(data) {
      if (data) {
        let total = data.length;
        let rows = [];
        for (let i = 0; i < data.length; i++) {
          let val = data[i];
          let rowClass = val.tipopago != null ? 'highlight' : 'success';
          let position = i + 1;
          // console.log('Creating row for position ' + position + ':', val);
          rows.push(`
            <tr class="${rowClass}">
              <td>${position}</td>
              <td>${val.des}</td>
              <td>${val.fec}</td>
              <td>${val.mon}</td>
              <td>
                <div class="pull-right hidden-phone">
                  <a class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" title="Eliminar" onclick="deleteItem(${val.idCCE},${val.idCA},${val.idFC},${val.idCF},${val.idMM},${val.Pagos},${data.length},${val.tipopago},${val.mon})">
                    <i class="fa fa-trash-o"></i>
                  </a>
                </div>
              </td>
            </tr>
          `);
        }
        html += rows.join('');
      }
      html += '</table>';
      $('#' + element).html(html);
    });


return html;
}




function listar_deudas(id) {
  $('#list').dataTable().fnDestroy();
  $('#Deudas').removeClass('active');$('#listados').addClass('active');
  $('#addtrpdf').attr('onclick', "pdf_exporte('lisdeuda/"+id+"')");
  $('#addtrexel').attr('href','Reporte_exel/lisdeuda/'+id+'');
  if (id > 0) {
  list = $('#list').DataTable({
            "processing": true, //Característica de control del indicador de procesamiento.
            "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
            // Datos de carga de contenidos de la tabla de un origen Ajax
            "ajax": {
                "url": "Deuda_empresa/listar_deudas/"+id,
                "type": "POST",
            },
            //Conjunto de columnas propiedades de definición de inicialización .
            "columnDefs": [
            {
              "targets": [ -1 ], // ultimass columnas
              "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
            },
            ],
  "order": [[ 0, 'acs' ], [ 1, 'acs' ],[ 2, 'acs' ],[ 3, 'acs' ],[ 4, 'acs' ],[ 5, 'acs' ]]
          });
  }
  }
  

function atras() {
$('#listados').removeClass('active');$('#Deudas').addClass('active');
}

function item_cobrar(id,monto,cantidadRestante,Proveedor)
{
    if (alertaCaja != 1 ) {
        alertacaja();
        return false;
    } 
       $("#recibo,#numero,#alertasadd,#pendiente,#prove").html("").css({"display":"none"});
            $('#c,#t,#s,#Cheque,#Tarjeta,#fabor').removeClass('active');
            $('.hidden').val('');
             $('#pagosdeuda')[0].reset();
             $("#pendiente").append('Monto Pendiente  :  '+formatPrice(monto)).css({"display":"block"});
             $("#monto").val(monto);
             motopagar=monto;
             $('#id').val(id);
             $("#pagosdeuda,#modal-header").show();
             $('#modal-id').modal('show');
            proveedor = Proveedor;
            cambio(1) ;
            //  $( "#fabor" ).load( "DeudaEmpresa/formapago/"+4+'/'+Proveedor );
            //  $( "#piesss" ).load( "DeudaEmpresa/formapago/"+5);
            //  $( "#cheque_tercero" ).load( "DeudaEmpresa/cuenta_bancaria");
      $.ajax({  
        type : 'POST',
        url: "Deuda_empresa/lis_deuda/"+id,
        dataType: 'json',
      })
      .done(function(data) {
                $("[name='numcheque']").removeAttr('required')
        if (data != null) {
            $("#recibo").append('Recibo Nº : '+data.Num_Recibo).css({"display":"block"});
            $("#numero").append('Cuota  Nº : '+data.Num_cuota).css({"display":"block"});
            $('#idProveedor').val(data.idProveedor);
            $("#prove").append('Proveedor  : '+data.Razon_Social+' '+  data.Vendedor).css({"display":"block"});
            $('#idF').val(data.idFactura_Compra);
            idreload = data.idFactura_Compra;
            $('#crEstado').val(data.crestado);
            $('#cfEstado').val(data.esta);
            $('#cuotaN').val(data.Num_cuota);
            $('#totalrous').val(cantidadRestante);
            reloadcheque();
            save_method ='add';

            $('#ParcialE,#ParcialC,#ParcialT').html('');
            $('.hidden').val('');
            $('#tabefectivo').tab('show');
            $('#numcheque').removeAttr('required');

            $('#deudapagar').val(monto);
            $('#m_total,#spanmontopagar,#spanmontopagarchque,#spanmontopagartar,#spanmontopagarfa').html(formatNum(monto));
            $('.controlajustar').hide();
            setTimeout(() => {
              $( "#EF1" ).focus();
            }, 500)




        }

      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
}

function reload_table()
{
  deuda_e.ajax.reload(); //reload datatable ajax 
  listar_deudas(idreload);
}

$(function() {

  function mostrarMensajeError(mensaje) {
    toastem.error(mensaje);
    $('#loadingg').attr({disabled: 'disabled'});
    $('.alerter').html(mensaje).show();
    setTimeout(function() {
    $('#loadingg').removeAttr('disabled');
    $('.alerter').hide();
    }, 4000);
  }
  function procesarRespuestaAjax(data, id) {
      if (data.res == 'error') {
      toastem.error("Disculpe los datos no han sido modificado por fabor intente nuevamente gracias!!");
      } else {
        var b = $('#loadingg');
        b.button("loadingg");
        setTimeout(function() {
          b.button("reset");
          alertasave('Datos Registrado correctamente');
        }, 1000);
        setTimeout(function() {
          $("#pagosdeuda,#modal-header").hide();
          $('#modal-id').modal('hide');
          $("#cheque_tercero").val('').trigger("change");
          reload_table();
          // pdf_exporte('pago', id);
        }, 2000);
      }
  }
    $('#pagosdeuda').submit(function(e) {
      e.preventDefault();
     var totalParcial = $('#Totalparclal').val();
      if (totalParcial < 1) {
      mostrarMensajeError("Es necesario ingresar algun Monto!!");
      return;
      }
      var id = $('#id').val();
      var urlAjax;
        if (save_method == 'add' && parseFloat(totalParcial) >= parseFloat($('#monto').val())) {

        urlAjax = "pagoDeuda/1";
        } else {
 
        urlAjax = "pagoDeuda/2";
        }
          $.ajax({
          url: urlAjax,
          type: "POST",
          data: $(this).serialize(),
          cache: false,
          })
          .done(function(data) {
          procesarRespuestaAjax(data, id);
          })
          .fail(function() {
          toastem.error("Disculpe hubo un error los datos no se an modificado");
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





function deleteItem(id, id2, id3, id4,id5,id6,cantidadRestante, tipopago, monto) {
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
                url: "Deuda_empresa/ajax_delete",
                type: "POST",
                dataType: "JSON",
                data: {
                    id1: id, // idCCuenta Corriente
                    id2: id2, //Caja_Pagos
                    id3: id3, // Factura compra
                    id4: id4, // CCE_idCuenta_Corriente_Empresa
                    id5: id5, // id movimiento
                    id6: id6, // pagos 

                    cantidad: cantidadRestante,
                    tipopago: tipopago,
                    monto: monto }
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
