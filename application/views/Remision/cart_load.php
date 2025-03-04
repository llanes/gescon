                            <div style="height : auto; overflow : auto; ">
                            <table id="table" class="idRecorrer table" cellspacing="0" width="100%" >
                                        <thead>
                                          <tr>
                                                      <th class  ="text-danger">#</th>
                                                      <th class  ="text-danger">Codigo</th>
                                                      <th class  ="text-danger">Producto</th>
                                                      <th class  ="text-danger">Cantidad</th>
                                                      <th  class ="text-danger">Precio</th>
                                                      <th style  ="text-align:right" class ="text-danger">Subtotal</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                  <?php $i = 1; ?>
                                  <?php foreach ($this->cart->contents() as $items): ?>

                                          <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                                          <tr>
                                                  <td colspan="1">
                                                  <?php echo  $i ?>
                                                  </td>
                                                  <td colspan="1">
                                                  <?php echo $items['id']; ?>
                                                  </td>
                                                  <td>
                                                          <?php echo $items['name']; ?>
                                                  </td>
                                                  <td>
                                                          <?php echo $items['qty']; ?>
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
                                          </tr>
                                  <?php $i++; ?>
                                  <?php endforeach; ?>
                                  <input type="hidden" id="mnto" name="" value="<?php echo $this->cart->format_number($this->cart->total()) ?>">
                             </tbody>
                            </table >
                            </div>
                           <!-- cierre de carrito -->
                    </div>
              </div>
          </div>
          <!-- ///////////////////////////////////////////// -->


</div>
      </form>
<script type="text/javascript" charset="utf-8" async defer>
  $(function() {
          $("#mnt").html($('#mnto').val());
});
</script>
