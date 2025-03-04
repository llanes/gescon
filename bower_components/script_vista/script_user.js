    var save_method; // VARIABLE DE CONTROL
    var tabla_Usuario; //  VARIABLE PARA LA TABLA  DE DADATABLE
    $(document).ready(function() {
      $( "#au,#Seguridad" ).addClass( "active" );
      $( "#_user" ).addClass( "text-red" );
       tabla_Usuario = $('#tabla_Usuario').DataTable({ 
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "userajax_list",
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
       $("#pasnew").hide();
       $("#password,#passconf").attr('required', 'required').removeAttr('disabled');
       $("#from_user").show(); 
       $('#btnSave').text('Guardar');
       $('#con').text('* Contraseña:');
       $('#pasantigua').removeAttr('requiered')   
      if (save_method == "add_add") {
         $('#tituloboton').text(' Agregar Nuevo Usuario'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nuevo Usuario'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add_add" && "update"){
            $('[name    ="0"]').val('');
            $('[name    ="0"]').text('');
            $(".US,.CO,.CC,.CA").html("").css({"display":"none"});  // A TRAVES DE LA CLACE OCULTO LOS CONTENEDORES DE ERROR
            $("#user_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_user')[0].reset(); // restablecer el formulario 
            save_method = 'add_add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
   function resetear() {
         $('#tituloboton').text(' Agregar Nuevo Usuario'); // Fijar título para arrancar título 
          save_method = ''; 
   }

   function verificar(argument) {
     $('.C_A').hide();
     var text      = $("#pasantigua").val();
     if (text !== ''  ) {
         $.ajax({
           url: 'verificarpass',
           type: 'POST',
           dataType: 'json',
           data: {val: text},
         })
         .done(function(data) {
          if (data==true) {
                $("#password,#passconf").attr('required', 'required').removeAttr('disabled');
                $('#pasantigua').attr('required', 'required'); 
                $('.C_A').html('').hide();
          }else{
               $("#password,#passconf").html('').attr('disabled','disabled');
               $('#pasantigua').html('').removeAttr('required'); 
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
         $("#password,#passconf").html('').attr('disabled','disabled');
         $('#pasantigua').html('').removeAttr('required');  
     }

   }

    function edit_User(id)
    {
        $("#pasnew").show(); 
        $('#pasantigua').removeAttr('required');  
        $("#password,#passconf").removeAttr('required').attr('disabled','disabled');
        $(".US,.CO,.CC,.CA,.C_A").html("").css({"display":"none"});  // A TRAVES DE LA CLACE OCULTO LOS CONTENEDORES DE ERROR
        $("#user_aler").hide(); // oculto el contenedor de mensaje de confirmacion
        save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
        $('#from_user')[0].reset(); // restablecer el formulario del modal por cualquien eventualidad
        $('#tituloboton').text(' Cerrar Edicion de Cliente');  // Fijar título para arrancar título 
        $("#from_user").show(); 
        $('#con').text('*Nueva Contraseña:');
 
        $('#btnSave').text('Actualizar');
      //los datos de carga de Ajax Ajax
      $.ajax({
        url : "userajax_edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idUsuario"]').val(data.idUsuario);
              $('[name ="usuario"]').val(data.Usuario);
              $('#cargo').val(data.Permiso_idPermiso).change();
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
      tabla_Usuario.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_user').submit(function(e) {
        var url;
        if(save_method == 'add_add') 
        {
          url = "userajax_add";
        }
        else
        {
          url = "userajax_update";
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
                            $(".US,.CO,.CC,.CA").html("").css({"display":"none"}); 
                            if (json.res == "error") {
                              if (json.cargo) {
                                 $(".CA").append(json.cargo).css({"display":"block"}); // mostrar validation de iten  cargo
                              }
                              if (json.usuario) {
                                 $(".US").append(json.usuario).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                              if (json.password) {
                                 $(".CO").append(json.password).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                             if (json.passconf) {
                                 $(".CC").append(json.passconf).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                             if (json.passanterior) {
                                 $(".C_A").append(json.passanterior).css({"display":"block"}); /// mostar validation  de iten pass
                              }
                            }else{ 

                                            setTimeout(function() {
                                            $("#from_user").hide(); // CERRAMOS  EL FORMULARIO
                                           $('#user_aler').show(); // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                            if (save_method == 'add_add') {
                                              $('.title').text('Registrado Correctamente');
                                            } else {
                                              $('.title').text('Datos Actualizado correctamente');
                                            }
                                            $("#user_aler").fadeOut(1500);
                                                    $('#tituloboton').text(' Agregar Nuevo Usuario');  // Fijar título para 
                                                  
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
    function delete_User(id)
     {
     Swal.fire({
        title: "Estas seguro?",
        text: "Usted no será capaz de recuperar este Usuario!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "userajax_delete/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    $( "#collapseExample" ).removeClass( "in" );
                    $('#tituloboton').text(' Agregar Nuevo Usuario');
                    save_method = ''; 
                    reload_table();   // si se  completo el eliminado refrescamos la tabla
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
