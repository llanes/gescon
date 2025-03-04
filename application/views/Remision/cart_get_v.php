                            <div style="height : 350px; overflow : auto; ">
                            <table id="table" class="idRecorrer table" cellspacing="0" width="100%" >
                                        <thead>
                                          <tr>
                                                      <th class  ="text-danger">Cantidad</th>
                                                      <th class  ="text-danger">Nombre</th>
                                                      <th  class ="text-danger">Precio</th>
                                                      <th style  ="text-align:right" class ="text-danger">Subtotal</th>
                                                      <th  style ="text-align:right"  class ="text-danger"style="width:125px;">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php $i = 1; ?>
                                  <?php foreach ($this->cart->contents() as $items): ?>

                                          <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                                          <tr>
                                                  <td colspan="1">
                                                    <?php echo form_hidden(array('name' => $i.'[qty]','value' => $items['qty'],'maxlength' => '15', 'size' => '5')); ?>
                                                    <input 
                                                    type="number" 
                                                    min="1"
                                                     min="1" max="99999999999"
                                                    id="<?php echo $items['rowid']?>"
                                                    maxlength="15"
                                                    value="<?php echo $items['qty']?>"
                                                    onchange="update_rowid('<?php echo $items['rowid']?>');"
                                                    style="width:80px"
                                                    >
                                                  </td>
                                                  <td>
                                                          <?php echo $items['name']; ?>
                                                  </td>
                                                          <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>


                                                                          <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>


                                                                          <?php endforeach; ?>


                                                          <?php endif; ?>

                                                  <td >
                                                      <?php echo $items['price']; ?>
                                                  </td>
                                                  </td>
                                                  <td style="text-align:right" 
                                                      class='inpuesto' 
                                                      data-id="<?php echo $option_value;?>"
                                                      data-subtotal="<?php echo $this->cart->format_number($items['subtotal']); ?>">₲. 
                                                      <?php echo $this->cart->format_number($items['subtotal']); ?>
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
                             <table id="table" class="table" cellspacing="0" width="100%" >
                              <tbody>
                                  <tr class="success" role="row">
                                          <td colspan="6" >
                                            <label for="" class="col-xs-12 " style="text-align:right">
                                                  <strong>Monto Total &nbsp;</strong>₲. <span id="total"><?php echo $this->cart->format_number($this->cart->total()); ?></span>
                                            </label>
                                          </td>
                                   </tr>
                                        </tbody>

                            </table >
                            <div class="col-md-12">

                                                 <div class="pull-right">
                                                    <?php if ($this->cart->format_number($this->cart->total()) != '0') { ?>
                                                         <button type="submit" id="loading" name="loading" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off" >
                                                                                    <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave"  >Guardar</span> 
                                                                             </button>&nbsp;&nbsp;

                                                  <?php } ?>
                                                          <button type ="reset" onclick="Limpiar('1');" class="btn btn-sm btn-info">
                                                          <i class     ="fa fa-refresh "></i> Limpiar</button>&nbsp;
                                                          <button type ="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" >
                                                          <span class  ="glyphicon glyphicon-floppy-remove"></span> Cancelar</button>     
                                                  </div> 

                            </div>
                            <input type="hidden" name="monto" id="monto" value="<?php echo $this->cart->format_number($this->cart->total()); ?>">
                             <input type="hidden" name="iva_cinco" id="iva_cinco" value='' class="form-control" value="">
                            <input type="hidden" name="iva_diez" id="iva_diez"    value='' class="form-control" value="">
                            <input type="hidden" name="lesiva" id="lesiva"      value=''class="form-control" value=""> 
                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
      </form>
<script type="text/javascript" charset="utf-8" async defer>
  $(function() {
        var inpuesto_cinco,iva,inpuesto_diez,total_cinco,total_diez,total_iva,num1,num2 =0;
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
                 num2 = inpuesto_diez/11;
                 total_diez = num2.toFixed(3);
                 $(".total_iva_diez").html(total_diez);
                 $("#iva_diez").val(total_diez);
               }
                var num3 = num1 + num2;
                total_iva = num3.toFixed(3);
                $(".totalesiva").html(total_iva);
                $("#lesiva").val(total_iva);
                $('#totaliva').val(total_iva);

         }
        });
});
</script>