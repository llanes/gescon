<link href   ="<?php echo base_url();?>content/plugins/pikear/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/moment.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/es.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
var table;
var save_method;
$( "#Pag,#PagosCobros" ).addClass( "active" );
$( "#P_a_g" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
 function format ( d ) {
      var id     =  d.slice(6) ;    
      $.ajax({
      type : 'POST',
      url: "<?php echo base_url('index.php/Pago/detale');?>",
      data: { val:  d.slice(6)},
      dataType: 'json',
      })
      .done(function(data) {
        if (data) {
          $.each(data, function(index, val) { 
           var sub ='';
           if (val.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa != null) {
             sub = 'Cuota Nº '+val.Num_Cuotas;
           }
            $('#'+id).append('<tr class="success"><td>'+val.Descripcion+'</td><td>'+formatNumber.new(val.Monto)+' ₲.</td><td>'+sub+'</td></tr>');
          });
        }
      });
      return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+id+'">'+
          '<tr class="danger">'+
              '<td>Forma de Pagos</td>'+
              '<td>Monto ₲.</td>'+
               '<td>Referencia</td>'+
          '</tr>'+
      '</table>';
  }

    $(function() {
    table = $('#tablePagos').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Pago/ajax_list'); ?>",
            "type": "POST"
        },

        "columns": [
            {
                "targets":  [ -1], // ultimass columnas
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
            // { "data": "6" },

        ],
      });

    $('#tablePagos tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );


    });

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }
   function resetear() {
    $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Producir');
    }


$(function() {
  $( "#add" ).click(function() {
     <?php if ($this->session->userdata('idcaja')){ ?>
        $("[name='numcheque']").removeAttr('required')
        var atr = $('#accion').attr('class');
        if (atr == 'fa fa-plus-square') {
          $('#limpiar').tab('show');
          $('#collapseExample').collapse('show');
           $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
           save_method = 'add';
              $('#btnSave').html('&nbsp;Guardar');
           $('#from_Pagos')[0].reset();
           $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
           setTimeout(function()
            {
              $('#EF1').focus();
            }, 100);
        }else{
          $( "#accion" ).removeClass('fa fa-minus-square').addClass('fa fa-plus-square').html('&nbsp;&nbsp;Agregar');
         save_method = '';
               $('#collapseExample').collapse('hide');
                  $('#btnSave').html('&nbsp;Actualizar');
        }
   <?php } else { ?>
           alertacaja();
      <?php } ?>
  });
});


  $( "#selectrequired,#select,#cuenta_bancaria,#PlandeCuenta" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );
    $('#fecha_pago').datetimepicker({
         // minDate: new Date(),
         format: 'DD-MM-YYYY',
         disabledHours: [0, 1, 2, 3, 4,] ,
         enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
    });

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    function resetet() {
           $('#from_Pagos')[0].reset();
    }

        function delete_(id,id2,id3,id4,id5)
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
                  url : "<?php echo base_url('index.php/Pago/ajax_delete'); ?>",
                  type: "POST",
                  dataType: "JSON",
                  data: {id: id,id2: id2,id3: id3,id4: id4,id5: id5},
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
       $('#from_Pagos')[0].reset();
       $('.ttt,.nnn,.ccc,#alertasadd').html("").css({"display":"none"});
      if (id) {
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/Plan_de_Cuenta/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              $('[name ="id"]').val(data.idPlandeCuenta);
              $('[name ="Codigo"]').val(data.CodigoCuenta);
              $('[name ="nombre"]').val(data.NombreCuenta);
              $('[name ="Categorias"]').val(data.SubPlanCuenta_idSubPlanCuenta).change();
              setTimeout(function() {
              $('#collapseExample').collapse('show');
              $( "#accion" ).removeClass('fa fa-plus-square').addClass('fa fa-minus-square').html('&nbsp;&nbsp;Cerrar');
              $('#btnSave').html('&nbsp;Actualizar');
              },100);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
      }
    }

reloadcheque();
$(function () {
    $('#efectivo').keyup(function(event) {
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()) {
         $('#numcheque').attr('required','required');
         var valor = $(this).val();
         $('#ParcialC').html(formatNumber.new(valor));
         $('#parcial2').val(valor);
         totalparcial(1);
      }else{
         $('#numcheque').removeAttr('required');
         $('#ParcialC').html('');
         $('#parcial2').val('');
         totalparcial(1);
      }
    });

   $('#efectivoTarjeta').keyup(function(event) {
      var este =+ parseFloat($(this).val());
      if ($(this).val()<1) {
        $(this).val('')
      }
       this.value = (this.value + '').replace(/[^0-9]/g, '');
      if ($(this).val()) {
         var valor = $(this).val();
         $('#ParcialT').html(formatNumber.new(valor));
         $('#parcial3').val(valor);
         totalparcial(1);
      }else{
         $('#ParcialT').html('');
         $('#parcial3').val('');
         totalparcial(1);
      }
    });
          $("#numcheque").keyup(function () {
             var id      = $(this).val();
             if (id > 00 ) {
                 $("#Cliente,#efectivo,#fecha_pago").removeAttr('disabled');
                 $("#Cliente,#efectivo,#fecha_pago").attr('required','required');
             } else{
                  $("#Cliente,#efectivo,#fecha_pago").attr('disabled','disabled');
                  $("#Cliente,#efectivo,#fecha_pago").removeAttr('required');

             }
        });

      $("#checkbox").on( 'change', function() {
        if( $(this).is(':checked') ) {
                 $("#cuenta_bancaria").removeAttr('disabled');
                 $("#cuenta_bancaria").attr('required','required');
        } else {
                $("#cuenta_bancaria").attr('disabled','disabled');
                $("#cuenta_bancaria").removeAttr('required');
                $('[name="cuenta_bancaria"]').val('').trigger("change");
        }
    });


    $("[name='Razon']").change(function () {
      var element =  $(this).val().split(',') ;
      $('[name="Nombre_Tipo"]').val(element[1]);
      $('[name="razon"]').val(element[0]);
       if ( element[0] == 1 ) {
           $("#empleadito").show();
           $('[name="R_H" ]').attr('required','required');
       } else{
            $("#empleadito").hide();
            $('[name="R_H" ]').removeAttr('required');

       }
  });




});
    $('.blocqueac').keyup(function(event) {
      if ($(this).val()< 1) {
        this.value = '';
      }
     this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

$(function () {

$('#from_Pagos').submit(function(e) {
$('.tttt,.ttt,.nnn,.TIPO,.CNP,#alertasadd').html("").css({"display":"none"});
var Totalparclal = $('#Totalparclal').val();
if (save_method == 'add' && parseFloat(Totalparclal) > 0  ) {
  $.ajax({
    url: "<?php echo base_url('index.php/Pago/ajax_add_Pago'); ?>",
    type: 'POST',
    dataType: 'json',
    data: $(this).serialize(),
  })
  .done(function(json) {
          if (json.res == "error") {
            if (json.Razon) {
               $(".ttt").append(json.Razon).show(); // mostrar validation de iten
            }
            if (json.tipoComprovante) {
               $(".TIPO").append(json.tipoComprovante).show(); // mostrar validation de iten
            }
            if (json.Descripcion) {
               $(".nnn").append(json.Descripcion).show(); // mostrar validation de iten
            }
           if (json.Totalparclal) {
               $(".tttt").append(json.Totalparclal).show(); // mostrar validation de iten
            }
           if (json.comprobante) {
               $(".CMP").append(json.comprobante).show(); // mostrar validation de iten
            }
           if (json.PlandeCuenta) {
               $(".PPP").append(json.PlandeCuenta).show(); // mostrar validation de iten
            }
          } else {
                var b = $('#loadingg');
                b.button("loadingg"), setTimeout(function() {
                    b.button("reset");
                    $('#alertasadd').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                    $( "#comprobante" ).val(parseFloat($( "#comprobante" ).val())+parseFloat(1));
                    $( "#Ticket" ).val(parseFloat($( "#Ticket" ).val())+parseFloat(1));
                }, 1000)
                  setTimeout(function() {

                           $("#alertasadd").fadeOut(1500);
                           $('#from_Pagos')[0].reset();
                           reload_table(); // recargar la tabla automaticamente
                           reseteart();
                           save_method = 'add';
                           location.reload();
                           pdf_exporte('pago', json);
                  },2000);
          }


  })
  .fail(function(json) {
    toastem.error("Error ");
  })
  .always(function() {
    console.log("complete");
  });

}else{
  $('.alerter').html("Ingrese algun Monto!! ").show();
  setTimeout(function() {  $('.alerter').hide();}, 4000);
}
  e.preventDefault();
});


});

function cambio(id) {
     var val = $("#EF"+id).val();
     var monto = $("#EF"+id).attr('data-monto');
      if (val>0) {
        $('#Moneda'+id).val(id);
        $('#cam'+id).val(monto);
     }else{
       $('#Moneda'+id).val('');
       $('#cam'+id).val('');
     }

     if (monto>0 && val>0 ) {
        var total = operaciones(val,monto,'*');
     }else{
      if (val > 0) {
        var total = val;
      }else{
        var total = '';
      }

     }
     $('#cm'+id).val(formatNumber.new(total));
     $('#ex'+id).val(total);
     $('#ParcialE').html(formatNumber.new(recorrer()));
     $('#parcial1').val(recorrer());
     totalparcial(1);
}

function recorrer(arguments) {
                    var val = $('#val').val();
                    var resultVal = 0;
                    for (var i = 1; i <= val; i++) {
                      var res = $('#ex'+i).val();
                      if (res>0) {
                         resultVal += parseFloat(res);

                      }
                    }
                    return resultVal;
                    // toastem.success(resultVal);

}

function totalparcial(xx) {
 var total = 0;
for (var i = 1; i < 5; i++) {
  var id = $('#parcial'+i).val()
  if (id > 0) {
    total += parseFloat(id);
  }

}
$('#Totalp').html(formatNumber.new(total));
$('#Totalparclal').val(total);
  return total;
}
function reseteart() {
        for (var i = 1; i < 5; i++) {
      $('#parcial'+i).val('')
    }

    $('#ParcialE,#ParcialC,#ParcialT,#Totalp').html('');
    $('#Totalparclal').val('');
    $('[name="Razon"]').val('').trigger("change");
    $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
    $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');
}


function reloadcheque(argument) {
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivo,#fecha_pago,#cuenta_bancaria").removeAttr('required');
}
$('#seartt').on('click', function(e) {
   save_method = 'add_add';
});
$('.close').on('click', function(e) {
   save_method = 'add';
});




$(function() {
  $('#insert_tipo').submit(function(e) {
   if (save_method == 'add_add') {
    $('.NOM').html("").css({"display":"none"});
    $.ajax({
        url: "<?php echo base_url('index.php/Pago/ajax_addtipo'); ?>",
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
    })
    .done(function(data) {
          if (data.res == 'error') {
            if (data.Descripcion) {
              $('.NOM').append(data.Descripcion).css({"display":"block"});
               $(".NOM").fadeOut(2500);
            }
          }else{
              $('[name="Razon"]').append($('<option>', {value:data.id, text:data.nom}));
              var b = $('#loading');
               $(b).button('loading'), setTimeout(function() {
                  b.button("reset");
                  $("#inserc,#mh").hide(); // CERRAMOS  EL FORMULARIO
                  $('#alertas').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
              }, 1000)
                setTimeout(function() {
                      $("#inser_aler").fadeOut(1500);
                      $('[name="Razon"]').val(data.id).trigger("change");
                      $('#modal-1').modal('hide');
                      save_method = 'add';
                      $('.productos').focus();
                },2000);
          }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
      }

       e.preventDefault();
  });
});




</script>
