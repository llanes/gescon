     <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.extensions.js'); ?>type="text/javascript"></script>
    <script type="text/javascript">
    $(function() {
      add_empresa();
               //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();
    });
      function add_empresa()
    {
      $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.SE,.LMC").html("").css({"display":"none"});
      $("#empresar_aler").hide(); // oculto el contenedor de mensaje de confirmacion
      $('#from_empresa')[0].reset(); // restablecer el formulario 
      $(".modal-body,.modal-header,.modal-footer").show(); // abrimos todas las partes de modal por cualquier eventualidad echa anteriormente
      $('#id_empresa').modal('show'); // abrir modal
    }
      $(function() {
      $('#from_empresa').submit(function(e) {
        var url = "<?php echo base_url('index.php/Empresa/ajax_add'); ?>";
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                      success : function(data) {
                         var json = JSON.parse(data);// parseo la dada devuelta por json
                          $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.SE,.LMC").html("").css({"display":"none"});
                          if (json.res == "error") {
                            if (json.Nombre) {
                               $("NN").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Descripcion) {
                               $(".DE").append(json.Descripcion).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Direccion) {
                               $(".DI").append(json.Direccion).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                             if (json.R_U_C) {
                               $(".RU").append(json.R_U_C).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Telefono) {
                               $(".TE").append(json.Telefono).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Email) {
                               $(".EM").append(json.Email).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Timbrado) {
                               $(".TI").append(json.Timbrado).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Series) {
                               $(".SE").append(json.Series).css({"display":"block"}); /// mostar validation  de iten pass
                            }
                             if (json.Comprovante) {
                               $(".LMC").append(json.Comprovante).css({"display":"block"}); /// mostar validation  de iten pass
                            }
                           // if (json.passconf) {
                           //     $(".PF").append(json.passconf).css({"display":"block"}); /// mostar validation  de iten pass
                           //  }
                          }else{ 
                                        $(".modal-body,.modal-header,.modal-footer,#from_empresa").hide();
                                         $('#empresar_aler').show()
                                            $("#empresar_aler").fadeOut(1500);
                                          setTimeout(function() {
                                               

                                                location.reload();
                                        $('#modal_form_empresa').modal('hide');
                                            },2000);
                                        
                                        
                           }
                     },
                        // código a ejecutar si la petición falla;
                        error : function(xhr, status) {
                            Swal.fire('Disculpe, existió un problema');
                            console.log('error(jqXHR, textStatus, errorThrown)');
                        },
                    });
        e.preventDefault();
      })
    });
</script>
