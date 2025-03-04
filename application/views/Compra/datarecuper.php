  $('#comprar').submit(function(e) {
        $('#c,#t,#s,#Cheque,#Tarjeta,#fabor').removeClass('active');
        $('#e,#Efectivo').addClass('active');
        $('#compra_submit')[0].reset();
        $('#ParcialE,#ParcialC,#ParcialT').html('');
        $('.hidden').val('');
        var Estado = $('#Estado').val();
        var proveedor = $('[name="proveedor"]').val();
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Compra/ajax_add'); ?>";
        }
         $.ajax({
              type : 'POST',
              url : url, // octengo la url del formulario
              data: $(this).serialize(), // serilizo el formulario
              success : function(data) 
              {
                var json = JSON.parse(data);// parseo la dada devuelta por json
                if (json.res == "error"){
                    if (json.proveedor) {
                       $(".PRO").append(json.proveedor).show(); // mostrar validation de iten
                    }
                     if (json.comprobante) {
                       $(".COMP").append(json.comprobante).show(); // mostrar validation de iten
                    }
                     if (json.orden) {
                       $(".PR").append(json.orden).show(); // mostrar validation de iten
                    }
                    if (json.montofinal) {
                       $(".FINAL").append(json.montofinal).show(); // mostrar validation de iten
                       $(".FINAL").fadeOut(5000); 
                    }
                     if (json.tipoComprovante) {
                       $(".TIPO").append(json.tipoComprovante).show(); // mostrar validation de iten
                    }
                     if (json.fecha) {
                       $(".FECHA").append(json.fecha).show(); // mostrar validation de iten
                    }
                    if (json.inicial) {
                       $(".INIT").append(json.inicial).show(); // mostrar validation de iten
                    }
                     if (json.condicion) {
                       $(".COND").append(json.condicion).show(); // mostrar validation de iten
                    }
                     if (json.cuotas) {
                       $(".CUO").append(json.cuotas).show(); // mostrar validation de iten
                    }
                     if (json.fletes) {
                       $(".FLE").append(json.fletes).show(); // mostrar validation de iten
                    }
                    if (json.descuento) {
                       $(".desc").append(json.descuento).show(); // mostrar validation de iten
                    }
                     if (json.observaciones) {
                       $(".OBSER").append(json.observaciones).show(); // mostrar validation de iten
                    }
                }else{ 
                    if (Estado == 0) {
                      $('#mmrrr').tab('show');
                      $( "#cheque_tercero" ).load( "<?php echo base_url('index.php/Deuda_empresa/cuenta_bancaria');?>");
                      $( "#fabor" ).load( "<?php echo base_url('index.php/Compra/formapago');?>/"+3+'/'+proveedor );
                      $( "#piesss" ).load( "<?php echo base_url('index.php/Compra/formapago');?>/"+5);
                      $(".NOM,.TE,.RUC,#alertasadd,.aaa,.eee,#alertasadd,.alerter").html("").css({"display":"none"});
                      $('#numcheque').removeAttr('required');
                      $("#compra_submit,#modal-header").show();
                      $('#modal-id').modal('show');
                      
                      save_method = 'add_add';
                      
                    }else{
                      $('#Compra_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                      $("#Compra_aler").fadeOut(1000);
                      setTimeout(function() {
                        save_method = 'add';
                      },1510);
                      $('#comprar')[0].reset();
                      reload_table(); // recargar la tabla automaticamente
                      Limpiar(1);
                      
                    }
                }
              },
              error : function(xhr, status) {
                  Swal.fire('Disculpe, existió un problema');
                  console.log('error(jqXHR, textStatus, errorThrown)');
              },
          });
      e.preventDefault();
  });

                                <div class="form-group col-md-3">
                                <div class="input-group">

                                  <?php echo $value->Compra ?> &nbsp;₲S.


                                </div>

                              </div>