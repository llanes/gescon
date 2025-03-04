  $(function(){
        const urlParams = new URLSearchParams(window.location.search);
        const codigoBarraParam = urlParams.get('codeBarra'); // Ajusta según el nombre de tu parámetro
        if (codigoBarraParam !== null) {
            // acer click sobre el elemento de <li role="presentation" id="">
            $('#someTab').click(); // Simula un clic en el enlace
            $("#Codigo").val(codigoBarraParam);

        } else {
            // Manejar el caso donde el parámetro no está presente
            console.log('El parámetro codeBarra no está presente en la URL.');
        }

        $(".toggleNavbar").fadeToggle();

    $('.multi').select2({
    ajax: {
        url: 'Proveedor/select2remote',
        dataType: 'json',
        delay: 250,
        data: function (params) {
        return {
            q: params.term, // Término de búsqueda
            page: params.page // Página actual
        };
        },
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
              results: $.map(data.items, function (item) {
                return {
                  id: item.id,
                  text: item.full_name
                };
              }),
              pagination: {
                more: false
              }
            };
          },
          
        cache: true
    },
    placeholder: 'Busca y Selecciona',
    width: null,
    allowClear: true,
    minimumInputLength: 1,
    theme: 'bootstrap'
    });




 
   });
    var edit_img = '';
    var save_method; // VARIABLE DE CONTROL
    var tabla_Producto; //  VARIABLE PARA LA TABLA  DE DADATABLE
    var inputFile = $('input[name=file]');
    $(document).ready(function() 
    {
      $( "#pr,#Producto" ).addClass( "active" );
      $( "#_pro" ).addClass( "text-red" );

tabla_Producto = $('#tabla_Producto').DataTable({
  "processing": false,
  "serverSide": true,
  "ajax": {
    "url": "Producto/ajax_list",
    "type": "POST"
  },
  "columns": [
    {
      "orderable": true,
      "className": 'details-control',
      "data": null,
      "defaultContent": '',
    },
    { "data": "0" },
    { "data": "1" },
    { "data": "2" },
    { "data": "3" },
    { "data": "4" },
    { "data": "5" },
    { "data": "6" },
    { "data": "7" },
  ],
});

      $('#tabla_Producto tbody').on('click', 'td.details-control', function () {
        var tr  = $(this).closest('tr');
        var row = tabla_Producto.row( tr );
        if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        }
        else {
          // Open this row
          row.child( format(row.data()) ).show();
          tr.addClass('shown');
          
        }
      } 

  );

      
    });
        function format ( d ) {
        var id     =  d.slice(8) ;
        var medidas = {
            'kg': 'kilo',
            'mg': 'Gramo',
            'kl': 'Litro',
            'ml': 'Mililitro',
            'un': 'Unidad',
            'dc': 'Docena',
            'cn': 'Centena'
        };
        var $A1, $A2, $A4;
        $.ajax({
            type : 'POST',
            url: "Producto/detale/"+id,
            dataType: 'json',
        })
        .done(function(data) {
            if (data) {
            var tbody = $('#' + id);
            $.each(data, function(index, val) {
                $A1 = val.A1;
                $A2 = val.A2;
                $A4 = val.A4;
                var medida = medidas[val.A5] || '';
                tbody.append(
                '<tr class="">'+
                    '<td>'+$A1+' ₲.</td>'+
                    '<td>'+$A2+' ₲.</td>'+
                    '<td>'+val.A3+'</td>'+
                    '<td>'+$A4+'  '+medida+'</td>'+
                    '<td >'+val.Proveedores+'</td>'+
                '</tr>'
                );
            });
            }
        })
        .fail(function() {
            console.log('Error en la llamada AJAX');
        });
        return '<div class="box box-info">'+
                '<div class="box-body"><div class="table-responsive">'+
                    '<table class="table no-margin">'+
                    '<thead><tr class="danger">'+
                        '<th>Precio Compra</th>'+
                        '<th>Precio Venta</th>'+
                        '<th>Stock Minimo</th>'+
                        '<th>Unidad Medida</th>'+
                        '<th>Proveedore</th>'+
                    '</tr></thead>'+
                    '<tbody id="'+id+'"> </tbody></table>'+
                '</div></div></div>';
        }


        function _add() {
        var $elements = $(".ME,.CD,.SM,.DE,.CO,.NO,.MA,.CT,.PO,.CA,.PC,.IV,.DS,.PRO,.IMGIMG");
        $elements.empty().hide();
        $('.select2-search-choice').remove();
        $('#octenimg').remove();
        edit_img = '';
        $('#btnSave').text('Guardar');
        // $( "#editfile" ).removeClass('fileinput fileinput-exists').addClass('fileinput fileinput-new');
        if (save_method === "add" || save_method === "update") {
            save_method = '';
            $('[name="multi"]').val('').trigger('change');
        } else {
            $('#from_Producto')[0].reset();
            $('[name="multi"]').val('').trigger('change');
            save_method = 'add';
        }
        }
      function resetear() {
         $('#tituloboton').text(' Agregar Nuevo Producto'); // Fijar título para arrancar título 
          save_method = ''; 
          $('.select2-search-choice,#octenimg').remove();  
    }

    function _edit(id)
    {
        $(".ME,.CD,.SM,.DE,.CO,.NO,.MA,.CT,.PO,.CA,.PC,.IV,.DS,.PRO,.IMGIMG").html("").css({"display":"none"});
          $('.select2-search-choice,#octenimg').remove();
          $('[name="multi"]').val('').trigger('change');
          edit_img = '';
          save_method = 'update'; // al darle Editar usuario la variable contendra un valor update
          $('#from_Producto')[0].reset(); // restablecer el formulario del modal por cualquien eventualidad
          $('#clic').text('Actualizar');

      //los datos de carga de Ajax Ajax
          $.ajax({
            url : "Producto/ajax_edit/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                // mostraremos los datos necesario  octenidos por ajax
                $('[name ="idProducto"]').val(data.idProducto);
                $('[name ="Codigo"]').val(data.Codigo);
                $('[name ="Nombre"]').val(data.Nombre);
                $('#Marca').val(data.idMarca).change();
                $('[name ="Precio_Costo"]').val(data.Precio_Costo);
                $('[name ="Precio_Venta"]').val(data.Precio_Venta);
                $('[name ="Porcentaje"]').val(data.Produccion).change();
                $('[name ="Cantidad_A"]').val(data.Cantidad_A);
                $('[name ="Cantidad_D"]').val(data.Cantidad_D);
                $('[name ="Stock_Min"]').val(data.Stock_Min);
                $('[name ="Unidad"]').val(data.Unidad);
                $('#Medida').val(data.Medida).change();
                $('#Categoria').val(data.idCategoria).change();
                $('[name ="Descripcion"]').val(data.Descripcion);
                $('[name ="iva"]').val(data.Iva).change();;
                $('[name ="Descuento"]').val(data.Descuento).change();
                if (data.Img != '') {
                    $('#agregar').prepend($('<img>',{id:'octenimg'}))
                    $("#octenimg").attr('src', "/bower_components/uploads'/"+data.Img);
                    $( "#editfile" ).removeClass('fileinput fileinput-new').addClass('fileinput fileinput-exists');
                    edit_img = data.Img;  
                }
                $.post("Producto/proveedor_has/"+id, function(res) {
                  var conten = [];
                  var conten = new Array();
                        var json = JSON.parse(res);
                       $.each(json, function(index, val) {
                          conten.push(val.id2);
                    });
                    $("#multi").val(conten).trigger("change");

                });
               $('#someTab').tab('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Swal.fire('Error al obtener datos');
            }
        });
    }
    function reload_table()
    {
      tabla_Producto.ajax.reload(null,false); //reload datatable ajax 
    }
    $('.remove').click(function(event) {
      edit_img = '';
    });
    $(function() {
      $('#from_Producto').submit(function(e) {
        var url;
        var fileToUpload = inputFile[0].files[0];
        console.log(fileToUpload);
        if(save_method == 'add') 
        {
          url = "Producto/ajax_add";
        }
        else
        {

          url = "Producto/ajax_update";
        }
         if (fileToUpload != 'undefined') {
            var formData =  new FormData();
            if (save_method == 'update' && edit_img !== '') {
              formData.append("imageneditado",edit_img);
            }
            if (save_method == 'update' && edit_img == '') {
              formData.append("file",fileToUpload);
            }
            if (save_method == 'add') {
              formData.append("file",fileToUpload);
            }
            formData.append("idProducto",$('#idProducto').val());
            formData.append("Codigo",$('#Codigo').val());
            formData.append("Nombre",$('#Nombre').val());
            formData.append("Marca",$('#Marca').val());
            formData.append("Categoria",$('#Categoria').val());
            formData.append("Cantidad_A",$('#Cantidad_A').val());
            formData.append("Cantidad_D",$('#Cantidad_D').val());
            formData.append("Stock_Min",$('#Stock_Min').val());
            formData.append("Precio_Costo",$('#Precio_Costo').val());
            formData.append("Porcentaje",$('#Porcentaje').val());
            formData.append("Precio_Venta",$('#Precio_Venta').val());
            formData.append("iva",$('#iva').val());
            formData.append("Medida",$('#Medida').val());
             formData.append("Unidad",$('#Unidad').val());
            formData.append("Descuento",$('#Descuento').val());
            formData.append("multi",$('#multi').val());
                  // manipulaciones por ajax
                  $.ajax({
                    url: url ,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                      $('#clic').attr("disabled","disabled");
                      $('#from_Producto').css("opacity",".4");
                      $('#bguarda').toggleClass('fa fa-spinner fa-pulse fa-1x fa-fw');
                    },
                  })
                  .done(function(data) {
                          var json = JSON.parse(data);// parseo la dada devuelta por json
                          if (json.res == "error"){
                            $('#bguarda').toggleClass('fa fa-spinner fa-pulse fa-1x fa-fw');
                            $('#from_Producto').css("opacity","");
                            $('#clic').removeAttr('disabled');
                              if (json.Codigo) {
                               $("#alert-code").attr('data-original-title', json.Codigo).tooltip('show');
                              }
                               if (json.Nombre) {
                                $("#alert-code1").attr('data-original-title', json.Nombre).tooltip('show');
                              }
                              if (json.Marca) {
                                  $("#alert-code2").attr('data-original-title', json.Marca).tooltip('show');
                              }
                              if (json.Categoria) {
                                  $("#alert-code3").attr('data-original-title', json.Categoria).tooltip('show');
                              }
                              if (json.Cantidad_A) {
                                  $("#alert-code4").attr('data-original-title', json.Cantidad_A).tooltip('show');
                              }
                              if (json.Precio_Costo) {
                                  $("#alert-code5").attr('data-original-title', json.Precio_Costo).tooltip('show');
                              }
                               if (json.Produccion) {
                                  $("#alert-code6").attr('data-original-title', json.Produccion).tooltip('show');
                              }
                              if (json.Precio_Venta) {
                                  $("#alert-code7").attr('data-original-title', json.Precio_Venta).tooltip('show');
                              }
                              if (json.iva) {
                                  $("#alert-code8").attr('data-original-title', json.iva).tooltip('show');
                              }
                              if (json.Cantidad_D) {
                                  $("#alert-code9").attr('data-original-title', json.Cantidad_D).tooltip('show');
                              }
                             if (json.Stock_Min) {
                                  $("#alert-code10").attr('data-original-title', json.Stock_Min).tooltip('show');
                              }
                              if (json.Descuento) {
                                  $("#alert-code11").attr('data-original-title', json.Descuento).tooltip('show');
                              }
                              if (json.Proveedor) {
                                  $("#alert-code12").attr('data-original-title', json.Proveedor).tooltip('show');
                              }
                              if (json.img) {
                                  $("#editfile").attr('data-original-title', json.img).tooltip('show');
                              }
                              if (json.Unidad) {
                                  $("#alert-code14").attr('data-original-title', json.Unidad).tooltip('show');
                              }
                              setTimeout(function(ar) {
                                $('[data-toggle="tooltip"]').removeAttr('data-original-title').tooltip('hide',{duration:3000});

                              },3000);

                          }
                          else
                          {
                                      if (save_method == 'add') {
                                          $data = 'Datos Registrado correctamente';
                                      } else{
                                          $data = 'Datos Actualizado correctamente';
                                          save_method = '';
                                      }
                                      alertasave($data);

                                      limpieza();
                                     $('#from_Producto').css("opacity","");
                                     $('#bguarda').toggleClass('fa fa-spinner fa-pulse fa-1x fa-fw');

                          }
                  })
                  .fail(function() {
                    console.log("error");
                  })
                  .always(function() {
                    console.log("complete");
                  });
        e.preventDefault();
      }
      })
    });
  function limpieza(arguments) {
    $('#from_Producto')[0].reset();
    edit_img    = '';
    $('#btnSave').text('Guardar');
    $('#clic').removeAttr("disabled");
    reload_table(); // recargar la tabla automaticamente
  }
function _delete(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás recuperar el producto!",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    console.log(result);
    if (result.value) {
      // ajax delete datos de database
      $.ajax({
        url: "Producto/ajax_delete/" + id,
        type: "POST",
        dataType: "JSON"
      })
      .done(() => {  
        reload_table(); 
      })
      .fail(() => {
        Swal.fire('Error al intentar borrar');
      });
      Swal.fire("¡Eliminado!", "El producto ha sido borrado.", "success");
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelado", "Sin acción :)", "error");
    }
  });
}

    function Proveedor_has(id) {
      var id_prove = id;
      var n = 1;
       $("#"+id_prove).html("").css({"display":"none"});
       $.ajax({
        url: "proveedor_has/"+id,
        type: "POST",
        dataType: "JSON",
        cache: false,
      })
      .done(function(data) {
            $.each(data, function(i, val){
              i += n;
              var limite_text = val.Razon;
              if (limite_text.length > 10) {
                limite = limite_text.substr(0, 10)+" ...";
                $('#'+val.id).append(''+i+'<strong class="text-danger">&nbsp;'+ limite + '</strong><br>').css({"display":"block"});
              } else {
                $('#'+val.id).append(''+i+'<strong class="text-danger">&nbsp;'+ limite_text + '</strong><br>').css({"display":"block"});
              }
          });
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }

        function status(id,data)
        {
               $.ajax({
                  url : "updateStatud",
                  type: "POST",
                  dataType: "JSON",
                  data: {
                    val:  data,
                    id:  id,

                  },
                })
              .done(function(data) {
                if (data == 0) {
                  Swal.fire("Cancelled", "Sin accion:)", "error");
                  finish();
                }
                 reload_table();

              })
              .fail(function() {
               Swal.fire("Cancelled", "Sin accion:)", "error");
              });
        }


function verificar() {
  // Obtiene el valor del campo "Unidad"
  var valorUnidad = $("#Unidad").val();

  // Si el campo no está vacío, habilita el campo "Medida".
  // De lo contrario, lo deshabilita.
  valorUnidad !== '' ? $("#Medida").removeAttr("disabled") : $("#Medida").attr("disabled", "disabled");
}

function formatCurrency(input) {
    // Eliminar todos los caracteres que no sean dígitos o punto decimal
    const cleanedValue = input.value.replace(/[^\d.]/g, '');
    
    // Dividir el valor en parte entera y parte decimal (si está presente)
    const [integerPart, decimalPart] = cleanedValue.split('.');
    
    // Formatear la parte entera con separadores de miles
    const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    
    // Volver a unir la parte entera y decimal (si está presente) y agregar el símbolo de moneda
    const formattedValue = decimalPart ? `${formattedIntegerPart}.${decimalPart}` : formattedIntegerPart;
    
    // Actualizar el valor del campo de entrada
    input.value = `${formattedValue}`;
  }

  function limitDigits(event, input, maxLength) {
    if (input.value.replace(/[^\d]/g, '').length >= maxLength) {
      if (event.key === 'Backspace' || event.key === 'Delete') {
        return; // Permitir la eliminación de caracteres
      }
      event.preventDefault(); // Evitar la entrada de más caracteres
    }
  }