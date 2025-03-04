<div style="height : 370px; overflow : auto; ">
  <table id="table" class="idRecorrer table table-striped" cellspacing="0" width="100%" >
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
            <input type="number" min="1" min="1" max="99999999999"  data-id="'.$items['rowid'].'" maxlength="15" value="'.$data['poriginal'].'"  style="width:80px" name="dataprice" data-tyf="'.$data['t_f'].'" data-qty="'.$items['qty'].'" data-iva="'.$data['iva'].'" >
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
          <strong>Monto Total &nbsp;</strong>₲. <?php echo $this->cart->format_number($this->cart->total()); ?>
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
    $('.cantidad').change(function(event) {
      var j = $(this);
      update_rowid(j.val(),j.attr('id'));
    }).keyup(function(event) {
      var j = $(this);
      update_rowid(j.val(),j.attr('id'));
    });
    $('[name="dataprice"]').change(function(event) {
      $price = $(this).val();
      $rowid = $(this).data('id');
      $qty   = $(this).data('qty');
      $iva   = $(this).data('iva');
      $tyf   = $(this).data('tyf');
      update2_rowid($price,$rowid,$qty,$iva,$tyf) 
    }).keyup(function(event) {
      $price = $(this).val();
      $rowid = $(this).data('id');
      $qty   = $(this).data('qty');
      $iva   = $(this).data('iva');
      $tyf   = $(this).data('tyf');
      update2_rowid($price,$rowid,$qty,$iva,$tyf) 
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
          $( "#detalle" ).load( "<?php echo base_url('Orden_compra/loader');?>" );
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }
    var inpuesto_cinco,iva,inpuesto_diez,total_cinco,total_diez,total_iva,num1,num2 =0;

    $('.idRecorrer  td.selec select.descuento').each(function(index, el) {
      $(this).val($(this).data('tyf')) 
    });

});
</script>