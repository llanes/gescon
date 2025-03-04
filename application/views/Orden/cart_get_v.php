<div style="height : 350px; overflow : auto; ">
  <table id="table" class="idRecorrer table" cellspacing="0" width="100%" >
    <thead>
      <tr class="danger">
        <th class  ="">#</th>
        <th class  ="">Cantidad</th>
        <th class  ="">Nombre</th>
        <th  class ="">Precio</th>
        <th  class ="">Descuento</th>
        <th  style="text-align:right">Impuesto</th>

        <th style  ="text-align:right" class ="">Subtotal</th>
        <th  style ="text-align:right"  class =""style="width:100px;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php 
          $i = 1; 
          $data = array();
          
         foreach ($this->cart->contents() as $items): 
              if ($this->cart->has_options($items['rowid']) == TRUE) {
                  $data = $this->cart->product_options($items['rowid']);
              }

         echo form_hidden($i.'[rowid]', $items['rowid']).'
        <tr >
          <td colspan="1">
             '.$i.' 
          </td>
          <td colspan="1">
            '.form_hidden(array('name' => $i.'[qty]','value' => $items['qty'],'maxlength' => '15', 'size' => '5')).'
            <input type="number" min="1" min="1" max="99999999999" id="'.$items['rowid'].'" maxlength="15" value="'.$items['qty'].'" style="width:80px" class="cantidad">
          </td>
          <td>
            '.$items['name'].'
          </td>
          <td >
            ₲. '.$this->cart->format_number($data['poriginal']).' 
          </td>
          <td class="selec">
          <select name="descuento" data-tyf="'.$data['t_f'].'"  id="select'.$items['rowid'].'" data-id="'.$items['rowid'].'" data-qty="'.$items['qty'].'" data-price="'.$data['poriginal'].'" data-i="'.$data['iva'].'" class="descuento">
          <option value="0">select</option>
          <option value="0.02">2 %</option>
          <option value="0.05">5 %</option>
          <option value="0.10">10 %</option>
          <option value="0.15">15 %</option>
          <option value="0.20">20 %</option>
          <option value="0.25">25 %</option>
          <option value="0.30">30 %</option>
          <option value="0.35">35 %</option>
          <option value="0.40">40 %</option>
          <option value="0.45">45 %</option>
          <option value="0.50">50 %</option>
          </select>
          <span id="val" class="danger">'.$this->cart->format_number($data['descuento']).'</span>
          </td>
          <td style="text-align:right" colspan="" rowspan="" headers="">'.$data['iva'].' %</td>
          <td style="text-align:right" 
          class="inpuesto" 
          data-id="'.$data['iva'].'"
          data-subtotal="'.$this->cart->format_number($items['subtotal']).'">₲. '.$this->cart->format_number($items['subtotal']).'
        </td>
        <td style="text-align:right" width="130">
          <div class="pull-right hidden-phone">
            <a class="btn btn-danger btn-xs fa fa-trash-o"  
            onclick="delete_rowid('.$items['rowid'].')">
             </a>
          </div>
        </td>
    </tr>';
     $i++;
   endforeach; ?>
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
      <button type ="submit"  class="btn btn-sm btn-success">
        <span class  ="glyphicon glyphicon-floppy-disk" id="btnSave">Guardar</span> </button>&nbsp;&nbsp;

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
    $('.cantidad').change(function(event) {
      var j = $(this);
      update_rowid(j.val(),j.attr('id'));
    }).keyup(function(event) {
      var j = $(this);
      update_rowid(j.val(),j.attr('id'));
    });

    $('[name="descuento"]').change(function(event) {
      $val   = $(this).val();
      $id    = $(this).data('id');
      $qty   = $(this).data('qty');
      $price = $(this).data('price');
      $i     = $(this).data('i');
      update_descuento($val,$id,$qty,$price)

    });
    function update_descuento(val,id,qty,price) {
      $.ajax({
        url: "<?php echo base_url('Orden_compra/update_descuento');?>",
        type: 'POST',
        dataType: 'JSON',
        data: {
          val: val,
          id: id,
          qty: qty,
          price: price,
          i: $i

        },
      })
      .done(function(data) {
        if (data) {
          $( "#detalle" ).load( "<?php echo base_url('Orden_venta/loader');?>" );
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }
    $('.idRecorrer  td.selec select.descuento').each(function(index, el) {
      $(this).val($(this).data('tyf')) 
    });
// $('.idRecorrer td.inpuesto').each(function(){ //filas con clase 'contenido_caja', especifica una clase, asi no tomas el nombre de las columnas
//   iva = $(this).attr('data-id');
//   if (iva == '') {
//   } else {
//     if (iva == 5) {
//       inpuesto_cinco += parseFloat($(this).attr('data-subtotal').replace(',', ','));
//       num1 = inpuesto_cinco/21;
//       total_cinco = num1.toFixed(3);
//       $(".total_iva_cinco").html(total_cinco);
//       $("#iva_cinco").val(total_cinco);
//     }
//     if (iva == 10) {
//       inpuesto_diez += parseFloat($(this).attr('data-subtotal').replace(',', ','));
//       num2 = inpuesto_diez/11;
//       total_diez = num2.toFixed(3);
//       $(".total_iva_diez").html(total_diez);
//       $("#iva_diez").val(total_diez);
//     }
//     var num3 = num1 + num2;
//     total_iva = num3.toFixed(3);
//     $(".totalesiva").html(total_iva);
//     $("#lesiva").val(total_iva);
//     $('#totaliva').val(total_iva);

//   }
// });
});
</script>