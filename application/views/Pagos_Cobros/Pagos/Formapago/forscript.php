

<script type="text/javascript">
reloadcheque();
$('#controlajustar').hide();
var motopagar = $('#monto').val();
$(function () {
$('#deudapagar').val(motopagar);
$('#spanmontopagar,#spanmontopagarchque,#spanmontopagartar,#spanmontopagarfa').html(formatNumber(motopagar));
   $('#cheque_tercero').change(function() {
    var  resultado=0;
    var myArray = [ ];
    var myArray2 = [ ];
     var efecti = $('#efectivo').val() ;
              $("#cheque_tercero :selected").map(function(i, el) {
                    var element        = $(el).val().split(',');
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
              $('#ParcialC').html(formatNumber(temporal));
              $('#parcial2').val(temporal);
              $('#Acheque_tercero').val(myArray2);
              $('#Acheque').val(myArray);
              var totalp = totalparcial(1);
              if (totalp>0) {
                  var final = operaciones(totalp,motopagar,'-');
                   $('#rerer').html(formatNumber(final));
                         if (final>0) {
                         $('#vuelto').html(formatNumber(final));
                         $('#vueltototal').val(final);
                         $('#rerer').html('');
                         if (Cliente) {
                          $("#controlajustar").show();
                          reerrt();
                         }

                         }else{
                            $('#agregar_cuenta').removeAttr('checked');
                            $('#vuelto').html('');
                            $('#vueltototal').val('');
                            $("#controlajustar").hide();
                            $('#si_no').val('');
                            $('#ajustado').val('');
                            $('#valor').html('');

                         }
               }else{
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#vuelto').html('');
                  $('#vueltototal').val('');
                  $("#controlajustar").hide();
                  $('#matriscuanta').val('');
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                   $('#rerer').html('');

               }



  });
$('#efectivo').keyup(function (event) {
    let resultado = 0;
    let myArray = [];
    this.value = (this.value + '').replace(/[^0-9]/g, '');
    const $numCheque = $('#numcheque');
    const $chequeTercero = $("#cheque_tercero :selected");
    const $parcialC = $('#ParcialC');
    const $parcial2 = $('#parcial2');
    const $rerer = $('#rerer');
    const $vuelto = $('#vuelto');
    const $vueltototal = $('#vueltototal');
    const $controlajustar = $("#controlajustar");
    const $agregarCuenta = $('#agregar_cuenta');
    const $siNo = $('#si_no');
    const $ajustado = $('#ajustado');
    const $valor = $('#valor');
     if ($(this).val()) {
        $numCheque.attr('required', 'required');
        let valor = $(this).val();
        $chequeTercero.map(function (i, el) {
            let element = $(el).val().split(',');
            myArray.push(element[0]);
        });
        myArray.forEach(function (val) {
            if (val >= 0) {
                resultado += parseFloat(val);
            }
        });
        let temporal = operaciones(resultado, valor, '+');
        $parcialC.html(formatNumber(temporal));
        $parcial2.val(temporal);
    } else {
        $numCheque.removeAttr('required');
        $chequeTercero.map(function (i, el) {
            let element = $(el).val().split(',');
            myArray.push(element[0]);
        });
        myArray.forEach(function (val) {
            if (val >= 0) {
                resultado += parseFloat(val);
            }
        });
        if (resultado > 0) {
            $parcialC.html(formatNumber(resultado));
            $parcial2.val(resultado);
        } else {
            $parcialC.html('');
            $parcial2.val('');
            totalparcial(1)
        }
    }
     let totalp = totalparcial(1);
    if (totalp > 0) {
        let final = operaciones(totalp, motopagar, '-');
        $rerer.html(formatNumber(final));
        if (final > 0) {
            $vuelto.html(formatNumber(final));
            $vueltototal.val(final);
            $rerer.html('');
            $controlajustar.show();
            reerrt();
        } else {
            $agregarCuenta.removeAttr('checked');
            $vuelto.html('');
            $vueltototal.val('');
            $controlajustar.hide();
            $siNo.val('');
            $ajustado.val('');
            $valor.html('');
        }
    } else {
        $agregarCuenta.removeAttr('checked');
        $vuelto.html('');
        $vueltototal.val('');
        $controlajustar.hide();
        $siNo.val('');
        $ajustado.val('');
        $valor.html('');
        $rerer.html('');
    }
});

   $('#efectivoTarjeta').keyup(function(event) {
      var este =+ parseFloat($(this).val());
      if ($(this).val()<1) {
        $(this).val('')
      }
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()) {
         var valor = $(this).val();
         $('#ParcialT').html(formatNumber(valor));
         $('#parcial3').val(valor);
         var totalp = totalparcial(1);
         if (totalp>0) {
              var final = operaciones(totalp,motopagar,'-');
                    $('#rerer').html(formatNumber(final));
                     if (final>0) {
                     $('#vuelto').html(formatNumber(final));
                     $('#vueltototal').val(final);
                      $('#rerer').html('');
                      $("#controlajustar").show();
                      reerrt();
                     }else{
                        $('#agregar_cuenta').removeAttr('checked');
                        $('#vuelto').html('');
                        $('#vueltototal').val('');
                        $("#controlajustar").hide();
                        $('#si_no').val('');
                        $('#ajustado').val('');
                        $('#valor').html('');

                     }
           }else{
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
         $('#parcial3').val('');
         totalparcial(1);
      }
    });

      $("#agregar_cuenta").on( 'change', function() {
        // var diferencia = parseInt($('#diferencia').val());
        var vueltototal = $('#vueltototal').val();
        if( $(this).is(':checked') ) {
              $('#si_no').val('1');
                $('#ajustado').val(vueltototal);
                 $('#valor').html(formatNumber(vueltototal));
                $('#vuelto').html('');
                $('#vueltototal').val('');


        } else {
                  $('#vuelto').html(formatNumber($('#ajustado').val()));
                  $('#vueltototal').val($('#ajustado').val());
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                  // $('.blocqueac').removeAttr('disabled');

        }
    });

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



});

$(function () {

setTimeout(function()
{
  $('#EF1').focus();
}, 500);
});

function cambio(id) {
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
     $('#cm'+id).val(formatNumber(total));
     $('#ex'+id).val(total);
     $('#ParcialE').html(formatNumber(recorrer()));
     $('#parcial1').val(recorrer());
     var totalp = totalparcial(1);
    if (totalp>0) {
        var final = operaciones(totalp,motopagar,'-');
              $('#rerer').html(formatNumber(final));
               if (final>0) {
               $('#vuelto').html(formatNumber(final));
               $('#vueltototal').val(final);
                $('#rerer').html('');
                $("#controlajustar").show();
                reerrt();
               }else{
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#vuelto').html('');
                  $('#vueltototal').val('');
                  $("#controlajustar").hide();
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');

               }
     }else{
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#vuelto').html('');
                  $('#vueltototal').val('');
                  $("#controlajustar").hide();
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                   $('#rerer').html('');
     }
}

function recorrer() {
  var val = parseFloat($('#val').val());
  var resultVal = 0;
  for (var i = 1; i <= val; i++) {
    var res = parseFloat($('#ex'+i).val());
    if (res > 0) {
      resultVal += res;
    }
  }
  return resultVal;
}

function totalparcial() {
  var total = 0;
  var parciales = [];
   // Buscar los elementos del DOM una sola vez y guardarlos en un array
  for (var i = 1; i < 5; i++) {
    parciales.push(parseFloat($('#parcial' + i).val()));
  }
   // Sumar los números del array utilizando reduce
  total = parciales.reduce(function(acc, val) {
    return acc + (val > 0 ? val : 0);
  }, 0);
   // Actualizar el contenido del DOM una sola vez
  $('#Totalp').html(formatNumber(total));
  $('#Totalparclal').val(total);
   return total;
}


function reerrt(argument) {
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
}


function reloadcheque(argument) {
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');
}

  $('.blocqueac').keyup(function(event) {
    if ($(this).val() < 1) {
      $(this).val('');
    }else{
      this.value = (this.value + '').replace(/[^0-9]/g, '');
    }

    });




</script>