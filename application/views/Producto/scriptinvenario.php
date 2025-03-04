  <script type="text/javascript">

    var edit_img = '';
    var save_method; // VARIABLE DE CONTROL
    var listatodo,bajostock; //  VARIABLE PARA LA TABLA  DE DADATABLE
    var inputFile = $('input[name=file]');
    $(document).ready(function() 
    {
      $( "#inven,#Producto" ).addClass( "active" );
      $( "#_inven" ).addClass( "text-red" );
      // $("#barra").addClass("sidebar-collapse");
         listatodo = $('#listatodo').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Inventario/ajax_list'); ?>",
            "type": "POST"
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });

         bajostock = $('#bajostock').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Inventario/bajostock'); ?>",
            "type": "POST"
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });


    });

    function reload_table()
    {
      listatodo.ajax.reload(null,false); //reload datatable ajax 
    }

</script>
