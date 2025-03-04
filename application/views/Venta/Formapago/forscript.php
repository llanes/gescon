

<script type="text/javascript">
reloadcheque();
$('#controlajustar').hide();
var motopagar = $('#finalcarrito').val();
var Cliente   = $('[name="Cliente"]').val();
$(function () {
$('#deudapagar').val(motopagar);
$('#spanmontopagar,#spanmontopagarchque,#spanmontopagartar,#spanmontopagarfa').html(formatNumber.new(motopagar));
    $('#efectivo').keyup(function(event) {
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()<1) {
         var valor = '0';
         $('#numcheque').removeAttr('required');
      }else{
         var valor = $(this).val();
         $('#numcheque').attr('required','required');
      }
         $('#ParcialC').html(formatNumber.new(valor));
         $('#parcial2').val(valor);
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
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                   $('#rerer').html('');

           }
    });

   $('#efectivoTarjeta').keyup(function(event) {
      // var este =+ parseFloat($(this).val());
      if ($(this).val()<1) {
         var valor = '0';
      }else{
         var valor = $(this).val();
      }

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
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
                   $('#rerer').html('');
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
                 $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('disabled');
                 $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('required','required');
             } else{
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');

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
       var total = val;
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