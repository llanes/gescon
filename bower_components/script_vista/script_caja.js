      var save_method; // VARIABLE DE CONTROL
      var tabla_Marca; //  VARIABLE PARA LA TABLA  DE DADATABLE
                 $('#Abrir_caja').submit(function(e) {
                  var url = "Caja/abrir_Cerrar_Caja/"+1;
                          $.ajax({
                            type : 'POST',
                            url : url, // octengo la url del formulario
                            cache: false,
                            data: $(this).serialize(), // serilizo el formulario
                          })
                          .done(function(data) {
                                 var json = JSON.parse(data);// parseo la dada devuelta por json
                                    $(".MI").html("").css({"display":"none"});
                                  if (json.res == "error") 
                                  {
                                      if (json.inicio) {
                                         $(".MI").append(json.inicio).css({"display":"block"}); // mostrar validation de iten usuario
                                      }
                                  }
                                  else
                                  { 
                                        $('#_abrir').attr({
                                          disabled: 'disabled'
                                        });
                                            setTimeout(function(){
                                            location.reload();
                                            },500)
                                         toastem.abrir("La Caja fue abierta!!!");
                                  }
                          })
                          .fail(function(data) {
                             toastem.error('Error los datos no se han Insertado');
                          })
                          .always(function() {
                            console.log("complete");
                          });
                    e.preventDefault();
                    })

     $(function() {
        $( "#a_c,#Caja" ).addClass( "active" );
        $( "#_a_c" ).addClass( "text-red" );
        $("#inicio").keyup(function () {
            this.value = (this.value + '').replace(/[^0-9]/g, '');
            var value = $(this).val();

            $("#Importe1").html(formatNumber.new(value)+' ₲');
            $("#Importe").val(value);
        });

    });
