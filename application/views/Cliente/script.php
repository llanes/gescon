     <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
  <script type="text/javascript">
    $(function() {
        $("[data-mask]").inputmask();
    });
    var save_method; // VARIABLE DE CONTROL
    var tabla_Cliente; //  VARIABLE PARA LA TABLA  DE DADATABLE

    $(document).ready(function() 
    {
            $( "#Cliente,#Seguridad" ).addClass( "active" );
      $( "#c" ).addClass( "text-red" );
      $("#Cliente_aler").hide(); // oculto el contenedor de mensaje de confirmacion
         tabla_Cliente = $('#tabla_Cliente').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Cliente/ajax_list'); ?>",
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
       $("#from_Cliente").show(); 
       $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nuevo Cliente'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nuevo Cliente'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $(".NN,.AP,.DI,.TE,.CO,.LI,.RUC").html("").css({"display":"none"});
            $("#Cliente_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Cliente')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
    function resetear() {
         $('#tituloboton').text(' Agregar Nuevo Cliente'); // Fijar título para arrancar título 
          save_method = ''; 
    }

    function _edit(id)
    {
          $(".NN,.AP,.DI,.TE,.CO,.LI,.RUC").html("").css({"display":"none"});
          $("#Cliente_aler").hide(); // oculto el contenedor de mensaje de confirmacion
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Cliente')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
          $('#tituloboton').text(' Cerrar Edicion de Cliente');  // Fijar título para arrancar título 
          $("#from_Cliente").show(); 
          $('#btnSave').text('Actualizar');
      //los datos de carga de Ajax Ajax
      $.ajax({
        url : "<?php echo base_url('index.php/Cliente/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idCliente"]').val(data.idCliente);
              $('[name ="ruc"]').val(data.Ruc);
              $('[name ="Nombres"]').val(data.Nombres);
              $('[name ="Apellidos"]').val(data.Apellidos);
              $('[name ="Direccion"]').val(data.Direccion);
              $('[name ="Email"]').val(data.Correo);
              $('[name ="Telefono"]').val(data.Telefono);
              $('[name ="Limite_max_Credito"]').val(data.Limite_max_Credito);
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
      tabla_Cliente.ajax.reload(null,false); //reload datatable ajax 
    }

    $(function() {

    $(window).keypress(function(event) {
        if (!(event.which == 10 && event.ctrlKey) && !(event.which == 19)) return true;
        $("button[name=save]").click();
        event.preventDefault();
        return false;
    });
      $('#from_Cliente').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Cliente/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('index.php/Cliente/ajax_update'); ?>";
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
                            $(".NN,.AP,.DI,.TE,.EM,.LI").html("").css({"display":"none"});
                          if (json.res == "error") 
                          {
                              if (json.Nombres) {
                                 $(".NN").append(json.Nombres).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                               if (json.Apellidos) {
                                 $(".AP").append(json.Apellidos).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Direccion) {
                                 $(".DI").append(json.Direccion).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.ruc) {
                                 $(".RUC").append(json.ruc).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Telefono) {
                                 $(".TE").append(json.Telefono).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Email) {
                                 $(".EM").append(json.Email).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Limite_max_Credito) {
                                 $(".LI").append(json.Limite_max_Credito).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }
                          else
                          { 
                                                  setTimeout(function(){
                                                    $("#from_Cliente").hide(); // CERRAMOS  EL FORMULARIO
                                                    $('#Cliente_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                                    if (save_method == 'add') {
                                                      $('.title').text('Datos Registrado correctamente');
                                                    } else{
                                                      $('.title').text('Datos Actualizado correctamente');
                                                    }
                                                  },1000);
                                                  $("#Cliente_aler").fadeOut(1800);
                                                  setTimeout(function() {
                                                  $('#tituloboton').text(' Agregar Nuevo Cliente');  // Fijar título para 
                                                  $( "#collapseExample" ).removeClass( "in" );
                                                  save_method = ''; 
                                             },1910);
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
        text: "Usted no podrá recuperar este Cliente!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Cliente/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    $( "#collapseExample" ).removeClass( "in" );
                    $('#tituloboton').text(' Agregar Nuevo Cliente');
                    save_method = ''; 
                    reload_table();
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Cliente ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
