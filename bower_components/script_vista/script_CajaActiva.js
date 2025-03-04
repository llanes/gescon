     $(function() {
        $( "#c_a,#Caja" ).addClass( "active" );
        $( "#_c_a" ).addClass( "text-red" );
    });
    var table;
     function view(id) {
      $('#pdf').attr('onclick', 'pdf_exporte("caja",'+id+')');
      $('#exel').attr('href', 'Reporte_exel/caja/'+id+'');

      $('#user').html($('#'+id).data('userdata'));
      $('#caja').html(id);
      $('#datafecha').html($('#'+id).data('fecha'));
      

      $("#contenido_caja_ajax").dataTable().fnDestroy();
      $('[name="Importe"],[name="editarmonto"]').hide();
      $('#monto_final1').html("").css({"display":"none"});
     table = $('#contenido_caja_ajax').dataTable({
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
                  url: "Caja/ajax_list_/"+id,
                  type: "POST",
                dataSrc: function(data){
                  $("#monto_final1").append('<p class="text-danger">'+data.Importe+'&nbsp;₲.</p>').css({"display":"block"});
                  Importe = (data.Importe + '').replace(/[^0-9]/g, '');
                  $('[name="Importe"]').val(Importe).show();
                  $('[name="cerrar"]').val(id);
                  $('[name="editarmonto"]').val(data.Importe).show();
                  $('#exCollapsingNavbar').addClass('collapse in');
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
     }

     function ceerar() {
      $("#contenido_caja_ajax").dataTable().fnDestroy();
       $('#exCollapsingNavbar').removeClass('collapse in').addClass('collapse');
     }
               $('#Cerrar_caja').submit(function(e) {
                  var url = "Caja/Cerrar_Caja";
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
                                 },500)
                               toastem.cerrar("La Caja fue Cerrada!!!");
                          })
                          .fail(function(data) {
                             toastem.error('Error los datos no se han Insertado');
                          })
                          .always(function() {

                          });
                    e.preventDefault();

                    })
