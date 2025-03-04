                            <div style="height : 350px; overflow : auto; ">
                            <table id="principal" class="idRecorrer table" cellspacing="0" width="100%" >
                                        <thead>
                                          <tr>
                                                      <th class  ="text-danger">Cantidad</th>
                                                      <th   class ="text-danger">Nombre </th>
                                                      <th style="text-align:center"  class  ="text-danger">Precio </th>
                                                      <th style="text-align:right"   class ="text-danger">Devolucion <input type="checkbox" name="micheckbox" class="micheckbox" value=""></th>
                                                       <th style="text-align:left">  </th>
                                                      <th  style="text-align:center" class ="text-danger">Subtotal</th>
                                                      <th  style ="text-align:right"  class ="text-danger"style="width:125px;">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php $i = 1; ?>
                                  <?php foreach ($this->cart->contents() as $items): ?>

                                          <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                                          <tr>
                                               <td colspan="1">
                                                  <?php echo  $items['qty']; ?>
                                                  </td>
                                                  <td>
                                                          <?php echo $items['name']; ?>
                                                  </td>
                                                    <td style="text-align:center" >
                                                         ₲. <?php echo number_format($items['price'],0,',','.'); ?>
                                                          <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                                                                          <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

                                                                          <?php endforeach; ?>
                                                                          <?php foreach ($this->cart->product_option($items['rowid']) as $option_nam => $option_val): ?>
                                                                          <?php endforeach; ?>
                                                                          <?php foreach ($this->cart->product_option2($items['rowid']) as $option_nam => $option_ID):
                                                                          echo $option_ID ?>
                                                                          <?php endforeach; ?>
                                                         <?php endif; ?>
                                                  </td>
                                                    <td style="text-align:right"   >
                                                    max:<?php echo $option_value; ?> 
                                                   <?php echo form_input(array(
                                                    'disabled' => 'disabled',
                                                   'name' => $i.'[qty]',
                                                   'class' => $i.'[qty] valid',
                                                    'type' => 'text',
                                                    'value' => '',
                                                    'data-id2' => $option_val,
                                                    'data-id' => $option_value,
                                                     'id' => $items['rowid'],
                                                     'placeholder' => '',
                                                    'data-monto' => $items['price'],
                                                    'style' => "width:100px"
                                                   )); ?> 
                                                  </td>
                                                  <td style="text-align:left">Stock:<?php echo $option_val; ?></td>
                                                  <td id="recorerr" style="text-align:center; " class="<?php echo $items['rowid']?>">
                                                  </td>
                                                  <td style="text-align:right" width="130">
                                                        <div class="pull-right hidden-phone">
                                                              <a class="btn btn-danger btn-xs fa fa-trash-o"  
                                                                      onclick="delete_rowid('<?php echo $items['rowid']?>')">
                                                              </a>
                                                         </div>
                                                  </td>
                                          </tr>
                                  <?php $i++; ?>
                                  <?php endforeach; ?>
                             </tbody>
                            </table >
                            </div>
                            <div class="col-md-12 bg-gray color-palette">
                              <div class="col-md-4">
                                  <label for=""  style="text-align:left">

                                  </label>
                              </div>
                              <div class="col-md-4">
                                  <label for="" style="text-align:center">

                                  </label>
                              </div>
                             <div class="col-md-4">
                                  <label for=""  style="text-align:right">
                                      <strong>Monto Perdida &nbsp;</strong>₲. <span class='totalesiva'></span>
                                      <input type="hidden" name="total" id="total" value="">
                                  </label>
                              </div>

                            </div >
                            <div class="col-md-12">
                            <br>  
                                                 <div class="pull-right">
                                                    <?php if ($this->cart->format_number($this->cart->total()) != '0') { ?>
                                                          <button disabled type ="submit" id="loading" name="loading" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off">
                                                          <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave">Devolver</span> </button>&nbsp;&nbsp;


                                                          

                                                  <?php } ?>
                                                          <button type ="reset" onclick="Limpiar('1');" class="btn btn-sm btn-info">
                                                          <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                                          <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
                                                          <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                                                  </div> 

                            </div>
                            <input type="hidden" name="cart_total" id="cart_total" value="">
                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
      </form>

<script type="text/javascript" charset="utf-8" async defer>
$(function () {
    $('#tipooccion').change(function() {
    var id = $('#tipooccion').val();
    if (id == 1) {
              $('.micheckbox').prop('checked', false);
              $(".valid,#loading").prop('disabled', true).val('');
              $('#cart_total').val('');
              $('.totalesiva,#recorerr').html('');
    }

  });
});
   $('.valid').keyup(function(event) {
           var cond = $('#tipooccion').val();
           var longit = $(this).attr('data-id');
           var stock = $(this).attr('data-id2');
           var num = $(this).val();
           this.value     = (this.value + '').replace(/[^0-9]/g, '');
           if (cond == 1) {
               if (parseFloat(num) > parseFloat(stock) || parseFloat(num) > parseFloat(longit) || parseFloat(num) == 0) {
                 $(this).val('')
               }else{
                 $('#'+this.id).attr({max:longit,maxlength:longit.length,max:longit.length});
               }
           }else{
               if (parseFloat(num) > parseFloat(longit) || parseFloat(num) == 0) {
                 $(this).val('')
               }else{
                 $('#'+this.id).attr({max:longit,maxlength:longit.length,max:longit.length});
               }
           }

    });
   $('input').focusout(function(event) {
                  var id        = $(this).attr('id');
                  var val       = $(this).val();
                  var perdida   = $(this).attr('data-monto');
                  var resultado = parseFloat(perdida) * parseFloat(val);
                  if (val > 0) {
                  $('.'+id).html(resultado);
                  }
                var resultVal = 0.0;
                $("#principal tbody tr").each(function (index) 
                {
                    var campo1; 

                    $(this).children("#recorerr").each(function (index2) 
                    {
                        switch (index2) 
                        {
                            case 0: 
                            campo1 = $(this).text();
                                    break;
                        }
                    })
                        if (campo1 == 0 ) {
                       }else{
                         resultVal += parseFloat(campo1);
                       }
                })
                 if (resultVal == '' || resultVal == 0) {
                        $('#loading').prop('disabled', true);

                      }else{
                        $('#loading').prop('disabled', false);
                      }
              $('#cart_total').val(resultVal);
              $('.totalesiva').html(resultVal);
      });
  $(function() {
        $(".micheckbox").on( 'change', function() {
        if( $(this).is(':checked') ) {
            // Hacer algo si el checkbox ha sido seleccionado
            // alert("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
             $(".valid").prop('disabled', false);
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            // alert("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
              $(".valid,#loading").prop('disabled', true).val('');
              $('#cart_total').val('');
              $('.totalesiva,#recorerr').html('');
            }
        });

        // alert(iva);
        $('.idRecorrer td.inpuesto').each(function(){ //filas con clase 'contenido_caja', especifica una clase, asi no tomas el nombre de las columnas
         iva = $(this).attr('data-id');
         if (iva == '') {

         } else {
                if (iva == 5) {
                 inpuesto_cinco += parseFloat($(this).attr('data-subtotal').replace(',', ','));
                 num1 = inpuesto_cinco/21;
                 total_cinco = num1.toFixed(3);
                 $(".total_iva_cinco").html(total_cinco);
                 $("#iva_cinco").val(total_cinco);
               }
               if (iva == 10) {
                 inpuesto_diez += parseFloat($(this).attr('data-subtotal').replace(',', ','));
                 // alert(inpuesto_diez);
                 num2 = inpuesto_diez/11;
                 total_diez = num2.toFixed(3);
                 $(".total_iva_diez").html(total_diez);
                 $("#iva_diez").val(total_diez);
               }
                var num3 = num1 + num2;
                total_iva = num3.toFixed(3);
                $(".totalesiva").html(total_iva);
                $("#lesiva").val(total_iva);

         }
        });
});

</script>