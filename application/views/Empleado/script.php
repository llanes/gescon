     <script src="<?php echo base_url('content/plugins/input-mask/jquery.inputmask.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript">
    $(function() {
        $("[data-mask]").inputmask();
    });
    var text      = $("#Usuario").val();
    var save_method; // VARIABLE DE CONTROL
    var tabla_Empleado; //  VARIABLE PARA LA TABLA  DE DADATABLE
    $(document).ready(function() {
      $( "#Empleado,#Seguridad" ).addClass( "active" );
      $( "#e" ).addClass( "text-red" );
         tabla_Empleado = $('#tabla_Empleado').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Empleado/ajax_list'); ?>",
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

function verificar(arguments) {
 var text      = $("#Usuario").val();
 if (text !== '' && save_method == 'add_add') {
     $("#Password,#passconf,#Cargo").removeAttr('disabled');
 }
  if (text == '' && save_method == 'add_add') {
     $("#Password,#passconf,#Cargo").attr('disabled','disabled');
 }

}
function verifica(argument) {
     $('.C_A').hide();
     var text      = $("#pasantigua").val();
     if (text !== ''  ) {
         $('#pasantigua').attr('required', 'required'); 
         $.ajax({
           url: '<?php echo base_url('index.php/Admin_User/verificarpass'); ?>',
           type: 'POST',
           dataType: 'json',
           data: {val: text},
         })
         .done(function(data) {
          if (data==true) {
                $("#Password,#passconf").attr('required', 'required').removeAttr('disabled');
                $('#pasantigua').attr('required', 'required'); 
                $('.C_A').html('').hide();
          }else{
               $("#Password,#passconf").html('').attr('disabled','disabled');
                $('.C_A').html('Contraseña Incorrecta').show();
          }
         })
         .fail(function() {
           console.log("error"); 
         })
         .always(function() {
           console.log("complete");
         });
     

 
     }
      if (text == '') {
         $("#Password,#passconf").html('').attr('disabled','disabled');
         $('#pasantigua').html('').removeAttr('required');  
     }

   }


    function _add()
    {
        $("#from_Empleado").show();  $('#con').text('* Contraseña:'); $('#pasantigua').removeAttr('requiered')   
        $('#btnSave').text('Guardar');
        $("#pasnew").hide();
        $("#Password,#passconf,#Cargo").attr('disabled','disabled');
      if (save_method == "add_add") {
         $('#tituloboton').text(' Agregar Nuevo Empleado'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nuevo Empleado'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add_add" && "update"){
            $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.CO,.CA,.CC,.US").html("").css({"display":"none"});
            $("#Empleador_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Empleado')[0].reset(); // restablecer el formulario 
            save_method = 'add_add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
    function resetear() {
         $('#tituloboton').text(' Agregar Nuevo Empleado'); // Fijar título para arrancar título 
          save_method = ''; 
    }

    function _edit(id)
    {

        $("#Password,#passconf,#Cargo").attr('disabled','disabled'); $('.C_A').hide();
        $(".NN,.CE,.DI,.RU,.TE,.EM,.TI,.CO,.CA,.CC,.US").html("").css({"display":"none"});
        $("#Empleador_aler").hide(); // oculto el contenedor de mensaje de confirmacion
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
        $('#from_Empleado')[0].reset(); // restablecer el formulario del modal por cualquien eventualidad
        $('#tituloboton').text(' Cerrar Edicion de Empleado');  // Fijar título para arrancar título 
        $("#from_Empleado").show(); 
        $('#btnSave').text('Actualizar');

      $.ajax({
        url : "<?php echo base_url('index.php/Empleado/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idUsuario"]').val(data.idUsuario);
              $('[name ="idEmpleado"]').val(data.idEmpleado);
              $('[name ="Nombres"]').val(data.Nombres);
              $('[name ="Apellidos"]').val(data.Apellidos);
              $('[name ="Direccion"]').val(data.Direccion);
              $('[name ="Email"]').val(data.Correo);
              $('[name ="Telefono"]').val(data.Telefono);
              $('[name ="Sueldo"]').val(data.Sueldo);
              $('[name ="Timbrado"]').val(data.Timbrado);
              if ( data.Usuario !== null && data.Usuario !== '') {
                 $("#pasnew").show(); 
                 $('#pasantigua').removeAttr('required');  
                 $('#con').text('*Nueva Contraseña:');
                 $('[name ="Usuario"]').val(data.Usuario);
                  $("#Password,#passconf").html('').attr('disabled','disabled');
                   $('#Cargo').val(data.Permiso_idPermiso).change().removeAttr('disabled');
                  $('#pasantigua').html('').removeAttr('required');  
              }else{
                 $("#pasnew").hide();
                 $('#con').text('* Contraseña:');
                  $('#Cargo').attr('disabled','disabled');
              }
        $( "#collapseExample" ).collapse('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.fire('Error al obtener datos');
        }
    });
    }

    function reload_table()
    {
      tabla_Empleado.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Empleado').submit(function(e) {
        var url;
        if(save_method == 'add_add') 
        {
          url = "<?php echo base_url('index.php/Empleado/ajax_add'); ?>";
        }
        else
        {
          url = "<?php echo base_url('index.php/Empleado/ajax_update'); ?>";
       }
          var b = $('#loadingg');
          b.button("loadingg"), setTimeout(function() {
              b.button("reset");
          }, 1000);
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario
                        success : function(data) {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                            $(".NN,.AP,.DI,.RU,.TE,.EM,.SU,.CO,.CA,.CC,.US").html("").css({"display":"none"});
                          if (json.res == "error") {
                            if (json.Nombres) {
                               $(".NN").append(json.Nombres).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                             if (json.Apellidos) {
                               $(".AP").append(json.Apellidos).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Direccion) {
                               $(".DI").append(json.Direccion).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Telefono) {
                               $(".TE").append(json.Telefono).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Email) {
                               $(".EM").append(json.Email).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                            if (json.Sueldo) {
                               $(".SU").append(json.Sueldo).css({"display":"block"}); // mostrar validation de iten usuario
                            }
                              if (json.Usuario) {
                                 $(".US").append(json.Usuario).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.Password) {
                                 $(".CO").append(json.Password).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                             if (json.passconf) {
                                 $(".CC").append(json.passconf).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                               if (json.passanterior) {
                                 $(".C_A").append(json.passanterior).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                          }else{

                                         setTimeout(function() {
                                          $("#from_Empleado").hide(); // CERRAMOS  EL FORMULARIO
                                          $('#Empleador_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                            if (save_method == 'add_add') {
                                              $('.title').text('Registrado Correctamente');
                                            } else {
                                              $('.title').text('Datos Actualizado correctamente');
                                            }
                                            $("#Empleador_aler").fadeOut(1500);
                                                     $('#tituloboton').text(' Agregar Nuevo Empleado');  // Fijar título para 
                                                  
                                             setTimeout(function() {  $( "#collapseExample" ).collapse('hide');}, 1500);
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
        text: "Usted no será capaz de recuperar este Empleado!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Empleado/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                  $( "#collapseExample" ).removeClass( "in" );
                  $('#tituloboton').text(' Agregar Nuevo Empleado');
                  save_method = ''; 
                  reload_table();
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Empleado ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
