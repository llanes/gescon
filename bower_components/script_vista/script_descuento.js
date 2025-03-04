    var table,tablaVencidos,categoriaselect; //  VARIABLE PARA LA TABLA  DE DADATABLE
    var save_method = 'update'; 
    $(document).ready(function() 
    {
      $( "#descuen,#Producto" ).addClass( "active" );
      $( "#d_escue_n" ).addClass( "text-red" );
      $('#des_aler').hide();
      $('#marca').prop('disabled', true);
      $( "#categoria" ).select2( {
        allowClear: true,
        placeholder: 'Busca y Selecciona',
        theme: "bootstrap"
      } ).on('change', function() {
        categoriaselect = $(this).val();
        if (categoriaselect) {
          $('#marca').prop('disabled', false);
        } else {
          $('#marca').prop('disabled', true);
        }
      });
      $('#marca').select2({
        ajax: {
            url: 'Orden_compra/remotecategoria',
            dataType: 'json',
            delay: 250,
            data: function (params) {
            return {
                q: params.term, // Término de búsqueda
                page: params.page, // Página actual
                categoria: categoriaselect // Página actual

            };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                  results: $.map(data.items, function (item) {
                    return {
                      id: item.id,
                      text: item.full_name
                    };
                  }),
                  pagination: {
                    more: false
                  }
                };
              },
              
            cache: true
        },
        placeholder: 'Busca y Selecciona',
        minimumInputLength: 1,
        theme: 'bootstrap'
        });
    


         table = $('#tabla_Des').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "Descuentos/ajax_list",
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
    $('.NN,.eee,.PROVE').html("").hide();
    $('#des_aler').hide();
    var b = $('#loadingg');
    b.button("loading"), 
    $.ajax({
        url: 'Descuentos/add',
        type: 'POST',
        dataType: 'json',
        data: $('[name="formulario"]').serialize(),
    })
    .done(function(data) {
        if (data.res == 'error') {
        if (data.categoria) {
            $(".NN").html(data.categoria).show();
        }
        if (data.marca) {
            $(".eee").html(data.marca).show();
        }
        if (data.Descuento) {
            $(".PROVE").html(data.Descuento).show();
        }
        $('.NN,.eee,.PROVE').fadeOut(3000);    
        } else { 
        if (data == 0) {
            toastem.error("No se han encontrado productos existentes para la aplicacion de DESCUENTO");
        } else {
            $('[name="formulario"]')[0].reset();
            $('#categoria,#marca').val('').change();
            toastem.success('Aplicado Correctamente');
            reload_table();
        }
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        b.button("reset");
    });
    e.preventDefault();
    });



    });


    function edit(id)
    {
        $('.NN,.eee,.PROVE').html("").css({"display":"none"});
        $("#user_aler").hide(); // oculto el contenedor de mensaje de confirmacion
      $.ajax({
        url : "Descuentos/ajax_edit/" + id,
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
  }).then((result) => {
    if (result.value) {
      // ajax delete datos de database
      $.ajax({
        url : "Descuentos/ajax_delete/"+id,
        type: "POST",
        dataType: "JSON",
        cache: false,
        success: function(data) { 
          table.ajax.reload();
          Swal.fire("Eliminado!", "Descuento ha sido borrado.", "success");
        },
        error: function() {
          Swal.fire('Error al intentar borrar');
        }
      });
    } else {
      Swal.fire("Cancelled", "Sin accion:)", "error");
    }
  });
}
