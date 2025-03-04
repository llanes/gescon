    $(function() {
          var tabla_Stock; //  VARIABLE PARA LA TABLA  DE DADATABLE
          $( "#Producto" ).addClass( "active" );
          $( "#_stc" ).addClass( "text-red" );
    });
    function stocktodas(data) {
         $('#tabla_Stock').DataTable().destroy();
         tabla_Stock = $('#tabla_Stock').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "ajax_list_stock",
            "type": "POST",
            "data" : {val:  data}
        },
        //Conjunto de columnas propiedades de definición de inicialización .
        "columnDefs": [
        { 
          "targets": [ -1 ], // ultimass columnas
          "orderable": false, //  Desavilita 0 activar para ordenar la columna ascendente desendiente
        },
        ],

      });
    }

    function reload_table()
    {
      tabla_Stock.ajax.reload(null,false); //reload datatable ajax 
    }


    
