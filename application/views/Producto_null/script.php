  <script type="text/javascript">
    var tabla_Producto,tablaVencidos; //  VARIABLE PARA LA TABLA  DE DADATABLE
    $(document).ready(function() 
    {
      $( "#perd,#Producto" ).addClass( "active" );
      $( "#_perd" ).addClass( "text-red" );

         tabla_Producto = $('#tabla_Producto').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Producto_null/ajax_list'); ?>",
            "type": "POST",
            'data': {data: 'Descompuesto'},
        },

        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });


         tablaVencidos = $('#tablaVencidos').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Producto_null/ajax_list'); ?>",
            "type": "POST",
            'data': {data: 'Vencido'},
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

    $(function() {
      $('.des').on('click', function(e) {
         $('#controllll').val('Descompuesto');
      });
      $('.ven').on('click', function(e) {
         $('#controllll').val('Vencido');
          
      });
    });
</script>
