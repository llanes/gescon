  <script src ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
    <script src ="<?php echo base_url();?>bower_components/select2/dist/js//i18n/es.js"></script>
  <script type="text/javascript">
    var save_method; // VARIABLE DE CONTROL
    var tabla_Permiso; //  VARIABLE PARA LA TABLA  DE DADATABLE

    $(document).ready(function() 
    {
      $(".multi").select2({
            placeholder: "Selecciona Menu",
      });
      $( "#ap,#Seguridad" ).addClass( "active" );
      $("#Permiso_aler").hide(); // oculto el contenedor de mensaje de confirmacion
       $( "#_Per" ).addClass( "text-red" );
         tabla_Permiso = $('#tabla_Permiso').DataTable({
        "processing": true, //Característica de control del indicador de procesamiento.
        "serverSide": true, // el modo de procesamiento de servidor de control de tablas de datos de características .
        // Datos de carga de contenidos de la tabla de un origen Ajax
        "ajax": {
            "url": "<?php echo base_url('index.php/Permiso/ajax_list'); ?>",
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
       $("#from_Permiso").show(); 
       $('#btnSave').text('Guardar');
      if (save_method == "add") {
         $('#tituloboton').text(' Agregar Nueva Permiso'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method == "update") {
         $('#tituloboton').text(' Agregar Nueva Permiso'); // Fijar título para arrancar título 
         save_method = ''; 
      } else if (save_method != "add" && "update"){
            $(".NN,.DD").html("").css({"display":"none"});
            $("#Permiso_aler").hide(); // oculto el contenedor de mensaje de confirmacion
            $('#from_Permiso')[0].reset(); // restablecer el formulario 
             save_method = 'add'; 
            $('#tituloboton').text(' Cerrar'); // Fijar título para arrancar título 

      }
    }
    function resetear() {
         $('#tituloboton').text(' Agregar Nueva Permiso'); // Fijar título para arrancar título 
          save_method = ''; 
    }

    function _edit(id)
    {
          $(".NN,.DD").html("").css({"display":"none"});
          $("#Permiso_aler").hide(); // oculto el contenedor de mensaje de confirmacion
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Permiso')[0].reset(); // restablecer el formulario del  por cualquien eventualidad
          $('#tituloboton').text(' Cerrar Edicion de Permiso');  // Fijar título para arrancar título 
          $("#from_Permiso").show(); 
          $('#btnSave').text('Actualizar');
      //los datos de carga de Ajax Ajax
      $.ajax({
        url : "<?php echo base_url('index.php/Permiso/ajax_edit/'); ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            // mostraremos los datos necesario  octenidos por ajax
              $('[name ="idPermiso"]').val(data.idPermiso);
              $('[name ="Nombre"]').val(data.Nombre);
              $('[name ="Oservacion"]').val(data.Oservacion);
              $.post("<?php echo site_url('/Permiso/permiso_has/'); ?>/"+id, 
                function(res) {
                    var conten = [];
                    var conten = new Array();
                          var json = JSON.parse(res);
                         $.each(json, function(index, val) {
                            conten.push(val.id2);
                      });
                      $("#multi").val(conten).trigger("change");

              });
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
    function _permiso(id) {
      $("#res_per").html("").css({"display":"none"});
      $.ajax({
        url : "<?php echo base_url('index.php/Permiso/permiso_has/'); ?>/" + id,
        type: "GET",
      })
      .done(function(data) {
               var result = JSON.parse(data);
               $.each(result, function(index, val) {
                     $("#res_per").append('<li>'+val.res+'</li>').css({"display":"block"});;
              });
              $( "#collaje_Permiso" ).addClass( "in" );
              $("#collaje_Permiso").attr('aria-expanded', 'true');
              $("#collaje_Permiso").removeAttr( 'style' );
      })
      .fail(function(res) {
           toastem.error('Disculpe, existió un problema');
      })
      .always(function(res) {
        // toastem.error('Disculpe, existió un problema');
      });
      
    }

    function reload_table()
    {
      tabla_Permiso.ajax.reload(null,false); //reload datatable ajax 
    }
    $(function() {
      $('#from_Permiso').submit(function(e) {
        var url;
        if(save_method == 'add') 
        {
          url = "<?php echo base_url('index.php/Permiso/ajax_add'); ?>";
        }
        else
        {

          url = "<?php echo base_url('index.php/Permiso/ajax_update'); ?>";
        }
             $.ajax({
                        type : 'POST',
                        url : url, // octengo la url del formulario
                        data: $(this).serialize(), // serilizo el formulario

                        success : function(data) 
                        {
                           var json = JSON.parse(data);// parseo la dada devuelta por json
                           $(".NN,.DD").html("").css({"display":"none"});
                          if (json.res == "error") 
                          {
                              if (json.Nombre) {
                                 $(".NN").append(json.Nombre).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                               if (json.Oservacion) {
                                 $(".DD").append(json.Oservacion).css({"display":"block"}); // mostrar validation de iten usuario
                              }
                          }
                          else
                          { 
                                          $("#from_Permiso").hide(); // CERRAMOS  EL FORMULARIO
                                          $('#Permiso_aler').show() // ABRIMOS UN MENSAJE DE CORNFIRMACION DE REGISTRO O EDIDCION
                                          if (save_method == 'add') {
                                            $('.title').text('Datos Registrado correctamente');
                                          } else{
                                            $('.title').text('Datos Actualizado correctamente');
                                          }
                                                  $("#Permiso_aler").fadeOut(1500);
                                                  setTimeout(function() {
                                                  $('#tituloboton').text(' Agregar Nueva Permiso');  // Fijar título para 
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
        text: "Usted no podrá recuperar este Permiso!",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Eliminar !",
        cancelButtonText: "Cancelar !",
      },
      function(isConfirm) {
        if (isConfirm) {
            // ajax delete datos de database
                $.ajax({
                  url : "<?php echo base_url('index.php/Permiso/ajax_delete'); ?>/"+id,
                  type: "POST",
                  dataType: "JSON",
                  cache: false,
                })
                .done(function() {  // done el igual al success  solo que es mas seguro
                    $( "#collapseExample" ).removeClass( "in" );
                    $('#tituloboton').text(' Agregar Nueva Permiso');
                    save_method = ''; 
                    reload_table();
                })
                .fail(function() {
                  Swal.fire('Error al intentar borrar');
                });
          Swal.fire("Eliminado!", "Permiso ha sido borrado.", "success");
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
      });
    }
</script>
