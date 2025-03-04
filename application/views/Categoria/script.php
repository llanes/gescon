  <script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Categoria; //  VARIABLE PARA LA TABLA  DE DADATABLE

    $(document).ready(function() 
    {
      $( "#Producto" ).addClass( "active" );
      $("#Categoria_aler").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#_cate" ).addClass( "text-red" );
         tabla_Categoria = $('#tabla_Categoria').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Categoria/ajax_list'); ?>",
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
       $("#from_Categoria").show(); 
       $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Categoria'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Categoria'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $(".NN,.DD").html("").css({"display":"none"});
            $("#Categoria_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Categoria')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
      function resetear() {
         $('#tituloboton').text(' Agregar Nueva Categoria'); // Fijar título para arrancar título 
          save_method = ''; 
    }

    function _edit(id)
    {
          $(".CA,.MA").html("").css({"display":"none"});
          $("#Categoria_aler").hide(); // oculto el contenedor de mensaje de confirmacion
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Categoria')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
          $('#tituloboton').text(' Cerrar Edicion de Categoria');  // Fijar título para arrancar título 
          $("#from_Categoria").show(); 
          $('#btnSave').text('Actualizar');

      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "<?php echo base_url('index.php/Categoria/ajax_edit/'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                  $('[name ="idCategoria"]').val(id);
                  $('[name ="Categoria"]').val(data.Categoria);
                  $('[name ="Descrip"]').val(data.Descrip);
                  $( "#collapseExample" ).addClass( "in" );
                  $("#collapseExample").attr('aria-expanded', 'true');
                  $("#collapseExample").removeAttr( 'style' );
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
    }

    function reload_table()
    {
      tabla_Categoria.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Categoria').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Categoria/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('index.php/Categoria/ajax_update'); ?>";
        }

             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                        beforeSend: function(){
                          $('#clic').attr("disabled","disabled");
                          $('#from_Categoria').css("opacity",".4");
                        },
                        success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                            $(".CA,.DE").html("").css({"display":"none"});
                          if (json.res == "error") 
                          {
                            $('#from_Producto').css("opacity","");
                            $('#clic').removeAttr('disabled');

                              if (json.Codigo) {
                                 $(".CA").append(json.Codigo).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                               if (json.Nombre) {
                                 $(".DE").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }
                          else
                          { 

                              if (save_method == 'add') {
                               $title = ('Datos Registrado correctamente');
                              } else{
                               $title = ('Datos Actualizado correctamente');
                              }
                              $('#from_Categoria')[0].reset();
                               $('#from_Categoria').css("opacity","");
                               $('#clic').removeAttr('disabled');
                              alertasave($title);
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
        text: "No podrá recuperar el Categoria!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Categoria/ajax_delete'); ?>/"+id,
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
                  toastem.error("Error al intentar Borrar, Categoria en Uso!!!");

                });
          Swal.fire("Eliminado!", "Categoria ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
