<script type="text/javascript">
  const cache = {};
  const $document = $(document);
  const $minimumInputLength = 5;
  const $modalSearch = $('#modalSearch');
  const $modalCobro = $('#modal-id');
  const $campoSearch = $('#campoSearch');
  const $campoBusqueda = $('#campoBusqueda');
  const $imagenContenedor = $('.img-thumbnail');
  const $tablaProductos = $('#tablaProductos');
  const $rutaDeLaImagen  = "bower_components/uploads/";
  const $srcImagenNoDisponible = `${$rutaDeLaImagen }imgnull.jpg`;
  const swalConfig = {
    title: "<span id='mensajeAgradecimiento' >¡Gracias por su compra!</span>",
    icon: "info",
    showCloseButton: true,
    showCancelButton: false,
    focusConfirm: false,
    confirmButtonText: "<i class='fa fa-thumbs-up'></i> Cerrar",
  };
  const KEY_SHIFT = 'Shift';
  let codigoLeido = '' ;


// # Temporizadores
let bandera = true;
let banderas = true;
let $tbody = $('#divisas tbody');
let typingTimer = null;
let timeout = null;
let motopagar = 0;
let final = 0;
let pagoEfectivo = 0;
let pagoCeque = 0;
let pagoTarjeta = 0;
let saldoFavor = 0;
let Cliente;
let table;
let save_method;
let tecla;
let formEnviado = false;



class ProductSearchModal {
    constructor() {
          this.modal = $modalSearch;
          this.table = $modalSearch.find('.table tbody');
          this.focusedRow = null; //$('#tablaProductos tr.trproduc.focused');
          this.typingTimer = null;
          this.keyPrincipal = {
                    112: () => { // F1
                        this.toggleSelect2('[name="Cliente"]');
                    },
                    114: () => { // F3
                        this.toggleSelect2('[name="orden"]');
                    },
                    115: () => { // F4
                        this.toggleSelect2('[name="tipoComprovante"]');
                    },
                    119: () => { // F8
                        this.toggleSelect2('[name="Estado"]');
                    }
                };
          this.keyModalCobro = {
            'F1': '#e a',
            'F2': '#c a',
            'F3': '#t a',
            'F4': '#s a'

          };
          $document.on('keydown', (e) => {
                tecla = e.which;
                if (e.key === KEY_SHIFT) {
                    this.handleShiftKey();
                }
                this.handleDocumentKeydown(e);
          });

          $campoSearch.on('keydown', (e) => {
            this.handleSearchInputKeydown(e);
          });

          $campoSearch.on('keyup', (e) => {
            this.handleSearchInputKeyup(e);
          });

          $document.on('click', 'button.search-product', (e) => {
            this.openModal();
          });

          $tablaProductos.on('mouseenter', 'tr.trproduc', (e) => {
            this.handleMouseEnter(e);
          });

          $modalSearch.on('show.bs.modal', (e) => {
            $campoSearch.focus();
          });

          $tablaProductos.on('click', 'i.fa-edit', (e) => {
            const productId = $(e.target).data('id');
            this.handleclick(e);
            this.editProduct(productId);
          });

          $tablaProductos.on('click', 'i.fa-shopping-cart', (e) => {
            this.handleclick(e);
            this.handleModalKeydown(e);
          });


    }

    handleShiftKey() {
        $campoBusqueda.val('').focus();
    }


    toggleSelect2(fieldName) {

      const select2 = $(fieldName).data('select2');
      if (select2.isOpen()) {
          select2.close();
          this.handleShiftKey();
      } else {
          select2.open();
      }


    } 

    handleDocumentKeydown(e) {
            const modalSearchVisible = $modalSearch.is(':visible');
            const modalCobroVisible = $modalCobro.is(':visible');
            const key = e.key.toUpperCase();
            console.log(key);

            if (modalSearchVisible) {
                this.handleModalKeydown(e);
                return;
            }

            if (modalCobroVisible) {
                if (this.keyModalCobro[key]) {
                $(this.keyModalCobro[key]).click();
                e.preventDefault();
                return;
                }

                const checkbox = document.getElementById('customSwitch1');
                if (e.key === 'F8') {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
                e.preventDefault();
                return;
                }
            }else {
                // if (!isNaN(e.key) && codigoLeido.length < 13) {
                //     codigoLeido += e.key; // Concatena el dígito a la variable
                // }
                // if (e.key === 'Enter' && $campoBusqueda.is(':focus') === false) {
                //   $campoBusqueda.val(codigoLeido).focus();
                //   realizarConsulta();
                //   codigoLeido='';
                   
                // }

                if (e.key === 'Enter'  && $('#vender :focus').length === 0)  {
                    this.handleShiftKey() ;
                }

            }





            if (this.keyPrincipal[e.which]) {
                this.keyPrincipal[e.which]();
                e.preventDefault();
                return;
            }
        

            if (Swal.isVisible() && e.key === 'Enter') {
                Swal.close();
                this.handleShiftKey() ;
                e.preventDefault();
            }

            
   
            
            
           
            
    }
    
  openModal() {
    $('.todo-list').html('');
    this.modal.modal("show");
    $campoSearch.val('');
    setTimeout(() => {
      $campoSearch.focus();
    }, 600);
  }

  handleSearchInputKeydown(e) {
    if (e.which === 9) {
      e.preventDefault();
      this.blurSearchInput();
      this.focusFirstRow();
    }
  }


  handleModalKeydown(e) {
    if (e.keyCode === 27) {
        this.modal.modal("hide");
        this.handleShiftKey();
    }else if (e.keyCode === 40 ) {
      e.preventDefault();
      this.blurRow();
      this.focusNextRow();
    } else if (e.keyCode === 38) {
      e.preventDefault();
      this.blurRow();
      this.focusPrevRow();
    } else if (e.which === 13 || $(e.target).hasClass('fa fa-shopping-cart')) {
        this.Additem();
        e.preventDefault();

    } else if (e.which === 113) {
      $campoSearch.focus();
        e.preventDefault();

    } 
  }

  Additem(e) {
  var selectedRow = this.focusedRow;

  if (selectedRow && selectedRow.length) {
    var hiddenInput = selectedRow.find('input[type="hidden"]');
    let id = hiddenInput.data('id');
    let precio = hiddenInput.data('precio');
    let name = hiddenInput.data('name');
    let iva = hiddenInput.data('iva');
    let descuento = hiddenInput.data('descuento');
    let stock = hiddenInput.data('stock');
    let code = hiddenInput.data('code');
    let medida = hiddenInput.data('medida');

    toastem.cerrar(name + ' Agregado');
    $.ajax({
      url: 'Venta/Additem',
      method: 'POST',
      data: {
        id: id,
        iva: iva,
        precio: precio,
        nombre: name,
        descuento: descuento,
        stock: stock,
        code: code,
        medida: medida
      },
      success: function(response) {
        $('#detalle').html(response);
      },
      error: function(xhr, status, error) {
        console.log(error);
      }
    });
  } else {
    console.error("La fila seleccionada es nula o no tiene longitud.");
  }
}


  handleclick(e) {
    e.preventDefault();
      const closestTr = $(e.target).closest('tr');
      if (this.focusedRow && this.focusedRow.is(closestTr)) {
        return;
      }
      this.blurRow();
      this.focusedRow = closestTr;
      this.focusedRow.addClass('focused');
      this.handleMouseEnter(this.focusedRow.attr('id'));
  }

  handleSearchInputKeyup(e) {
    clearTimeout(this.typingTimer);
    const searchTerm = $campoSearch.val().trim();
    if (searchTerm.length >= $minimumInputLength) {
      this.typingTimer = setTimeout(() => {
        this.buscarProductos(searchTerm);
      }, this.doneTypingInterval);
    }
  }

  handleMouseEnter(event) {
    let imagen;
    if (typeof event === 'object') {
      imagen = event.currentTarget?.id;
    } else {
      imagen = event;
    }
    const src = imagen && $rutaDeLaImagen  ? $rutaDeLaImagen  + imagen : $srcImagenNoDisponible;
    const $contenedor = $imagenContenedor;
    if ($contenedor) {
      $contenedor.fadeOut(300, function() {
        $contenedor.attr('src', src).fadeIn(300);
      });
    }
  }

  blurSearchInput() {
    $campoSearch.blur();
  }

  focusFirstRow() { 
    const focusedR = this.table.find('tr.focused'); 
    if (focusedR.length > 0) { 
      this.focusedRow = focusedR; 
    } else { 
      this.focusedRow = this.table.find('tr:first-child'); 
    } 
    this.focusedRow.addClass('focused').focus(); 
    this.handleMouseEnter(this.focusedRow.attr('id')); 
  } 

  focusPrevRow() {
    const prevRow = this.focusedRow.prev('tr');
    if (prevRow.length) {
      this.focusedRow.removeClass('focused');
      this.focusedRow = prevRow;
      this.focusedRow.addClass('focused');
      this.focusedRow.focus();
      this.handleMouseEnter(this.focusedRow.attr('id'));
    }
  }

  blurRow() {
    if (this.focusedRow) {
      this.focusedRow.removeClass('focused');
    }
  }

  focusNextRow() {
    const nextRow = this.focusedRow.next('tr');
    if (nextRow.length) {
      this.focusedRow = nextRow;
      this.focusedRow.addClass('focused');
      this.focusedRow.focus();
      this.handleMouseEnter(this.focusedRow.attr('id'));
    }
  }

  buscarProductos(searchTerm) {
    $('#loadingContainer').show();
    $.ajax({
      url: "Venta/campoSearch",
      type: 'POST',
      dataType: 'json',
      data: { searchTerm: searchTerm },
    })
    .done((data) => {
      var html = '';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach(function(producto) {
        html += `
          <tr id="${producto.Img}" data-prod="${producto.id}" class="trproduc text-center">
            <td>
            
            <input type="hidden" 
            data-code="${producto.CodigoBarra}" 
            data-stock="${producto.stock}" 
            data-descuento="${producto.Descuento}" 
            data-iva="${producto.Iva}" 
            data-name="${producto.full_name}" 
            data-id="${producto.id}" 
            data-precio="${producto.precio}"
            data-Medida="${producto.Medida}"

            ></td>
            <td>${producto.stock}</td>
            <td><small class="price">${formatPrice(producto.precio)}</small></td>
            <td><small class="mayor">${formatPrice(producto.mayor)}</small></td>

            <td><span class="text">${producto.full_name}</span></td>
            <td><span class="code">${producto.CodigoBarra}</span> </td>
            <td class="todolist">
            <li>
                  <div class="tools">
                  <i class="fa fa-edit" data-id="${producto.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"> Edit</i>
                  <i class="fa fa-shopping-cart" data-id="${producto.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar al carrito"> Add</i>
                  </div>
                  
                </li>
            </td>
          </tr>
        `;
      });
      } else {
        html = `
          <tr class="message-row">
            <td class="message" colspan="7">Producto no encontrado.</td>
          </tr>
        `;
      }
      $('.todo-list').fadeOut(300, function() {
        $(this).html(html).fadeIn(300);
        $('#loadingContainer').hide();
      });
    })
    .fail(() => {
      $('#loadingContainer').hide();
      Swal.fire('Disculpe, existió un problema');
    });
  }
  doneTypingInterval = 500;

  editProduct(productId) {
    const $rowToEdit = $('[data-prod="'+productId+'"]');
    const $nameCell = $rowToEdit.find('.text');
    const $priceCell = $rowToEdit.find('.price');
    const $codeCell = $rowToEdit.find('.code');
    const $mayorCell = $rowToEdit.find('.mayor');

    const name = $nameCell.text();
    const price = $priceCell.text();
    const mayor = $mayorCell.text();
    const code = $codeCell.text();

    $nameCell.html(`<input type="text" name="nameInput" class="form-control input-smch" value="${name}">`);
    $priceCell.html(`<input type="text" name="priceInput" class="form-control input-smch validat" value="${price}">`);
    $mayorCell.html(`<input type="text" name="mayorInput" class="form-control input-smch validat" value="${mayor}">`);
    $codeCell.html(`<input type="text" name="codeInput" class="form-control input-smch" value="${code}">`);

    const $saveButton = $rowToEdit.find('i.fa-edit');
    $saveButton.removeClass('fa-edit').addClass('fa-save').text(' Ok');
    $saveButton.on('click', () => {
      this.saveProductChanges(productId);
    });
  }

  saveProductChanges(productId) {
    const $rowToSave = $(`[data-prod="${productId}"]`);
    const $nameInput = $rowToSave.find('[name="nameInput"]');
    const $priceInput = $rowToSave.find('[name="priceInput"]');
    const $mayorInput = $rowToSave.find('[name="mayorInput"]');
    const $codeInput = $rowToSave.find('[name="codeInput"]');

    
    const newName = $nameInput.val();
    const newPrice = $priceInput.val().replace(/[^\d\s]+/g, "");
    const newMayor = $mayorInput.val().replace(/[^\d\s]+/g, "");
    const newCode = $codeInput.val();

    // Validar los campos antes de guardar los cambios
    if (newName.trim() === '') {
      Swal.fire('Por favor, ingresa un nombre válido');
      return;
    }
    if (isNaN(newPrice)) {
      Swal.fire('Por favor, ingresa un precio válido');
      return;
    }
    if (isNaN(newMayor)) {
      Swal.fire('Por favor, ingresa un precio mayor válido');
      return;
    }
    if (newCode.trim() === '') {
      Swal.fire('Por favor, ingresa un código válido');
      return;
    }
    
    // Aquí puedes realizar la lógica para guardar o actualizar los cambios en el servidor
    const $data = { id: productId,nombre: newName, precio: newPrice,mayor: newMayor,codigo:newCode };

      $.ajax({
        url: "Producto/guardarCambios",
        type: 'POST',
        data: $data,
        dataType: 'json'
      })
      .then(response => {
        if (response.ok) {
          Swal.fire({
            title: 'Actualizado',
            text: 'Datos  Actualizados correctamente',
            timer: 1000,
            showConfirmButton: false
          });
        } else {
          toastem.error('Error al guardar los cambios:');
        }
      })
      .then(data => {
        // Utilizar la respuesta para actualizar los valores en la tabla
        $rowToSave.find('.text').html($data['nombre']);
        $rowToSave.find('.price').html(formatPrice( $data['precio']));
        $rowToSave.find('.mayor').html(formatPrice($data['mayor']) );
        $rowToSave.find('.code').html($data['codigo']);
        const $saveButton = $rowToSave.find('i.fa-save');
        $saveButton.removeClass('fa-save').addClass('fa-edit').text(' Edit');
        $saveButton.off('click');

      })
      .fail((jqXHR, textStatus, errorThrown) => {
        toastem.error('Error al guardar los cambios: ' + errorThrown);
      });


  }
}



$(document).ready(function () {

  const productSearchModal = new ProductSearchModal();


        $('#seat2').click(function() {
            try {
                // Obtener el valor del campo de búsqueda
                let codigoDeBarras = $('#campoBusqueda').val();

                if (!codigoDeBarras) {
                    codigoDeBarras = 0
                }
                    // Codificar el valor del código de barras para asegurar caracteres especiales
                    let codigoDeBarrasCodificado = encodeURIComponent(codigoDeBarras);

                    // Abrir una nueva pestaña del navegador con la URL "Producto"
                    window.open(`Producto?codeBarra=${codigoDeBarrasCodificado}`, '_blank');

            } catch (error) {
                console.error('Error al abrir la nueva pestaña:', error);
            }
        });


     // Después de que la página principal se ha cargado
    $("select:not(#cuotas,#ClienteSearch,.orden)").select2({
        allowClear: true,
        placeholder: 'Busca y Selecciona',
        width: null,
        theme: "bootstrap"
    });
        $( "#V_T_A, #Venta, #VTA" ).addClass( "active text-red" );
        $( "#accion" ).addClass( "fa fa-plus-square" );
        $('#atajos').show();
        $(".Cliente").val('').trigger("change");
        // $('.productos').focus();
        cargarContenido('Venta/loader/1', 'detalle');
        $('#c a').attr('data-toggle', 'disabled');
        $('#Venta > a').click();
         $campoBusqueda.focus();

        function esEntradaLector(event) {
             return event.originalEvent && event.originalEvent.isTrusted === false;
        }



// Función para realizar la consulta
function realizarConsulta() {

           // Obtener el valor del código de barras
            let codigoDeBarras = $campoBusqueda.val();
            let cantidad = 1;
            let codigoProducto = codigoDeBarras;
            // Verificar si hay un asterisco (*) en el código de barras
            const asteriscoIndex = codigoDeBarras.indexOf('*');
            if (asteriscoIndex !== -1) {
                // Hay un asterisco (*), procesar la cantidad y código de barras
                cantidad = parseInt(codigoDeBarras.substring(0, asteriscoIndex), 10);
                codigoProducto = codigoDeBarras.substring(asteriscoIndex + 1);
            }
                const primerCaracter = codigoDeBarras.charAt(0);
            let pesoProducto, ultimoDigito;
            let code = codigoProducto;

            if (codigoProducto.length >= 12) { // Validar que el código de barras tenga 13 dígitos
                if (primerCaracter === "2" && codigoProducto.length === 13) {
                    const match = codigoDeBarras.match(/^(\d)?(\d{6})(\d{5})(\d+)$/);
                    if (match) {
                        let ultimo ; // “5” podría ser kilogramos (kg). “6” podría ser gramos (g). “7” podría ser libras (lb).
                        codigoProducto = match[2]; // Peso del producto
                        pesoProducto = match[3]; // Último carácter
                        ultimo = match[4]; // Último carácter
                        code = codigoProducto + pesoProducto;
                    } else {
                        toastem.error("El formato del código de barras de producto de pesa no es válido.");
                    }
                }
                $.ajax({
                url: "Venta/select2remote",
                method: 'POST',
                delay: 500,
                data: {
                q: codigoProducto,
                peso: pesoProducto,
                code: code,
                cantidad: cantidad 
                },
                dataType: 'json',
                success: function(data, params) {
                params.page = params.page || 1;
                if (data.items && data.items.length > 0) {
                    cargarContenido('Venta/loader', 'detalle');
                    $campoBusqueda.val('');
                } else {
                    toastem.error('Sin resultado');
                }
                },
                error: function(xhr, status, error) {
                toastem.error('Error en la solicitud '+error);
                }
            });
        }
}


// Asignar evento 'keydown' al campo de búsqueda con debouncing
// $campoBusqueda.on('keydown', function(event) {
//     // Limpiar el temporizador existente
//     clearTimeout(timeout);
//     // Verificar si la tecla presionada es "Enter"
//     if (event.key === 'Enter') {
//             // Prevenir la acción predeterminada del "Enter" para evitar envío del formulario, etc.
//             // event.preventDefault();

//         // Establecer un nuevo temporizador para la consulta después de un breve período de tiempo (300 ms)
//         timeout = setTimeout(realizarConsulta, 100);
//     }
// })
function realizarAccionEspecifica() {
    // Obtener referencia al botón por su ID
    const botonGuardar = document.getElementById('add01');
    // Verificar si el botón está habilitado antes de simular un clic
    if (!botonGuardar.disabled) {
        // Simular un clic en el botón
        botonGuardar.click();
    } 
}

// Asignar evento 'keydown' al campo de búsqueda
$campoBusqueda.on('keydown', function(event) {
    // Limpiar el temporizador existente
    clearTimeout(timeout);

    // Verificar si la tecla presionada es "Enter"
    if (event.key === 'Enter') {
        // Prevenir la acción predeterminada del "Enter" para evitar envío del formulario
        event.preventDefault();

        // Obtener el valor actual del campo de búsqueda
        let valorActual = $campoBusqueda.val();
        let carritoCargado = $('#finalcarrito').val();

        // Verificar si el carrito está cargado y el campo lector está vacío
        if (carritoCargado > 0 && valorActual.trim() === '') {
            realizarAccionEspecifica();
        } else {
            // Verificar si tiene al menos 12 caracteres
            if (valorActual.length >= 12) {
                // Establecer un nuevo temporizador para la consulta después de un breve período de tiempo (500 ms)
                // timeout = setTimeout(realizarConsulta, 500);
                realizarConsulta();
            } 
        }
    }
})
.on('input', function(event) {
      if (tecla === 32) { // 32 es el código ASCII del espacio
        productSearchModal.openModal();
        return;
      }
});

    
    $("#ClienteSearch").select2({
        ajax: {
            url: 'Cliente/select2remote',
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
            var $progressNumber = $('.progress-number');
            var $progress = $('#progress');

            var results = $.map(data.items, function (item) {
              let  progreso = (item.lmc / item.tc) * 100;
                // Asegurarse de que el progreso esté dentro del rango del 0 al 100
                progreso = Math.min(Math.max(progreso, 0), 100);
                $progressNumber.html('<b>' + item.tc + '</b>/' + item.lmc);
                $progress.css({width: progreso + '%'});
                return {
                    id: item.id,
                    text: item.full_name
                };
            });
            
            return {
                results: results,
                pagination: {
                    more: false
                }
            };
        },
              
            cache: true
        },
        allowClear: true,
        placeholder: 'Busca Cliente',
        minimumInputLength: 5,
        theme: 'bootstrap'
        }).on('change', function(event) {
          Cliente = $(this).val();
          if (Cliente > 1) {
            setTimeout(() => {
                     $campoBusqueda.val('').focus();
                                 // Habilitar la pestaña cambiando el atributo data-toggle
                    $('#c a').attr('data-toggle', 'tab');
                  }, 1000)
          }else{
              // Deshabilitar la pestaña cambiando el atributo data-toggle
              $('#c a').attr('data-toggle', 'disabled');
              ordenSelect
                .html('')
                .prop("disabled", true);
          }
          // $(this).next('.select2-container').css('width', '-webkit-fill-available');
    });
          
    $('#vender').submit(function(e) {

          motopagar = $('#finalcarrito').val();

              var Estado = $('#Estado').val();
              if (Estado == 0) {
                  formEnviado = false;
                  $('#Venta_submit')[0].reset();
                  $('#ParcialE, #ParcialC, #ParcialT').empty();
                  $('.hidden').val('');
                  $('#tabEfectivo').tab('show');
                  $('#numcheque').prop('required', false);
                  $("#Venta_submit,#modal-header").show();
                  $('#modal-id').on('hidden.bs.modal', function () {
                        $(this).find('input[type="hidden"]').val('');
                    }).modal('show');

                  totalparcial();
                  reloadcheque();
                  $('#deudapagar').val(motopagar);
                  $('#m_total, #spanmontopagar, #spanmontopagarchque, #spanmontopagartar, #spanmontopagarfa').text(formatNum(motopagar));
                  $('.controlajustar').hide();
                  save_method = 'add_add';
 
                  setTimeout(() => {
                      $("#EF1").focus();
                  }, 1000);
              } else {

                  $('#bguarda').removeClass('fa-save').addClass('fa-spinner fa-spin');
                  var $serialize = $(this).serialize();
                  add($serialize);
              }


        e.preventDefault();
    });

    $( '#checControl' ).on('change', function() {
      let direcEnvio = $('#direcEnvio');
      let fletes = $('#fletes');
      let direccion = $('#Direccion');

      if ($(this).is(':checked')) {
        $(this).prop('value', '1');
        direcEnvio.removeClass('hidden');
        fletes.prop('disabled', false);
        direccion.prop('disabled', false).prop('required', true);

      } else {
        $(this).prop('value', '0');
        direcEnvio.addClass('hidden');
        fletes.prop('disabled', true);
        direccion.prop('disabled', true).prop('required', false).val('');

      }
    });

    $('#condicion,#Estado').change(function(event) {
      var estado = $('#Estado').val();
      if (estado == '2') {
        $('#contenCuotas,#cuotas').val('1').show();
        $('#Estado').val('2');
      } else {
        $('#contenCuotas,#cuotas').val('1').hide();
        $('#condicion').show();
        $('#Estado').val('0');
      }
      $("#ClienteSearch").prop("required", estado == '2');
    });

    $('[name="formapago"]').change(function(event) {
      if ($(this).val() == 2 || $(this).val() == 3) {
      $('[name="Cliente"]').attr('required','required');
      }else{
      $('[name="Cliente"]').removeAttr('required');
      }
    });

$('#tipoComprovante').change((event) => {
    const $this = $(event.currentTarget);
    const clienteInput = $('[name="Cliente"]');
    const comprobante = $('#comprobante');
    const Ticket = $('#Ticket');
    
    const tipoComprovanteVal = $this.val();
    const estadoVal = $('#Estado').val();

    if (tipoComprovanteVal && (tipoComprovanteVal === '1' || estadoVal === '2')) {
        clienteInput.prop('required', true);
        comprobante.removeClass('hidden');
        Ticket.addClass('hidden');

        const openSelect2 = () => {
            if (typeof Cliente === 'undefined' || Cliente <= 1) {
                setTimeout(() => {
                    clienteInput.select2("open");
                }, 500);
            } else {
                setTimeout(() => {
                    $("#add01").focus();
                }, 100);
            }
        };
        openSelect2();
    } else {
        clienteInput.prop('required', false);
        comprobante.addClass('hidden');
        Ticket.removeClass('hidden');

        setTimeout(() => {
            $("#add01").focus();
        }, 100);
    }
});

    var ordenSelect = $("#single-prepend-text_3");
    ordenSelect.select2({
      minimumResultsForSearch: Infinity, // Deshabilitar la 
      dropdownAutoWidth: false,
      allowClear: true,
      placeholder: 'Busca y Selecciona',
      width: null,
      disabled: true,
      theme: "bootstrap"
    }).change(function(event) {
      var id = $(this).val();
      var nombre;
        if (id == '') {
            var nombre = localStorage.getItem("id");
            if (nombre == '') {
            toastem.success("no hay datos todavia");
            }else{
            cargarContenido('Venta/loader/1', 'detalle');
            }
        }else{
            localStorage.setItem("id", id);
            if (id !==  undefined && id !== null) {
                $.ajax({
                    url : "Venta/agregar_item/"+id ,
                    type: "POST",
                    dataType: "JSON",
                })
                .done(function(data) {
                if (data !== false && data !== null) {
                cargarContenido('Venta/loader', 'detalle');

                toastem.abrir(data+ ' '+"Articulo agregador");
                }else{
                toastem.cerrar('Sin resultado');
                }
                })
                .fail(function() {
                console.log("error");
                })
                .always(function() {
                console.log("complete");
                });

            }
        }


    });

    var select2Container = $("#single-prepend-text_3").next(".select2-container");
    select2Container.on("click", function (e) {
        if (Cliente) {
          ordenSelect.siblings(".select2-container").find(".select2-selection__placeholder").text('Cargando Espere...');
            $.ajax({
              url: "Venta/list_orden/" + Cliente,
              success: function (data) {
                ordenSelect
                .html(data)
                .prop("disabled", false)
                .select2("open");
              },
              error: function () {
                ajaxInProgress = false;
                // Manejar el error de la llamada AJAX
              },
              complete: function () {
                ajaxInProgress = false;
              },
              timeout: 5000, // Establecer un tiempo de espera máximo de 5 segundos
            });
        }else{
          ordenSelect
                .html('')
                .prop("disabled", true);
          toastem.error('Selecciona algun Cliente');
        }
    });

    $('#inserc').submit(async (e) => {
      e.preventDefault();

      const $alertas = $('#alertas');
      const $loading = $('#loading');
      const nom = $('#nombre').val();
      const telefon = $('#Telefono').val();
      const ruc = $('#ruc').val();
      const credito = $('#limite_credito').val().replace(/[^\d\s]+/g, "");

      try {
        const response = await $.ajax({
          url: "insercliente",
          type: "POST",
          data: { nom, telefon, ruc,credito }
        });

        if (response.res == 'error') {
          $loading.button("reset");
          Object.entries(response).forEach(([key, value]) => {
            $(`input[name="${key}"]`)
              .closest('.form-group')
              .find('span')
              .html(value);
          });
        } else {
          const $clienteSelect = $('[name="Cliente"]');
          $clienteSelect.append($('<option>', {
            value: response.id,
            text: `${nom}  (${ruc})`
          }));
          $loading.button("reset");
          $('#modal-1').modal('hide');
          alertasave('Datos Registrado correctamente');
          setTimeout(() => {
            $clienteSelect.val(response.id).trigger("change");

          }, 600);
        }
      } catch (error) {
        toastem.error('error');
      }
    });


    const mostrarMensajesValidacion = (data) => {
      const elementos = {
        EF1: '.EF1',
        EF2: '.EF2',
        EF3: '.EF3',
        EF4: '.EF4',
        EF5: '.EF5',
        EF6: '.EF6',
        Totalparclal: '.Totalparclal',
        numcheque: '.numcheque',
        Cliente: '.Cliente',
        fecha_pago: '.fecha_pago',
        efectivotxt: '.efectivotxt',
        efectivoTarjeta: '.efectivoTarjeta',
        Tarjeta: '.Tarjeta',
        multi: '.multi'
      };

      Object.keys(data).forEach(key => {
        if (data[key]) {
          $(elementos[key]).html(data[key]).show();
        }
      });
    }


    $('#Venta_submit').submit(function(e) {
        e.preventDefault();
        if (formEnviado) {
            return; // Evitar envío del formulario si ya ha sido enviado
        }

        var Totalparclal = parseFloat($('#Totalparclal').val());
        var deudapagar = parseFloat($('#deudapagar').val());
        var parcial2 = $("#parcial2").val();
        var vuelto = $("#vueltototal").val();

        var b = $('#loadingg');

    // Check if save_method is 'add_add' and Totalparclal is greater than or equal to deudapagar
        if (save_method === 'add_add' && Totalparclal >= deudapagar) {
            formEnviado = true;
            b.button("loadingg");
            $.ajax({
                    url: "Venta/ajax_add_pago",
                    type: 'POST',
                    dataType: 'json',
                    data: $(this).serialize() + '&' + $('#vender').serialize(),
                })
                .then(function(data) {
                    if (data.res === "error") {
                        // Aquí puedes mostrar los mensajes de validación
                        mostrarMensajesValidacion(data);
                    } else {
                        return new Promise(function(resolve, reject) {
                            setTimeout(function() {
                                $('#Venta_submit,#vender')[0].reset();
                                b.button("reset");
                                $("#Venta_submit,#modal-header").hide();

                                if ($('#tipoComprovante').val() == 1) {
                                    $("#vender input[id=TerceraSeccion]").text(data.TerceraSeccion);
                                } else {
                                    $("#vender input[id=inputTicket]").val(data.NumeroTicket);
                                }
                                Limpiar(1);
                                resolve();
                            }, 200);
                        });
                    }
                })
                .then(function() {
                    return new Promise(function(resolve, reject) {
                        setTimeout(function() {
                            $('#tipoComprovante').val('').change();
                            $('#modal-id').modal('hide');
                            $("#vender input[type=hidden], #Venta_submit input[type=hidden]")
                                .filter(function() {
                                    return $(this).attr("class") !== "hiddennone";
                                }).val(''); // Limpia solo los elementos que pasaron el filtro

                            $("#Venta_submit select").val(null).change();
                            $("#contenido,.modal-header2").show();
                            save_method = 'add';
                            resolve();
                        }, 400);
                    });
                })
                .then(function() {
                    if (parcial2 > 0) {
                        $("#numcheque").val("").trigger('input');
                    }
                    $(".mostrarMoneda").hide();
                    mostrarSwalImprimir(vuelto);

                    // Crear el swal para imprimir o no
                    

                })

                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                    toastem.error("Error al registrar los datos");
                });
        } else {
            $('.alerter').html("Monto es Inferior al Monto Total a Pagar!! ").show();
            setTimeout(function() {
                $('.alerter').hide();
            }, 8000);
        }
});


// Función para manejar la navegación entre botones con el teclado
const handleKeyboardNavigation = (e) => {
    const { key } = e;
    const focusableElements = Array.from(document.querySelectorAll('.swal2-confirm, .swal2-cancel'));

    if (key === 'ArrowUp' || key === 'ArrowDown') {
        e.preventDefault();
        const currentFocusedIndex = focusableElements.findIndex(element => element === document.activeElement);

        if (currentFocusedIndex !== -1) {
            const newIndex = key === 'ArrowUp' ? (currentFocusedIndex - 1 + focusableElements.length) % focusableElements.length : (currentFocusedIndex + 1) % focusableElements.length;
            focusableElements[newIndex].focus();
        }
    }
}
// Método para mostrar el swal para imprimir o no
const mostrarSwalImprimir = (vuelto) => {
                Swal.fire({
                    title: '¿Desea imprimir el recibo?',
                    text: 'La venta se ha guardado correctamente.',
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: 'Imprimir',
                    cancelButtonText: 'No Imprimir',
                    didRender: () => {
                        // Focus en el botón de imprimir cuando se abre el swal

                        setTimeout(() => {
                          document.querySelector('.swal2-confirm').focus();
                          document.addEventListener('keydown', handleKeyboardNavigation); // Agregar el evento de teclado al mostrar el swal
                        }, 500);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí colocas el código para imprimir el recibo si el usuario elige imprimir
                        // Puedes llamar a una función específica para imprimir el recibo
                        // imprimirRecibo();
                    }

                    // Ahora muestras el swal original
                    setTimeout(function() {
                        if (vuelto > 0) {
                            swalConfig.html = `<span id="mensajeVuelto" >Vuelto: ${formatPrice(vuelto)}</span>`;
                        } else {
                            swalConfig.html = `<span id='mensajeexacto'>Pago Exacto</span>`;
                        }
                        Swal.fire(swalConfig);
                        $campoBusqueda.val('').focus();
                    }, 100);
                });
}

    $('.Cliente').change(function(event) {
      if ($(this).val()) {
      setTimeout(function() {$('.productos').focus();}, 100);
      }
    });

    $('#modal-id').on('shown.bs.modal', function () {
        if (motopagar !== undefined) {
          $tbody.find('tr').each(function () {
            var $td = $(this).find('td');
            var signo = $td.data('signo');
            var cambio = $td.data('cambio');

            if (cambio !== undefined) {
              var resultado = motopagar / cambio;
              var equivalente = resultado % 1 === 0 ? resultado.toFixed(0) : Number.parseFloat(resultado).toFixed(2);
              $td.text(equivalente + ' ' + signo);
            } else {
              $td.text('');
            }
          });
        }
    });

    $('#c a').click(function(event) {
      if (!Cliente) {
          event.preventDefault(); // Evita que la pestaña se active
Swal.fire({
  title: 'Buscar Cliente por Ruc',
  html: '<input id="clienteInput" class="swal2-input" placeholder="Escribe el nombre del cliente">',
  showCancelButton: true,
  showConfirmButton: true,
  didRender: () => {
    const messageInput = document.getElementById('clienteInput');
    messageInput.addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
        Swal.clickConfirm();
      }
    });
    setTimeout(() => {messageInput.focus()}, 500);
  },
  preConfirm: async () => {
    const clienteNombre = document.getElementById('clienteInput').value;
    try {
      const response = await fetch('/gescon/Cliente/select2?q=' + clienteNombre, { method: 'GET' });
      if (!response.ok) {
        throw new Error('Error en la búsqueda');
      }
      const data = await response.json();
      if (data.length > 0) {
        const clienteEncontrado = data[0];
        await Swal.fire({
          title: 'Cliente Encontrado',
          text: `Nombre del cliente: ${clienteEncontrado.full_name}`,
          icon: 'success',
          showCancelButton: false,
        });
        Cliente = clienteEncontrado.id;
        $('#ClienteSearch').append(new Option(clienteEncontrado.full_name, clienteEncontrado.id, true, true)).trigger('change');
        $('#c a').tab('show');
        setTimeout(() => {
          $('#numcheque').focus();
        }, 2000);
      } else {
        Swal.showValidationMessage(`No se encontró un cliente con ese RUC.`);
      }
    } catch (error) {
      Swal.fire('Error', `Error en la búsqueda: ${error.message}`, 'error');
    }
  },
  didClose: () => {
    $("#EF1").focus();
    console.log('El diálogo Swal se ha cerrado.');
  }
});
        }
    });

});
///////////////////////////////////fuera del ready//////////////////////////////////////////
    function addclien(arguments) {
      $("#NOM, #TE, #RUC, #alertas").html("").css({"display":"none"});
      $("#inserc, .modal-header").show();
      $('#inserc')[0].reset();
      $('#modal-1').modal('show');
      setTimeout(function() {
        $('#nombre').focus();
          }, 500);

    }

  function Limpiar(id) {
    $("#ClienteSearch, #orden").val('').trigger('change');
    $('#condicion').val('1');
    $('#tipoComprovante').val('').trigger('change');
    $('#Estado').val('0');
    $('#contenCuotas').hide();
    $('#fletes, #Direccion').prop('required', false).prop('disabled', true).val('');
    cargarContenido('Venta/loader/1', 'detalle'); 
  }

    $('.validate').keyup(function(event) {
         var cart_total     = $('#cart_total').val();
          $('#'+this.id).attr({max:cart_total,maxlength:cart_total.length});
           this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    $(document).on('keyup', '.validat', function(event) {
      let value = (this.value + '').replace(/[^0-9]/g, '');
      if (value > 1) {
        $(this).val(formatPrice(value));
      }
    });

    const add = (data) => {
      $.ajax({
        url: "Venta/ajax_add",
        type: 'POST',
        dataType: 'json',
        data: data,
        success: (json) => {
          setTimeout(function() {
              $('#bguarda').removeClass('fa-spinner fa-spin').addClass('fa-save');
          }, 500);
          if (json.res === "error") {
            const { Cliente, comprobante, orden, montofinal, tipoComprovante, fecha, inicial, condicion, cuotas, fletes, descuento, Direccion, observaciones } = json;
            if (Cliente) $(".PRO").append(Cliente).show(); // mostrar validación de ítem
            if (comprobante) $(".COMP").append(comprobante).show(); // mostrar validación de ítem
            if (orden) $(".PR").append(orden).show(); // mostrar validación de ítem
            if (montofinal) $(".FINAL").append(montofinal).show(); // mostrar validación de ítem
            if (tipoComprovante) $(".TIPO").append(tipoComprovante).show(); // mostrar validación de ítem
            if (fecha) $(".FECHA").append(fecha).show(); // mostrar validación de ítem
            if (inicial) $(".INIT").append(inicial).show(); // mostrar validación de ítem
            if (condicion) $(".COND").append(condicion).show(); // mostrar validación de ítem
            if (cuotas) $(".CUO").append(cuotas).show(); // mostrar validación de ítem
            if (fletes) $(".FLE").append(fletes).show(); // mostrar validación de ítem
            if (descuento) $(".desc").append(descuento).show(); // mostrar validación de ítem
            if (Direccion) $(".DIR").append(Direccion).show(); // mostrar validación de ítem
            if (observaciones) $(".OBSER").append(observaciones).show(); // mostrar validación de ítem
          } else {
            alertasave('Datos Registrado correctamente');
            setTimeout(() => {
              save_method = 'add';
              $campoBusqueda.focus();
            }, 1510);
            $('#vender')[0].reset();
            if ($('#tipoComprovante').val() == 1) {
              $('#TerceraSeccion').text(json.TerceraSeccion);
            } else {
              $('[name="Ticket"]').val(json.NumeroTicket);
            }
            Limpiar(1);
          }
        },
        error: (xhr, status, error) => {
          Swal.fire('Disculpe, existió un problema');
        },
        complete: () => {
          console.log("complete");
        }
      });

 
    }
/////////////////////////////seccion del carrito//////////////////////////////
    $(document).on('change blur', '.cantidad', function(event) {
      var j = $(this);
      var id2 = j.data('id2');
      var id = j.attr('id');
      var num = j.val();
      console.log(id2 +' '+ j.val());
      if (parseFloat(id2) < parseFloat(num)) {
        toastem.error("Cantidad maxima en stock:  "+id2);
        update_rowid(1, id);
      } else {
        update_rowid(parseFloat(num), id);
      }
    });

    $(document).on('change', '[name="descuento"]', function(event) {
        $val = $(this).val();
        $id = $(this).data('id');
        $qty = $(this).data('qty');
        $price = $(this).data('price');
        $i = $(this).data('i');
        $stock = $(this).data('stock');
        $descuento = $(this).data('descuento');
        $idcode = $(this).data('idcode');
        update_descuento($val, $id, $qty, $price,$i,$stock,$descuento,$idcode);
    });

    $('.idRecorrer td.selec select.descuento').each(function(index, el) {
        $(this).val($(this).data('tyf'));
    });

    $(document).on('click', 'div.pull-right a.deleterow', function(event) {
        var rowid = $(this).data('id');
        deleterowid(rowid);
    });
    
    function update_rowid(val,id) {
      if (val !== '' && val !== 0 ) {
        var parametro = {}
        $.ajax({
          url : "Orden_compra/update_rowid/"+id,
          type: "POST",
          dataType: "JSON",
          data: {qty: val},
        })
        .done(function(json) {
            if (json.res == 'error') {
              if (json.qty) {
                toastem.error(json.qty);
              }
            }else{

                cargarContenido('Venta/loader', 'detalle');
              }
        })
        .fail(function() {
          toastem.error("error");
        })
        .always(function() {
        });

      }else{
        toastem.error("Cantidad no Soportado");
      }
    }



    function deleterowid(rowid)    {
        if (rowid) {
          $.ajax({
              url : "Orden_compra/delete_item/"+rowid,
              type: "POST",
              cache: false,
              data: $(this).serialize(), // serilizo el formulario
              success: function(data)
              {

                  cargarContenido('Venta/loader', 'detalle');
              },
          });
        } else {
          Swal.fire("Cancelled", "Sin accion:)", "error");
        }
    }

    function update_descuento(val,id,qty,price,i,stock,descuento,$idcode) {
      $.ajax({
      url: "Orden_compra/update_descuento",
      type: 'POST',
      dataType: 'JSON',
      data: {
        val: val,
        id: id,
        qty: qty,
        price: price,
        i: i,
        stock: stock,
        descuento: descuento,
        code: $idcode
      },
      })
      .done(function(data) {
        if (data) {
            cargarContenido('Venta/loader', 'detalle');
        }
      })
      .fail(function() {
        console.log("error");
      })
    }

////////////////////final seccion carrito////////////////////////////////

//////////////////////Seccion cobros/////////////////////////////////////
// Función para realizar un cambio
const cambio = (id) => {


  const $EF = $(`#EF${id}`);
  const val = Number($EF.val().replace(/[^\d]/g, ''));
  const monto = $EF.data('monto');
  let total = '';
  if (val > 0) {
    if (monto > 0) {
      total = operaciones(val, monto, '*');
    } else {
      $(`#MontoMoneda${id}`).val(val);
      total = val;
    }
  } 

  $(`#MontoMoneda${id}`).val(val);
  const $montoFormat = $(`#montoFormat${id}`);
  $montoFormat.val(formatNum(total));
  $(`#montoCambiado${id}`).val(total);
  $('#ParcialE').html(formatNum(recorrerMoneda()));
  $('#parcial1').val(recorrerMoneda());
  totalparcial(1);
}

const recorrerMoneda = () => {
  let val = $('#valtotalmoneda').val();
  let resultVal = 0;
  for (let i = 1; i <= val; i++) {
    let res = $('#montoCambiado'+i).val();
    if (res > 0) {
       resultVal += parseFloat(res);
    }
  }
  return resultVal;
};

const totalparcial = (xx) => {

  let total = 0;
  for (let i = 1; i < 5; i++) {
    let id = $('#parcial'+i).val()
    if (id > 0) {
      total += parseFloat(id);
    }
  }
  totalparciales(total);
};

const totalparciales = (total) => {
    if (total > 0) {

        $('#Totalp').html(formatNum(total));
        $('#Totalparclal').val(total);
        let final = operaciones(total, motopagar, '-');
        $('#rerer').html(formatNum(final));
        if (final > 0) {
          $('#vuelto').html(formatNum(final));
          $('#vueltototal').val(final);
          $('#rerer').html('');
          if (Cliente > 0) {
            $(".controlajustar").show();
          }
          reerrt();
        } else {
          if (Cliente > 0) {
            $(".controlajustar").hide();
          }
          $('#vueltototal').val('');
          $('#vuelto').html('');
          reerrt();
        }
     } else {
        $('#agregar_cuenta').removeAttr('checked');
        $('#vueltototal,#Totalparclal,#si_no,#ajustado').val('');
        $(".controlajustar").hide();
        $('#valor,#rerer,#vuelto,#Totalp').html('');
     }
};


function reerrt(argument) {
                  $('#agregar_cuenta').removeAttr('checked');
                  $('#si_no').val('');
                  $('#ajustado').val('');
                  $('#valor').html('');
}


function reloadcheque(argument) {
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").attr('disabled','disabled');
                  $("#Cliente,#efectivotxt,#fecha_pago,#cuenta_bancaria").removeAttr('required');
                  banderas = true;  

}

/////////////////////////////////////parte de reaysy/////////////////////////////////////////////

$('#customSwitch1').change(function() {
    if ($(this).is(':checked')) {
        $('.mostrarMoneda').fadeIn(); // Muestra los elementos con un efecto de desvanecimiento
    } else {
        $('.mostrarMoneda').fadeOut(); // Oculta los elementos con un efecto de desvanecimiento
    }
});

//////////////////////////////////////////
$('#efectivotxt').on('keyup', function(event) {
  let valor = (this.value + '').replace(/[^0-9]/g, '');
  let resultado = ($('#to_chek').val() > 0) ? $('#to_chek').val() : 0;
  let $parcialC = $('#ParcialC');
  let $parcial2 = $('#parcial2');
  let $efectivo = $('#efectivo');

  if (valor) {
    $('#numcheque').prop('required', true);
    let temporal = operaciones(resultado, valor, '+');
    $parcialC.text(formatNum(temporal));
    $parcial2.val(temporal);
    $efectivo.val(temporal);
    totalparcial(1);
  } else {
    if (resultado > 0) {
      $parcialC.text(formatNum(resultado));
      $parcial2.val(resultado);
      totalparcial(1);
    } else {
      $parcialC.empty();
      $parcial2.val('');
      $efectivo.val('');
      totalparcial(1);
    }
  }
});
  // control de cheque
  $("#numcheque").on("input", function() {
      let id = $(this).val();
      if (id > 0) {
        $(".chequeControl").prop('disabled', false).prop('required', true);
      } else {
        $(".chequeControl").prop('disabled', true).prop('required', false);
        $('.chequeControl,#efectivotxt').val('').trigger('keyup');
        $(this).prop('required', false);
        totalparcial(1);
      }
    });


// fin cheque efectivo 

// solo tarjeta
$(document).on('keyup', '#efectivoTarjetatext', function(event) {
  let valor = (this.value + '').replace(/[^0-9]/g, '');
  $('#ParcialT').html(valor ? formatNum(valor) : '');
  $('#parcial3, #efectivoTarjeta').val(valor || '');
  totalparcial(1);
});
// fin solo tarjeta

// cuenta a fabor
$('#multifabor').change(function() {
  let myArray = [];
  let myArray2 = [];
  let resultado = 0;

  $("#multifabor :selected").each(function() {
    const [val1, val2] = $(this).val().split(',');
    myArray.push(parseFloat(val1));
    myArray2.push(val2);
  });

  resultado = myArray.reduce((acc, val) => acc + (val >= 0 ? val : 0), 0);

  $('#ParcialF').html(formatNum(resultado));
  $('#parcial4').val(resultado);
  $('#matris').val(myArray);
  $('#matriscuanta').val(myArray2);
  totalparcial(1);
});
// fin fabor
 
$("#agregar_cuenta").on( 'change', function() {
    // let diferencia = parseInt($('#diferencia').val());
    let vueltototal = $('#vueltototal').val();

    if( $(this).is(':checked') ) {
          $('#si_no').val('1');
            $('#ajustado').val(vueltototal);
              $('#valor').html(formatNum(vueltototal));
            $('#vuelto').html('');
            $('#vueltototal').val('');


    } else {
              $('#vuelto').html(formatNum($('#ajustado').val()));
              $('#vueltototal').val($('#ajustado').val());
              $('#si_no').val('');
              $('#ajustado').val('');
              $('#valor').html('');
    }
});

$('a[data-inputName]').on('click', function() {
  var inputName = $(this).data('inputName');
  setTimeout(function() {
    var activeTab = $('.tab-pane.active');
    var firstInput = activeTab.find('input:not(:checkbox, :hidden):first, select:first, .select2:first');
    if (firstInput.length > 0) {
      if (firstInput[0].classList.contains('select2')) {
        firstInput.select2('open');
      } else {
        firstInput[0].focus();
      }
    }
  }, 200);
});


$('ul li#s a').click(function() {
  if (banderas && Cliente > 1) {
    cargarFormaPago();
  }
});

async function cargarFormaPago() {
  try {
    const response = await fetch("Venta/formapago/3/" + Cliente);
    if (!response.ok) {
      throw new Error("Error al cargar forma de pago");
    }
    const data = await response.text();
    $("#multifabor").html(data);
    banderas = false;
  } catch (error) {
    console.log(error);
  }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

</script>
