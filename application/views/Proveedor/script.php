     <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
    $(function() {
        $("[data-mask]").inputmask();
    });
    var save_method; // VARIABLE DE CONTROL
    var tabla_Proveedor; //  VARIABLE PARA LA TABLA  DE DADATABLE
    $(document).ready(function() {
            $( "#Proveedor,#Seguridad" ).addClass( "active" );
      $( "#p" ).addClass( "text-red" );
      // $( "#barra" ).addClass( "skin-blue sidebar-mini sidebar-collapse" );
         tabla_Proveedor = $('#tabla_Proveedor').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Proveedor/ajax_list'); ?>",
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
      $("#from_Proveedor").show(); 
       $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nuevo Proveedor'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nuevo Proveedor'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $(".RU,.RS,.DI,.TE,.EM,.PG,.VE").html("").css({"display":"none"});
            $("#Proveedorr_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Proveedor')[0].reset(); // restablecer el formulario 
            save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
   function resetear() {
         $('#tituloboton').text(' Agregar Nuevo Proveedor'); // Fijar título para arrancar título 
          save_method = ''; 
    }


    function _edit(id)
    {
        $(".RU,.RS,.DI,.TE,.EM,.PG,.VE").html("").css({"display":"none"});
        $("#Proveedorr_aler").hide(); // oculto el contenedor de mensaje de confirmacion
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
        $('#from_Proveedor')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
        $('#tituloboton').text(' Cerrar Edicion de Cliente');  // Fijar título para arrancar título 
        $("#from_Proveedor").show(); 
        $('#btnSave').text('Actualizar');
      //los datos de carga de Ajax Ajax
      $.ajax({
        url : "<?php echo base_url('index.php/Proveedor/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idProveedor"]').val(data.idProveedor);
              $('[name ="Ruc"]').val(data.Ruc);
              $('[name ="Razon_Social"]').val(data.Razon_Social);
              $('[name ="Direccion"]').val(data.Direccion);
              $('[name ="Telefono"]').val(data.Telefono);
              $('[name ="Correo"]').val(data.Correo);
              $('[name ="Pagina_Web"]').val(data.Pagina_Web);
              $('[name ="Vendedor"]').val(data.Vendedor);
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
      tabla_Proveedor.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Proveedor').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Proveedor/ajax_add'); ?>";
        }
        else
        {
          url = "<?php echo base_url('index.php/Proveedor/ajax_update'); ?>";
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                        success : function(data) {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                              $(".RU,.RS,.DI,.TE,.EM,.PG,.VE").html("").css({"display":"none"});
                          if (json.res == "error") {
                            if (json.Ruc) {
                               $(".RU").append(json.Ruc).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                             if (json.Razon_Social) {
                               $(".RS").append(json.Razon_Social).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Direccion) {
                               $(".DI").append(json.Direccion).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Telefono) {
                               $(".TE").append(json.Telefono).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Correo) {
                               $(".EM").append(json.Correo).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Pagina_Web) {
                               $(".PG").append(json.Pagina_Web).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                              if (json.Vendedor) {
                                 $(".VE").append(json.Vendedor).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }else{ 
                                          $("#from_Proveedor").hide(); // CERRAMOS  EL FORMULARIO
                                          $('#Proveedorr_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                          if (save_method == 'add') {
                                              $('.title').text('Datos Registrado correctamente');
                                          } else {
                                              $('.title').text('Datos Actualizado correctamente');
                                          }
                                                  $("#Proveedorr_aler").fadeOut(1500);
                                                  setTimeout(function() {
                                                    $('#tituloboton').text(' Agregar Nuevo Proveedor');  // Fijar título para 
                                                    $( "#collapseExample" ).removeClass( "in" );
                                                    save_method = ''; 
                                             },1510);
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
        text: "Usted no será capaz de recuperar este Proveedor!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Proveedor/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                      $( "#collapseExample" ).removeClass( "in" );
                      $('#tituloboton').text(' Agregar Nuevo Proveedor');
                      save_method = ''; 
                      reload_table();
                })
                .fail(function() {
                  Swal.fire('Error Proveedor asociado en alguna transacción');
                });
          Swal.fire("Eliminado!", "Proveedor ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
