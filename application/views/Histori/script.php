 <script type="text/javascript">
       var registro_histori_ajax = $('#registro_histori_ajax').dataTable({
              "processing": true, //Característica de control del indicador de procesamiento.
              "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
              ajax: {
                  url: "<?php echo base_url('index.php/Caja/histori_list'); ?>",
                  type: "POST",
                  dataSrc: function(data){
                 return data.data;
                }
              },
              //Set column definition initialisation properties.
              columnDefs: [
              { 
                targets: [ -1 ], //last column
                orderable: false, //set not orderable
              },
              ], 
              "order": [[ 0, 'desc' ], [ 1, 'desc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ]]

            });
    registro_histori_ajax.fnClearTable();

     $(function() {
        $( "#_his,#Caja" ).addClass( "active" );
        $( "#_his_" ).addClass( "text-red" );
    });

  </script>