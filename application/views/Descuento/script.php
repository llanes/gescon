 <script src ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
 <script src ="<?php echo base_url();?>bower_components/select2/dist/js//i18n/es.js"></script>
  <script type="text/javascript">
    var table,tablaVencidos; //  VARIABLE PARA LA TABLA  DE DADATABLE
    var save_method = 'update'; 
    $(document).ready(function() 
    {
      $( "#descuen,#Producto" ).addClass( "active" );
      $( "#d_escue_n" ).addClass( "text-red" );
      $('#des_aler').hide();
      $( "#categoria,#marca" ).select2( {
              allowClear: true,
              placeholder: 'Seleccione',
              width: null,
                theme: "bootstrap"
            } );
         table = $('#tabla_Des').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Descuentos/ajax_list'); ?>",
            "type": "POST",
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });
    function reload_table()
    {
       table.ajax.reload(); //reload datatable ajax 
    }

     $('[name="formulario"]').submit(function(e) {
      $('.NN,.eee,.PROVE').html("").css({"display":"none"});
      $('#des_aler').hide();
      var b = $('#loadingg');
      b.button("loadingg"), setTimeout(function() {
      b.button("reset");
      }, 1000);
        $.ajax({
          url: '<?= base_url('index.php/Descuentos/add') ?>',
          type: 'POST',
          dataType: '',
          data: $(this).serialize(),
        })
        .done(function(data) {
          var json = $.parseJSON(data);
          if (json.res == 'error') {
                if (json.categoria) {
                   $(".NN").append(json.categoria).css({"display":"block"}); // mostrar validation de iten usuario
                }
                 if (json.marca) {
                   $(".eee").append(json.marca).css({"display":"block"}); // mostrar validation de iten usuario
                }
                if (json.Descuento) {
                   $(".PROVE").append(json.Descuento).css({"display":"block"}); // mostrar validation de iten usuario
                }
          $('.NN,.eee,.PROVE').fadeOut(3000);    
          }else{ 
            if (data == 0) {
              toastem.error("No se han encontrado productos existentes para la aplicacion de DESCUENTO");
            }else{
             setTimeout(function() {
             $('[name="formulario"]')[0].reset();
              $('#categoria,#marca').val('').change();
              $('#des_aler').show();
                  $('.title').text('Aplicado Correctamente');
                  $("#des_aler").fadeOut(1500);reload_table();
            },1000);
            }

          }
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
        e.preventDefault();
      });



    });


    function edit(id)
    {
        $('.NN,.eee,.PROVE').html("").css({"display":"none"});
        $("#user_aler").hide(); // oculto el contenedor de mensaje de confirmacion
      $.ajax({
        url : "<?php echo base_url('index.php/Descuentos/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
      })
      .done(function(data) {
               $('[name ="categoria"]').val(data.Categoria_idCategoria).change();
               $('[name ="marca"]').val(data.idProducto).change();
               $('[name ="Descuento"]').val(data.Descuento).change();

      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });

    }


    function _delete(id)
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
                  url : "<?php echo base_url('index.php/Descuentos/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function(data) { 
                    setTimeout(function() {table.ajax.reload();}, 100);
                       // si se  completo el eliminado refrescamos la tabla
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Usuario ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
