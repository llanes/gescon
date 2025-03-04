<link href   ="<?php echo base_url();?>content/plugins/pikear/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/moment.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/es.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript">
    var text      = $("#Usuario").val();
    var list;
    var save_method ; // VARIABLE DE CONTROL
    var table;
    var deuda_e;
    var idreload = 0;
        $( "#PagE,#PagosCobros" ).addClass( "active" );
        $( "#CDC,#P_a_g_e" ).addClass( "text-red" );

    deuda_e = $('#Deuda_c').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/DeudaEmpresa/ajax_list'); ?>",
            "type": "POST"
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        {
          "targets":  [ -1], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],
          "order": [[ 0, 'desc' ], [ 1, 'desc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ]]
      });

function listar_deudas(id) {
  $('#list').dataTable().fnDestroy();
  $('#Deudas').removeClass('active');$('#listados').addClass('active');
  $('#addtrpdf').attr('onclick', "pdf_exporte('lisdeuda/"+id+"')");
  $('#addtrexel').attr('href','Reporte_exel/lisdeuda/'+id+'');
  if (id > 0) {
  list = $('#list').DataTable({
              "processing": true, //Característica de control del indicador de procesamiento.
              "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
              // Datos de carga de contenidos de la tabla de un origen Ajax
              "ajax": {
                  "url": "<?php echo base_url('index.php/DeudaEmpresa/listar_deudas');?>/"+id,
                  "type": "POST",
              },

              //Conjunto de columnas propiedades de definición de inicialización .
              "columnDefs": [
              {
                "targets": [ -1 ], // ultimass columnas
                "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
              },
              ],
 "order": [[ 0, 'acs' ], [ 1, 'acs' ],[ 2, 'acs' ],[ 3, 'acs' ],[ 4, 'acs' ],[ 4, 'acs' ]]
            });
  }


}


function atras() {
  $('#listados').removeClass('active');$('#Deudas').addClass('active');
}

function atras() {
  $('#listados').removeClass('active');$('#Deudas').addClass('active');
}

function item_cobrar(id,monto,cantidadRestante,Proveedor)
{
     <?php if ($this->session->userdata('idcaja')){ ?>
        $("#recibo,#numero,#alertasadd,#pendiente,#prove").html("").css({"display":"none"});
        $('#c,#t,#s,#Cheque,#Tarjeta,#fabor').removeClass('active');
        $('#ParcialE,#ParcialC,#ParcialT').html('');
        $('.hidden').val('');
         $("#pagosdeuda").show(); 
         $('#pagosdeuda')[0].reset();
         $("#pendiente").append('Monto Pendiente  :  '+formatNumber.new(monto, "₲ ")).css({"display":"block"});
         $("#monto").val(monto);
         $('#id').val(id);
         $( "#fabor" ).load( "<?php echo base_url('index.php/DeudaEmpresa/formapago');?>/"+4+'/'+Proveedor );
         $( "#piesss" ).load( "<?php echo base_url('index.php/DeudaEmpresa/formapago');?>/"+5);
         $( "#cheque_tercero" ).load( "<?php echo base_url('index.php/DeudaEmpresa/cuenta_bancaria');?>");
  $.ajax({  
    type : 'POST',
    url: "<?php echo base_url('index.php/DeudaEmpresa/lis_deuda');?>/"+id,
    dataType: 'json',
  })
  .done(function(data) {
            $("[name='numcheque']").removeAttr('required')
    if (data != null) {
        $("#recibo").append('Recibo Nº : '+data.Num_Recibo).css({"display":"block"});
        $("#numero").append('Cuota  Nº : '+data.Num_cuota).css({"display":"block"});
        $('#idProveedor').val(data.idProveedor);
        $("#prove").append('Proveedor  : '+liteStringmncv(data.Razon_Social,10)+' '+  liteStringmncv(data.Vendedor,10)).css({"display":"block"});
        $('#idF').val(data.idFactura_Compra);
        idreload = data.idFactura_Compra;
        $('#crEstado').val(data.crestado);
        $('#cfEstado').val(data.esta);
        $('#totalrous').val(cantidadRestante);
        $( "#collapseExample" ).collapse('show');
        $('#limpiar').tab('show');
        save_method ='add';
    }

  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
   <?php } else { ?>
           alertacaja();
      <?php } ?>
}
    function reload_table()
    {
      deuda_e.ajax.reload(); //reload datatable ajax 
      listar_deudas(idreload);
    }

  $(function() {
      $('#pagosdeuda').submit(function(e) {
      if ($('#Totalparclal').val() < 1) {
                toastem.error("Es necesario ingresar algun Monto!!");
                $('#loadingg').attr({disabled: 'disabled'});
                $('.alerter').html("Es necesario ingresar algun Monto!! ").show();
                setTimeout(function() { $('#loadingg').removeAttr('disabled');  $('.alerter').hide();}, 4000);

      }else{
          // $('#Deuda_e').dataTable().fnDestroy();
          if ( save_method == 'add' && parseFloat($('#Totalparclal').val()) >= parseFloat($('#monto').val()) ) {
            toastem.success("Pagar todo");
                       $.ajax({
                          url : "<?php echo base_url('index.php/DeudaEmpresa/pagar_todo'); ?>",
                          type: "POST",
                          data: $(this).serialize(), // serilizo el formulario
                          cache: false,
                       })

                       .done(function(data) {
                          if (data.res == 'error') {
                            toastem.error("Disculpe los datos no han sido modificado por fabor intente nuevamente gracias!!");
                          }else{
                         // $('#list').dataTable().fnDestroy()
                          var b = $('#loadingg');
                          b.button("loadingg"), setTimeout(function() {
                              b.button("reset");
                              $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                          }, 1000)

                                                  setTimeout(function() {
                                                  $("#alertasadd").fadeOut(1500);
                                                  $( "#collapseExample" ).collapse('hide');
                                                  $("#cheque_tercero").val('').trigger("change"); 
                                                  reload_table(); 
                                             },2000);

                          }
                       })
                       .fail(function() {
                         toastem.error("Disculpe hubo un error los datos no se an modificado");
                       })
                       .always(function() {
                         console.log("complete");
                       });
          }else{
                  toastem.success("Pagar Parcial");
                      $.ajax({
                          url : "<?php echo base_url('index.php/DeudaEmpresa/pagar_parcial'); ?>",
                          type: "POST",
                          data: $(this).serialize(), // serilizo el formulario
                          cache: false,
                       })
                       .done(function(data) {
                          if (data.res == 'error') {
                            toastem.error("Disculpe los datos no han sido modificado por fabor intente nuevamente gracias!!");
                          }else{
                          var b = $('#loadingg');
                          b.button("loadingg"), setTimeout(function() {
                              b.button("reset");
                              $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                          }, 1000)

                            setTimeout(function() {
                            $("#alertasadd").fadeOut(1500);
                            $( "#collapseExample" ).collapse('hide');
                            $("#cheque_tercero").val('').trigger("change");
                            reload_table(); // recargar la tabla automaticamente
                       },2000);

                          }
                       })
                       .fail(function() {
                         toastem.error("Disculpe hubo un error los datos no se an modificado");
                       })
                       .always(function() {
                         console.log("complete");
                       });

          }
      }
        e.preventDefault();
      })
    });
$(function() {
        $( "#cuenta_bancaria,#afabor" ).select2( {
              allowClear: true,
              placeholder: 'Buscar',
              width: null,
              theme: "bootstrap"
            } );
        $('#fecha_pago').datetimepicker({
          minDate: new Date(),
               format: 'DD-MM-YYYY',
          disabledHours: [0, 1, 2, 3, 4,] ,
          enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
        });

});
    function delete_(id,id2,id3,id4)
     {
     Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/DeudaEmpresa/ajax_delete'); ?>",
                  type: "POST",
                  dataType: "JSON",
                  data: {id: id,id2: id2,id3: id3,id4: id4},
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                  reload_table();s

               })
                .fail(function() {
                  toastem.error("Error al intentar borrar");
                });
          Swal.fire("Eliminado!", "Empleado ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }



</script>
