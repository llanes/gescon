

<script type="text/javascript">
reloadcheque();
$('#controlajustar').hide();
var motopagar = $('#finalcarrito').val();
$(function () {


$('#deudapagar').val(motopagar);

$('#spanmontopagar,#spanmontopagarchque,#spanmontopagartar,#spanmontopagarfa').html(formatNumber.new(motopagar));

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
              $('#ParcialC').html(formatNumber.new(temporal));
              $('#parcial2').val(temporal);
              $('#Acheque_tercero').val(myArray2);
              $('#Acheque').val(myArray);
              var totalp = totalparcial(1);
              if (totalp>0) {
                  var final = operaciones(totalp,motopagar,'-');
                   $('#rerer').html(formatNumber.new(final));
                         if (final>0) {
                         $('#vuelto').html(formatNumber.new(final));
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

    $('#efectivo').keyup(function(event) {
          var  resultado=0;
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
         $('#parcial2').val(temporal);
         var totalp = totalparcial(1);
         if (totalp>0) {
              var final = operaciones(totalp,motopagar,'-');
                    $('#rerer').html(formatNumber.new(final));
                     if (final>0) {
                     $('#vuelto').html(formatNumber.new(final));
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
             $('#parcial2').val(resultado);
               var totalp = totalparcial(1);
               if (totalp>0) {
                    var final = operaciones(totalp,motopagar,'-');
                          $('#rerer').html(formatNumber.new(final));
                           if (final>0) {
                           $('#vuelto').html(formatNumber.new(final));
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
             $('#ParcialC').html('');
             $('#parcial2').val('');
             totalparcial(1)
          }

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
         $('#ParcialT').html(formatNumber.new(valor));
         $('#parcial3').val(valor);
         var totalp = totalparcial(1);
         if (totalp>0) {
              var final = operaciones(totalp,motopagar,'-');
                    $('#rerer').html(formatNumber.new(final));
                     if (final>0) {
                     $('#vuelto').html(formatNumber.new(final));
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
                 $('#valor').html(formatNumber.new(vueltototal));
                $('#vuelto').html('');
                $('#vueltototal').val('');


        } else {
                  $('#vuelto').html(formatNumber.new($('#ajustado').val()));
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
                 $("#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('disabled');
                 $("#efectivo,#fecha_pago,#cuenta_bancaria").attr('required','required');
             } else{
                  $("#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');

             }
        });




});

$(function () {

setTimeout(function()
{
  $('#EF2').focus();
}, 500);
});

function cambio(id) {
  toastem.success(id);
     var val = $("#EF"+id).val();
     var monto = $("#EF"+id).attr('data-monto');
     if (monto>0 && val>0 ) {
        var total = operaciones(val,monto,'*');
     }else{
         if (val>0) {
            $('#Moneda'+id).val(id);
            $('#cam'+id).val(monto);
            var total = val;
         }else{
           $('#Moneda'+id).val('');
           $('#cam'+id).val('');
           $("#EF"+id).val('');
           var total = '';

         }

     }
     $('#cm'+id).val(formatNumber.new(total));
     $('#ex'+id).val(total);
     $('#ParcialE').html(formatNumber.new(recorrer()));
     $('#parcial1').val(recorrer());
     var totalp = totalparcial(1);
    if (totalp>0) {
        var final = operaciones(totalp,motopagar,'-');
              $('#rerer').html(formatNumber.new(final));
               if (final>0) {
               $('#vuelto').html(formatNumber.new(final));
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

function totalparcial(xx) {
 var total = 0;
for (var i = 1; i < 5; i++) {
  var id = $('#parcial'+i).val()
  if (id > 0) {
    total += parseFloat(id);
  }

}
$('#Totalp').html(formatNumber.new(total));
$('#Totalparclal').val(total);
var final = operaciones(total,motopagar,'-');
                  if (final>0) {
                  $('#vuelto').html(formatNumber.new(final));
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





</script>