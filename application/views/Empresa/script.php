     <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
  <script type="text/javascript">
    $(function() {
        $("[data-mask]").inputmask();
    });
    var save_method; // VARIABLE DE CONTROL
    var tabla_Empresa; //  VARIABLE PARA LA TABLA  DE DADATABLE
    $(document).ready(function() {
      $( "#ae,#Seguridad" ).addClass( "active" );
      $( "#_emp" ).addClass( "text-red" );
       tabla_Empresa = $('#tabla_Empresa').DataTable({ 
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Empresa/ajax_list'); ?>",
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

    function edit_empresa(id)
    {
        $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.SE,.LMC,.Num").html("").css({"display":"none"});
        $("#empresar_aler").hide(); // oculto el contenedor de mensaje de confirmacion
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
        $('#from_empresa')[0].reset(); // restablecer el formulario del modal por cualquien eventualidad
        $('#tituloboton').text(' Cerrar Edicion de Empresa');  // Fijar título para arrancar título 
        $("#from_empresa").show(); 
        $('#btnSave').text('Actualizar');
      //los datos de carga de Ajax Ajax
      $.ajax({
        url : "<?php echo base_url('index.php/Empresa/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idEmpresa"]').val(data.idEmpresa);
              $('[name ="Nombre"]').val(data.Nombre);
              $('[name ="Descripcion"]').val(data.Descripcion);
              $('[name ="Direccion"]').val(data.Direccion);
              $('[name ="ruc"]').val(data.R_U_C);
              $('[name ="Telefono"]').val(data.Telefono);
              $('[name ="Email"]').val(data.Email);
              $('[name ="Timbrado"]').val(data.Timbrado);
              $('[name ="Series"]').val(data.Series);
              $('[name ="Comprobante"]').val(data.Comprovante);
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
      tabla_Empresa.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_empresa').submit(function(e) {
        var url = "<?php echo base_url('index.php/Empresa/ajax_update'); ?>";
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                        success : function(data) {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                             $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.SE,.LMC,.Num").html("").css({"display":"none"});
                          if (json.res == "error") {
                            if (json.Nombre) {
                               $(".NN").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Comprobante) {
                               $(".Num").append(json.Comprobante).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Direccion) {
                               $(".DI").append(json.Direccion).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                             if (json.R_U_C) {
                               $(".RU").append(json.R_U_C).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Telefono) {
                               $(".TE").append(json.Telefono).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Email) {
                               $(".EM").append(json.Email).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Timbrado) {
                               $(".TI").append(json.Timbrado).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Series) {
                               $(".SE").append(json.Series).css({"display":"block"}); /// mostar validation  de iten pass
                            }
                           if (json.Comprovante) {
                               $(".LMC").append(json.Comprovante).css({"display":"block"}); /// mostar validation  de iten pass
                            }
                          }else{ 
                                          $("#from_empresa").hide(); // CERRAMOS  EL FORMULARIO
                                          $('#empresar_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                          $('.title').text('Datos Actualizado correctamente');
                                           $("#empresar_aler").fadeOut(1500);
                                                  setTimeout(function() {
                                                  $( "#collapseExample" ).removeClass( "in" );
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
    function delete_empresa(id)
     {
     Swal.fire({
        title: "Estas seguro?",
        text: "Usted no será capaz de recuperar este Presupuesto!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Empresa/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    $( "#collapseExample" ).removeClass( "in" );
                    save_method = '';
                    location.reload();
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Usuario ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
