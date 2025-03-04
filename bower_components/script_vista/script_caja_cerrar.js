      var save_method; // VARIABLE DE CONTROL
      var tabla_Marca; //  VARIABLE PARA LA TABLA  DE DADATABLE
     function editmonto() {
          save_method = 'add'
     }
     $(function() {
        $('#barra').addClass('sidebar-collapse');
        $( "#a_c,#Caja" ).addClass( "active" );
        $( "#_a_c" ).addClass( "text-red" );
     });
                // $("#monto_final1").html("").css({"display":"none"});
                 $('#Cerrar_caja').submit(function(e) {
                  var url = "Caja/abrir_Cerrar_Caja/"+0;
                        $.ajax({
                            type : 'POST',
                            url : url, // octengo la url del formulario
                            cache: false,
                            data: $(this).serialize(), // serilizo el formulario
                          })
                          .done(function(data) {
                                 $('#_cerrar').attr({
                                    disabled: 'disabled'
                                 });
                                 setTimeout(function(){
                                        location.reload();
                                 },200)
                               toastem.cerrar("La Caja fue Cerrada!!!");
                          })
                          .fail(function(data) {
                             toastem.error('Error los datos no se han Insertado');
                          })
                          .always(function() {

                          });
                    e.preventDefault();

                    })
      $(function() {
       $('#contenido_caja_ajax').dataTable({
             processing: true,
             serverSide: true,
             bPaginate : false,
             bFilter : false,
             bInfo : false,
             bAutoWidth : false,
             bLengthChange : false,
             sort : false,
              // Load data for the table's content from an Ajax source
              ajax: {
                  url: "Caja/ajax_list",
                  type: "POST",
                dataSrc: function(data){
                  $("#monto_final1").append('<p class="text-danger">'+data.Importe+'&nbsp;₲.</p>').css({"display":"block"});
                  Importe = (data.Importe + '').replace(/[^0-9]/g, '');
                  $('[name="Importe"]').val(Importe);
                  $('[name="editarmonto"]').val(data.Importe);
                 return data.data;


                }
              },
              //Set column definition initialisation properties.
              columnDefs: [
              { 
                targets: [ -1 ], //last column
                orderable: false, //set not orderable
              },
              ],

            });



       var registro_caja_ajax = $('#registro_caja_ajax').dataTable({
             "processing": true,
             "serverSide": true,
              ajax: {
                  url: "Caja/caja_list",
                  type: "POST",
                dataSrc: function(data){
                 return data.data;


                }
              },

              //Set column definition initialisation properties.
              columnDefs: [
              { 
                targets: [ -1 ], //last column
                orderable: false, //set not orderable
              },
              ],

            });

    registro_caja_ajax.fnClearTable();

      });
      function edit_caja(id)
      {
          Swal.fire({
          title: "Editar caja?",
          text: "Reabrir la caja!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Abrir !",
          cancelButtonText: "Cancelar !",
          closeOnConfirm: false,
          closeOnCancel: false
        },

        function(isConfirm) {
        if (isConfirm) {
                  $.ajax({
                  url: "Caja/edit_caja/" + id,
                  type: "POST",
                  success: function(data)
                   {
                    $("#caja_vista").load('Caja');
                   },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error');
                    }
                })
          Swal.fire("Deleted!", "Abierta Correctamente.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });

      }


