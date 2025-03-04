
<script type="text/javascript">
var table;
var save_method;
$( "#VPA,#Venta" ).addClass( "active" );
$( "#VPA,#VTAlIS,#V_P_A" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );

const listaVentas = (event) => {
          event.preventDefault();
          const formData = $('#listaVentas').serializeArray(); // Serializa el formulario a un array

          if (table) {
            table.destroy(); // Destruir la tabla existente antes de crear una nueva
          }

          table = $('#tabla_Venta').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "Venta/ajax_list",
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
                    "targets":  [ -1],
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

    $(function() {
        $('#anho,#datetimepicker1').datetimepicker({
    viewMode: 'years',
    format: 'MM-YYYY',
});

            $('.clear-input').on('click', function () {
                $('#anho').val('');
            });

    $('#listaVentas').on('submit', listaVentas);
    $('#tabla_Venta tbody').on('click', 'td.details-control', function () {
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
        // console.log(d);
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
            url: "Venta/detale/" + id,
            dataType: 'json'
        })
        .done(function(data) {
            if (data) {
            var rows = [];
            $.each(data, function(index, val) {
                var sub = parseInt(val.can) * parseInt(val.Precio_Venta);
                if (val.Descuentoapp !== '') {
                sub -= parseInt(val.Descuentoapp);
                }
                rows.push($('<tr>').addClass('success').append(
                $('<td>').text(val.can),
                $('<td>').text(val.Nombre),
                $('<td>').text(formatNum(val.Precio_Venta) + ' ₲.'),
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
            url : "Venta/delete_item/"+rowid,
            type: "POST",
            cache: false,
            data: $(this).serialize(), // serilizo el formulario
            success: function(data)
            {
              cargarContenido('Venta/loader', 'detalle');
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
            url : "<?php echo base_url('index.php/Venta/no_pagado');?>/"+id,
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
            url : "<?php echo base_url('index.php/Venta/pagado');?>/"+id,
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

    function anular(id,num) {
        Swal.fire({

      title: "Anular Comprobante?",
      text: "Usted esta intentando anular este comprobante de Venta Esto tambien afectara a los proceso de pagos realizados de este determinano Comprobante!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Anular Comprobante??",
      cancelButtonText: "Cancelar !",
      closeOnConfirm: false,
      closeOnCancel: true,
      showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            url : "<?php echo base_url('index.php/Venta/anular');?>/"+id,
            type: "POST",
            data: {num: num},
            success: function(data)
            {
              if (data == 'true') {
              reload_table()
              }else{
                toastem.error("No se han podido Modificar los datos");
              }

            },
        });
          Swal.fire("Anulado!", "Comprobante ha sido Anulado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }


</script>
