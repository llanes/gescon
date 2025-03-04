
<script type="text/javascript">
var table;
var save_method;
$( "#CPA,#Compra" ).addClass( "active" );
$( "#CPA,#C_P_A" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );


    $(function() {
        $('#anho,#datetimepicker1').datetimepicker({
    viewMode: 'years',
    format: 'MM-YYYY',
});
        const listaCompras = (event) => {
          event.preventDefault();
          const formData = $('#listaCompras').serializeArray(); // Serializa el formulario a un array

          if (table) {
            table.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

          table = $('#tabla_compra').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "Compra/ajax_list",
                "type": "POST",
                "data": function (d) {
                    const formObj = {};
                    $.each(formData, function(index, field) {
                    formObj[field.name] = field.value; // Convertir el array en un objeto
                    });
                    return $.extend({}, d, formObj); // Combina datos de DataTables con el objeto del formulario
                }
            },
            "columns": [
                {
                    // "targets":  [ -1],
                    "orderable": false,
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
                { "data": "6" }


                    ],
                });
        };

        $('.clear-input').on('click', function () {
                $('#anho').val('');
            });

        $('#listaCompras').on('submit', listaCompras);

    // table = $('#tabla_compra').DataTable({
    //     "processing": false,
    //     "serverSide": true,
    //     // Datos de carga de contenidos de la tabla de un origen Ajax
    //     "ajax": {
    //         "url": "Compra/ajax_list",
    //         "type": "POST"
    //     },

    //     "columns": [
    //         {
    //             // "targets":  [ -1], // ultimass columnas
    //             "orderable": true, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
    //             "className":      'details-control',
    //             "data":           null,
    //             "defaultContent": '',



    //         },
    //         { "data": "0" },
    //         { "data": "1" },
    //         { "data": "2" },
    //         { "data": "3" },
    //         { "data": "4" },
    //         { "data": "5" },
    //         // { "data": "6" },
    //         // { "data": "7" },


    //     ],
    //     // "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ],[ 5, 'desc' ]]
    //   });











    $('#tabla_compra tbody').on('click', 'td.details-control', function () {
        // Mostrar mensaje de carga
        
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


    function format(d) {
        var id = d.slice(7);
        // Almacenar el resultado de la creación de los elementos jQuery
        var table = $('<table>', {class: 'table table-striped table-bordered', id: id});
        var header = $('<tr>', {class: 'danger'}).append(
            $('<td>').text('Cantidad'),
            $('<td>').text('Nombre'),
            $('<td>').text('Precio'),
            $('<td>').text('Descuento'),
            $('<td>').text('Subtotal')
        );
        var loadingRow = $('<tr>', {class: 'loading-message',id: 'loading-message-' + id}).append(
            $('<td>', {colspan: '5', css: {'text-align': 'center'}}).text('Cargando...')
        );
        // Almacenar el resultado de la concatenación de los elementos jQuery
        var tableContent = header.add(loadingRow);
        table.append(tableContent);

        $.ajax({
            type: 'POST',
            url: "Compra/detale/" + id,
            dataType: 'json'
        })
        .done(function(data) {
            if (data) {
            var rows = [];
            $.each(data, function(index, val) {
                var sub = parseInt(val.can) * parseInt(val.Precio);
                if (val.Descuentoapp !== '') {
                sub -= parseInt(val.Descuentoapp);
                }
                rows.push($('<tr>').addClass('success').append(
                $('<td>').text(val.can),
                $('<td>').text(val.Nombre),
                $('<td>').text(formatNum(val.Precio) + ' ₲.'),
                $('<td>').text(formatNum(val.Descuentoapp)),
                $('<td>').text(formatNum(sub) + ' ₲.')
                ));
            });
            table.append(rows);
            $('#' + id).replaceWith(table);
            }
        })
        .always(function() {
            // Ocultar mensaje de carga
            $('#loading-message-' + id).hide();
        });
        return table;
    }



});



    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }

    function formatState (state) {
          var id =  state.id;
          if (id != undefined) {
            var element     =  id.split(',') ;
               var img = element[3];
          }

      if (!state.id) { return state.text; }
      var $img = $(
        '<span><img src="<?php echo base_url('bower_components/uploads') ?>/'+img+'" class="img-rounded" alt="Cinque Terre" width="60" height="50" /> ' + state.text + '</span>'
        );
        return $img;
      };

    function delete_rowid(rowid)
    {
        Swal.fire({
        title: "Estas seguro?",
        showCancelButton: true,
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('index.php/Compra/delete_item');?>/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
               $( "#detalle" ).load( "<?php echo base_url('index.php/Compra/loader');?>" );
               $('.productos').focus();
            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    function no_pagado(id) {
        Swal.fire({
        title: "",
        showCancelButton: true,
        confirmButtonText: "Modificar Estado",
        cancelButtonText: "Cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('index.php/Compra/no_pagado');?>/"+id,
            type: "POST",
            cache: false,
            success: function(data)
            {
              if (data == 0) {
              reload_table()
              }else{
                toastem.success("No se han podido Modificar los datos");
              }

            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    function _pagado(id) {
        Swal.fire({
        title: "",
        showCancelButton: true,
        confirmButtonText: "Modificar Estado",
        cancelButtonText: "Cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('index.php/Compra/pagado');?>/"+id,
            type: "POST",
            cache: false,
            success: function(data)
            {
              if (data == 0) {
              reload_table()
              }else{
                toastem.success("No se han podido Modificar los datos");
              }

            },
        });
          Swal.fire("Deleted!", "Articulo ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }

    function gestionCompra(id, num, stado) {
      let message;
      if (stado === "Anular") {
        message = 'Anulado';
      } else {
        message = 'Eliminado';
      }

      Swal.fire({
        title: stado + " Comprobante?",
        text: "Usted está intentando " + stado.toLowerCase() + " este comprobante de compra. Esto también afectará a los procesos de pagos realizados de este determinado comprobante.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: stado + " Comprobante",
        cancelButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return $.ajax({
            url: "Compra/gestionCompra",
            type: "POST",
            data: { id: id, num: num, stado: stado }
          }).then((response) => {
            if (response === 'true') {
              reload_table(); // Asumiendo que existe una función para recargar la tabla
              Swal.fire(message + "!", "El comprobante ha sido " + message + ".", "success");
            } else {
              Swal.fire("Error", "No se han podido modificar los datos", "error");
            }
          }).catch(() => {
            Swal.fire("Error", "No se han podido modificar los datos", "error");
          });
        },
        allowOutsideClick: () => !Swal.isLoading()
      });
    }



</script>
