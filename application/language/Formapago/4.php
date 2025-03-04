 <div class="col-md-6  col-md-offset-3 ">
      <div class="form-group">
         <label>Monto a Pagar &nbsp;<span id="spanmontopagar"></span>&nbsp;₲S.</label>
         <div class="input-group ">
          <span class="input-group-addon">
            <i class="fa fa-money" aria-hidden="true"></i>
          </span>
  <select  name="multi" id="multi"  Size="4" class="form-control select2-multiple multi blocqueac" multiple="multiple" style="width: 100%" >
              <?php 
                    foreach($cuenta as $key => $value)
                    {
                    ?>
                    <option value="<?php echo $value->Monto.','.$value->idCuenta_Fabor ?>"><?php echo number_format($value->Monto,0,'.',',')?>  ₲S.</option>
                    <?php
                    }
              ?>
          </select>
        </div>
          <span class ="mult text-danger"></span>   <!--    INDICADORES DE ERROR A TRAVEZ DE AJAX -->
      </div>
            <input type="hidden" name="cobroen" id="cobroen" value="3">
</div>
<div class="col-md-5 col-md-offset-7">
      <div class="form-group text-danger-align-right">
        <h3>
                 <p id="" class=" text-info">Parcial:&nbsp;<span class="text-danger" id="Parcialf"></span>&nbsp;₲s.</p>
                 <input type="hidden" name="parcial4" id="parcial4" value="">
                 <input type="hidden" name="matriscuanta" id="matriscuanta" value="">
                 <input type="hidden" name="matris" id="matris" value="">
        </h3>
      </div>
</div>

  <script type="text/javascript">
$(function() {
$(".multi").select2({
            placeholder: "Selecciona",
          theme: "bootstrap"
        });
});

   $('#multi').change(function() {
    var  resultado=0;
    var myArray = [ ];
    var myArray2 = [ ];
              $("#multi :selected").map(function(i, el) {
                    var element        = $(el).val().split(',');
                    myArray.push(element[0]);
                    myArray2.push(element[1]);
                });
                $.each( myArray, function( key, val ) {
                    if (val >= 0) {
                      resultado += parseFloat(val);
                    }
                });
              $('#Parcialf').html(formatNumber.new(resultado));
              $('#parcial4').val(resultado);
              $('#matris').val(myArray);
              $('#matriscuanta').val(myArray2);
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


  </script> 