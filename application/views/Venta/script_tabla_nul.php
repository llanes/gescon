
<script type="text/javascript">
var tabla;
$( "#NUL,#Venta" ).addClass( "active" );
$( "#NUL,#N_U_L" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
  function format ( d ) {
      var id     =  d.slice(4) ;
      $.ajax({
      type : 'POST',
      url: "<?php echo base_url('index.php/Venta/detale');?>/"+id,
      dataType: 'json',
      })
      .done(function(data) {
        if (data) {
          $.each(data, function(index, val) { 
           var sub ='';
           if (val.Descuento != '') {
             sub = (parseInt(val.can) * (parseInt(val.Precio) - parseInt(val.Descuento) ) );
           }else{
            sub = (parseInt(val.can) * parseInt(val.Precio));
           }
            $('#'+id).append('<tr class="success"><td>'+val.can+'</td><td>'+val.Nombre+'</td><td>'+formatNumber.new(val.Precio)+' ₲.</td><td>'+formatNumber.new(val.Descuento)+'</td><td>'+formatNumber.new(sub)+' ₲.</td></tr>');
          });
        }
      });
      return '<table cellpadding="5" cellspacing="0" border="0" class="table table-striped table-bordered" id="'+id+'">'+
          '<tr class="danger">'+
              '<td>Cantidad</td>'+
              '<td>Nombre</td>'+
              '<td>Precio</td>'+
               '<td>Descuento</td>'+
               '<td>Subtotal</td>'+
          '</tr>'+
      '</table>';
  }
$(function() {
    tabla = $('#tabla_nul').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Venta/anulados'); ?>",
            "type": "POST"
        },

        "columns": [
            {
                "targets":  [ -1, -0, 2 ], // ultimass columnas
                "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
                "className":      'details-control',
                "data":           null,
                "defaultContent": '',



            },
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },

        ],
        "order": [[ 0, 'asc' ], [ 1, 'asc' ],[ 2, 'desc' ],[ 3, 'desc' ]]
      });

    $('#tabla_nul tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tabla.row(tr);
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
</script>
