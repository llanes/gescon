    var registro_caja_ajax = $('#registro_caja_ajax').dataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
    ajax: {
      url: "Caja/caja_list",
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
    "order": [[ 0, 'desc' ], [ 1, 'desc' ],[ 2, 'desc' ],[ 3, 'desc' ],[ 4, 'desc' ],[ 5, 'desc' ]]
    });


  $(function() {
    $( "#m_c,#Caja" ).addClass( "active" );
    $( "#_m_c" ).addClass( "text-red" );
  });
  function edit_caja(id)
  {
    Swal.fire({
      title: "Desar volver a Abrir?",
      showCancelButton: true,
      confirmButtonText: "Abrir Caja !",
      cancelButtonText: "Cancelar !"
    }).then(result => {
      if (result.value) {
        $.ajax({
          url: "Caja/edit_caja/" + id,
          type: "POST",
          success: function(data)
          {
            setTimeout(function(){
              window.location.replace("Caja");
            },500)
            toastem.abrir("La Caja fue abierta!!!");

          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            toastem.error('Error los datos no se han Modificado');
          }
        })
        Swal.fire("OK!", "Abierta Correctamente.", "success");
      } else {
        Swal.fire("Cancelled", "Sin accion:)", "error");
      }

    })
  }
