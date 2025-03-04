  <script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Cambio; //  VARIABLE PARA LA TABLA  DE DADATABLE

    $(document).ready(function() 
    {
      $( "#Seguridad,#cambios" ).addClass( "active" );
      $("#cambioalert").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#_cate" ).addClass( "text-red" );
         tabla_Cambio = $('#tabla_Cambio').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Cambio/ajax_list'); ?>",
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

     function _add()
    {
       $(".CA,.MA").html("").css({"display":"none"});
       $("#from_Cambio").show(); 
       $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Cambio'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Cambio'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $(".NN,.DD").html("").css({"display":"none"});
            $("#cambioalert").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Cambio')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
      function resetear() {
         $('#tituloboton').text(' Agregar Nueva Cambio'); // Fijar título para arrancar título 
          save_method = ''; 
         $( "#collapseExample" ).hide();
    }

    function _edit(id)
    {
          $(".CA,.MA,#cambioalert").html("").css({"display":"none"});
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Cambio')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
          $('#tituloboton').text(' Cerrar Edicion de Cambio');  // Fijar título para arrancar título 
          $('#btnSave').text('Actualizar');

      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/Cambio/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                  $('[name ="idCambio"]').val(id);
                  $('[name ="Cambio"]').val(data.Cambio);
                  $('[name ="Moneda"]').val(data.Moneda);
                  $('[name ="Estado"]').val(data.Estado).change();
                  $("#from_Cambio").show(); 
                  $("#collapseExample").show();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
    }

    function reload_table()
    {
      tabla_Cambio.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Cambio').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Cambio/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('index.php/Cambio/ajax_update'); ?>";
        }
          var b = $('#loadingg');
          b.button("loadingg"), setTimeout(function() {
              b.button("reset");
          }, 1000);
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario

                        success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                            $(".CA,.DE").html("").css({"display":"none"});
                          if (json.res == "error") 
                          {
                              if (json.Codigo) {
                                 $(".CA").append(json.Codigo).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                               if (json.Nombre) {
                                 $(".DE").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Nombre) {
                                 $(".DE").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }
                          else
                          {
                                       setTimeout(function() {
                                            $("#from_Cambio").hide(); // CERRAMOS  EL FORMULARIO
                                                      if (save_method == 'add') {
                                                        $('#cambioalert').append('<div class="alert alert-info"><strong class="title" >Datos Registrado correctamente</strong></div>').show();
                                                      } else{
                                                        $('#cambioalert').append('<div class="alert alert-info"><strong class="title" >Datos Actualizado correctamente</strong></div>').show();
                                                      }
                                            $("#cambioalert").fadeOut(1500);
                                                    $('#tituloboton').text(' Agregar Nuevo Cambio');  // Fijar título para 
                                                  
                                             setTimeout(function() {      $( "#collapseExample" ).hide(); }, 1500);
                                                    save_method = ''; 
                                              },1000);
                                          reload_table(); // recargar la tabla automaticamente
                          }
                       },
                        // código a ejecutar si la petición falla;
                        error : function(xhr, status) {
                            Swal.fire('Disculpe, existió un problema');
                            console.log('error(jqXHR, textStatus, errorThrown)');
                        },
                    });
        e.preventDefault();
      })
    });
    function _delete(id)
     {
     Swal.fire({
        title: "Estas seguro?",
        text: "No podrá recuperar el Cambio!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Cambio/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    $( "#collapseExample" ).removeClass( "in" );
                    $('#tituloboton').text(' Agregar Nueva Marca');
                    save_method = ''; 
                    reload_table();
                })
                .fail(function() {
                  setTimeout(function(){
                  toastem.error("Trate de desvincular de su Producto!!!");
                  },2000)
                  toastem.error("Error al intentar Borrar, Cambio en Uso!!!");

                });
          Swal.fire("Eliminado!", "Cambio ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
