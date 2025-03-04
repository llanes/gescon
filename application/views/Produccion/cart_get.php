           <div style="height : 300px; overflow : auto;">
    <table  class="idRecorrer table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class  ="text-danger">Cantidad</th>
                <th class  ="text-danger">Nombre</th>
                <th  class ="text-danger">Precio</th>
                <th  class ="text-align-center text-danger">Unidad Medida</th>
                <th  class="text-align-left text-danger">Subtotal</th>
                <th   class="text-align-right text-danger col-md-1">Acciones</th>
            </tr>
        </thead>
        <tbody id="Recorrer">
            <?php 
            $i = 1; 
            $cart_contents = $this->cart->contents();
            foreach ($cart_contents as $items): 
                $form_hidden = form_hidden($i.'[rowid]', $items['rowid']);
                $options = $this->cart->has_options($items['rowid']) ? $this->cart->product_options($items['rowid']) : FALSE;

              $iva = ($options['iva'] == 11) ? '10' : $options['iva'];
            ?>
            <tr>
                <td colspan="1">
                    <?php echo $form_hidden; ?>
                    <input 
                    type="text" 
                    id="<?php echo $items['rowid']?>"
                    data-idval=""
                    maxlength="15"
                    value="<?php echo $items['qty']?>"
                    data-id2 ="<?php echo $option_val?>"
                    data-val ="<?php echo $items['qty']+$option_val?>"
                    onchange="update_rowid('<?php echo $items['rowid']?>');"
                    ONINPUT="update_rowid('<?php echo $items['rowid']?>');"
                    class="<?php echo $items['id']?> valid"
                    style="width:80px"
                    >
                </td>
                <td><?php echo $items['name']; ?></td>
                <td>₲. <?php echo  number_format($items['price']); ?></td>
                <td class="text-align-center">
                    <?php 

                    if ($options) {
                      echo $options['Unidad_Medida'];
     
                    }
                    ?>
                </td>
                <td class="text-align-left"  id='recorrerinp' 
                    data-id="<?php echo $iva;?>"
                    data-subtotal="<?php echo number_format($items['subtotal']); ?>">₲. 
                    <?php echo number_format($items['subtotal']); ?>
                </td>
                <td style="text-align:right" width="130">
                    <div class="pull-right hidden-phone">
                        <a class="btn btn-danger btn-xs fa fa-trash-o"  
                            onclick="delete_rowid('<?php echo $items['rowid']?>')">
                        </a>
                    </div>
                </td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>
</div>

<div class="col-md-12 bg-gray color-palette">
    <div class="col-md-4">
        <label for="" class="text-left">
            <strong></strong> <span class=''></span>
        </label>
    </div>
    <div class="col-md-4">
        <label for="" class="text-center">
            <strong></strong><span class=''></span>
        </label>
    </div>
    <div class="col-md-4">
        <h4 class="text-right">
            <strong>Monto Total &nbsp;</strong>₲. 
            <?php 
            $cart_total = number_format($this->cart->total());

            echo $cart_total; 
            ?>
        </h4>
    </div>
</div>
<div class="col-md-12">
    <br>  
    <div class="pull-right">
        <?php if ($cart_total != '0') { ?>
            <button type="submit" id="loading" name="loading" class="btn btn-sm btn-success" data-loading-text="Procesando..." autocomplete="off">
                <span class="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> 
            </button>&nbsp;&nbsp;
        <?php } ?>
        <button type="reset" onclick="Limpiar('1');" class="btn btn-sm btn-info">
            <i class="fa fa-refresh "></i> Limpiar
        </button>&nbsp;
        <button type="button" class="btn btn-sm btn-danger" data-toggle="collapse" onclick="resetear()" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
        </button>     
    </div> 
</div>
<input type="hidden" name="iva_cinco" id="iva_cinco" value=''>
<input type="hidden" name="iva_diez" id="iva_diez" value=''>
<input type="hidden" name="lesiva" id="lesiva" value=''> 
<input type="hidden" name="cart_total" id="cart_total" value="<?php echo $cart_total; ?>">
<input type="hidden" name="finalcarrito" id="finalcarrito" value="">
<!-- cierre de carrito -->
</div>
</div>
</div>
<!-- ///////////////////////////////////////////// -->
</div>
</form>
  
<script type="text/javascript" charset="utf-8" async defer>
    var $valid = $('.valid');
     $valid.keyup(function(event) {
        var maxVal = save_method == 'update' ? $(this).attr('data-val') : $(this).attr('data-id2');
        var num = $(this).val();
        var error = save_method == 'update' ? 'Cantidad maxima a modificar:  ' : 'Cantidad maxima en stock:  ';
         this.value = (this.value + '').replace(/[^0-9]/g, '');
         if (parseFloat(num) > parseFloat(maxVal)) {
            toastem.error(error + maxVal);
            $(this).val('')
            $("#detalle").load("<?php echo base_url('Produccion/load');?>");
        } else {
            $('#'+this.id).attr({max: maxVal, maxlength: maxVal.length});
        }
    });
     var inpuesto_cinco = 0, iva = 0, inpuesto_diez = 0, total_cinco = 0, total_diez = 0, total_iva = 0, num1 = 0, num2 = 0;
     $(function() {
        $('#Recorrer td#recorrerinp').each(function(){
            iva = $(this).attr('data-id');
             if (iva > 0) {
                if (iva == 5) {
                    inpuesto_cinco += parseFloat($(this).attr('data-subtotal'));
                    num1 = inpuesto_cinco/21;
                    total_cinco = num1.toFixed(3);
                }
                if (iva == 10) {
                    inpuesto_diez += parseFloat($(this).attr('data-subtotal'));
                    num2 = inpuesto_diez/11;
                    total_diez = num2.toFixed(3);
                }
                var num3 = num1 + num2;
                total_iva = num3.toFixed(3);
                $("#lesiva").val(total_iva);
            }
        });
    });     
</script>