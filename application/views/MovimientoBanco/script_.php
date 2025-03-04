<link href   ="<?php echo base_url();?>content/plugins/pikear/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/moment.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/es.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
   $(function() {
     recorrer();
        // $("[data-mask]").inputmask();
    $('#fecha').datetimepicker({
         minDate: new Date(),
         format: 'DD-MM-YYYY',
         disabledHours: [0, 1, 2, 3, 4,] ,
         enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
    });
    });
var table;
     save_method = '';

$( "#Mov,#bancos" ).addClass( "active" );
$( "#M_o_v" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
    $(function() {
    table = $('#tabla_movi').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/MovimientoBanco/ajax_list'); ?>",
            "type": "POST"
        },

        "columns": [
            {
                "targets":  [ -1 ], // ultimass columnas
                "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
                "className":      'details-control',
                "data":           null,
                "defaultContent": '',

           },
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },
            { "data": "4" },
            { "data": "5" },
            { "data": "6" },


        ],
        "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ]]
      });
    });

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
      $('#collapseExample').removeClass('collapse in').addClass('collapse'); 
    }


$(function() {
  $( "#add" ).click(function() {
     <?php if ($this->session->userdata('idcaja')){ ?>
       $('eee,.nnn,.iii,.ppp,.ccc,.FECHA,.ch,.pc,.cb,#alertasadd').html("").css({"display":"none"});
    var atr = $('#accion').attr('class');
    if (atr == 'fa fa-plus-square') {
      $('#collapseExample').addClass('collapse in');
       $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
       save_method = 'add_add_';
       // $('#ocul,#tab').show().addClass('tab-pane active');
         // $('#home,#acct').removeClass('active');
        $('#btnSave').html('Guardar');
       $('#addcheque,#newcheque')[0].reset();
       $("select").val('').trigger("change");
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
       $( "#Cheques" ).load("<?php echo base_url('index.php/MovimientoBanco/Cheques');?>");
    }else{
      $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
     save_method = '';
      $('#collapseExample').removeClass('collapse in').addClass('collapse');
      // $('#ocul,#tab').show().addClass('tab-pane active');
      // $('#home,#acct').removeClass('active');
    }
       <?php } else { ?>
           alertacaja();
      <?php } ?>
  });


   $( "#B" ).click(function(event) {
    if (save_method != 'update') {
       save_method = 'add';
       $('#newcheque')[0].reset();
    }
  

  });
   $( "#C" ).click(function(event) {
    if (save_method != 'update') {
       save_method = 'add_add';
        // $('#tab').addClass('tab-pane').removeAttr('style');
       $('#addcheque')[0].reset();
    }

  });

  $( "#F" ).click(function(event) {
    if (save_method != 'update') {
       save_method = 'add_add_';
         $('#form_Efectivo')[0].reset();
    }
        // $('#tab').addClass('tab-pane').removeAttr('style');
       // $('#addcheque')[0].reset();


  });
    $('#addcheque').submit(function(e) {
       $('eee,.nnn,.iii,.ppp,.ccc,.FECHA,.ch,.pc,.cb,#alertasadd').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_add'); ?>";
            break;
          case 'update':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_update'); ?>";
            break;
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                          success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error") 
                          {
                              if (json.Cheques) {
                                 $(".ch").append(json.Cheques).show(); // mostrar validation de iten
                              }
                               if (json.PlandeCuenta) {
                                 $(".pc").append(json.PlandeCuenta).show(); // mostrar validation de iten
                              }
                               if (json.cuenta_bancaria) {
                                 $(".cb").append(json.cuenta_bancaria).show(); // mostrar validation de iten
                              }
                           }
                          else
                          { 
                                              if (save_method == 'add') {
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                                             save_method = '';
                                                 $('#collapseExample').removeClass('collapse in').addClass('collapse');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                                          } else{
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                                 $('#collapseExample').removeClass('collapse in').addClass('collapse');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                                          }
                                                  $("#alertasadd").fadeOut(1500);
                                                  setTimeout(function() {
                                             },1510);
                                          reload_table(); // recargar la tabla automaticamente
                                          Limpiar(1);
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

    $('#newcheque').submit(function(e) {
       $('eee,.nnn,.iii,.ppp,.ccc,.FECHA,.ch,.pc,.cb,#alertasadd').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add_add':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_add_'); ?>";
            break;
          case 'update':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_update_'); ?>";
            break;
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                          success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error") 
                          {
                              if (json.Numeru) {
                                 $(".nnn").append(json.Numeru).show(); // mostrar validation de iten
                              }
                               if (json.PlandeCuenta) {
                                 $(".ppp").append(json.PlandeCuenta).show(); // mostrar validation de iten
                              }
                               if (json.Importe) {
                                 $(".iii").append(json.Importe).show(); // mostrar validation de iten
                              }
                               if (json.cuenta_bancaria) {
                                 $(".ccc").append(json.cuenta_bancaria).show(); // mostrar validation de iten
                              }
                               if (json.fecha) {
                                 $(".FECHA").append(json.fecha).show(); // mostrar validation de iten
                              }
                               if (json.movi) {
                                 $(".movii").append(json.movi).show(); // mostrar validation de iten
                              }
                           }
                          else
                          {
                            if (save_method == 'add_add') {
                               $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                               save_method = '';
                              $('#collapseExample').removeClass('collapse in').addClass('collapse');
                              $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                            } else{
                               $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                   $('#collapseExample').removeClass('collapse in').addClass('collapse');
                              $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                            }
                                    $("#alertasadd").fadeOut(1500);
                                    setTimeout(function() {
                               },1510);
                            reload_table(); // recargar la tabla automaticamente
                            Limpiar(1);
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


    $('#form_Efectivo').submit(function(e) {
            var val = $('#val').val();
       $('.cxc,.ppp,.movi,eee,.nnn,.iii,.ppp,.ccc,.FECHA,.ch,.pc,.cb,#alertasadd').html("").css({"display":"none"});
        var url;
        switch(save_method) {
          case 'add_add_':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_add_e'); ?>";
            break;
          case 'update':
          url = "<?php echo base_url('index.php/MovimientoBanco/ajax_update_e'); ?>";
            break;
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                          success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error") 
                          {
                            for (var i = 1, l = val.length; i < l; ++i) {
                             $("#eee"+i).hide();
                              if (json.EF+i) {
                                 $("#eee"+i).append(json.EF+i).show(); // mostrar validation de iten
                              }
                            }
                               if (json.PlandeCuenta) {
                                 $(".ppp").append(json.PlandeCuenta).show(); // mostrar validation de iten
                              }
                               if (json.movi) {
                                 $(".movi").append(json.movi).show(); // mostrar validation de iten
                              }
                               if (json.parcial1) {
                                 $(".eee").append(json.parcial1).show(); // mostrar validation de iten
                              }
                              if (json.cuenta_bancaria) {
                                 $(".cxc").append(json.cuenta_bancaria).show(); // mostrar validation de iten
                              }
                              $(".cxc,#eee,.ppp,.movi,.eee").fadeOut(10000);
                          }
                          else
                          { 
                                              if (save_method == 'add_add') {
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                                             save_method = '';
                                            $('#collapseExample').removeClass('collapse in').addClass('collapse');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                                          } else{
                                             $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                                 $('#collapseExample').removeClass('collapse in').addClass('collapse');
                                            $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
                                          }
                                                  $("#alertasadd").fadeOut(1500);
                                                  setTimeout(function() {
                                             },1510);
                                          reload_table(); // recargar la tabla automaticamente
                                          Limpiar(1);
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


  $( "select" ).select2( {
          placeholder: 'Selecciona',
          width: null,
          theme: "bootstrap"
        } );





    function Limpiar(id) {
      resetet();
    }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    $('.blocqueac').keyup(function(event) {

           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    function resetet() {
       $('#form_Efectivo')[0].reset();
       $('#newcheque')[0].reset();
       $('#addcheque')[0].reset();
    }

        function _delete(id)
     {
     Swal.fire({
        title: "Estas seguro?",
        text: "No podrá recuperar el los datos Eliminado!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/MovimientoBanco/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    reload_table(); 
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Plan de Cuenta ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
    function _edit(id)
    {
       $('#newcheque')[0].reset();
       $('.nnn,.iii,.ppp,.ccc,.FECHA,.ch,.pc,.cb,#alertasadd').html("").css({"display":"none"});
      if (id) {
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/MovimientoBanco/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
               $('[name ="id"]').val(data.idMovimientos);
              $('[name ="Numeru"]').val(data.NumeroCheque);
              $('[name ="PlandeCuenta"]').val(data.PlandeCuenta_idPlandeCuenta).change();
              $('[name ="Importe"]').val(data.Importe);
              $('[name ="Codigo"]').val(data.CodigoCuenta);
              $('[name ="fecha"]').val(data.FechaPago);
              $('[name ="cuenta_bancaria"]').val(data.Gestor_Bancos_idGestor_Bancos).change();
              setTimeout(function() {
              $('#collapseExample').addClass('collapse in');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              $('#btnSave').html('&nbsp;Actualizar');
              $('#ocul,#tab').hide();
              $('#home,#acct').addClass('tab-pane active')
              },100);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
      }
    }


    function cambio(id) {

     var val = $("#EF"+id).val();
     if ( val > 0 ) {
     var monto = $("#EF"+id).attr('data-monto');
     if (val>0) {
        var total = operaciones(val,monto,'*');
        var recor = recorrer();
     }else{
       var total = val;
       var recor = recorrer();
     }
     $('#parcial1').val(recor);
   }else{
    $("#EF"+id).val('')
   }
}
function recorrer(arguments) {
                    var val = $('#val').val();
                    var resultVal = 0;
                    for (var i = 1; i <= val; i++) {
                      var res = $('#EF'+i).val();
                      if (res>0) {
                         resultVal += parseFloat(res);

                      }
                    }
                    if (resultVal < 1) {
                        for (var i = 1; i <= val; i++) {
                          $('#EF'+i).attr({required: 'required'});
                        }
                    }else{
                       for (var i = 1; i <= val; i++) {
                      $('#EF'+i).removeAttr('required');
                      }

                      return resultVal;
                    }

                    // toastem.success(resultVal);

}

</script>
