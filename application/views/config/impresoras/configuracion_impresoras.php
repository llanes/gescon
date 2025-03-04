      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Gescom
            <small>Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero</li>
          </ol>
        </section>
        <link href="<?= base_url('bower_components/ConectorJavaScript.json')?>" rel="manifest">
                <!-- CONTENIDO GENERAL CENTRAL DEL PROYECTO-->
        <section class="content">
    <div class="container">
        <h2 class="text-center">Configuración de Impresoras</h2>
        <div class="row">
            <div class="col-md-6">
                <h3 class="text-center">Agregar Impresoras</h3>
                <form id="formAgregarImpresora">
                    <div class="form-group">
                        <label for="modelo_impresora">Modelo de Impresora:</label>
                        <select class="form-control" id="modelo_impresora" name="modelo_impresora">
                            <!-- <option value="Epson|TM-T88V|80mm">Epson TM-T88V - 80mm</option>
                            <option value="Star|Micronics TSP100|80mm">Star Micronics TSP100 - 80mm</option>
                            <option value="Epson|TM-U220B|76mm">Epson TM-U220B - 76mm</option>
                            <option value="Star|Micronics SP700|76mm">Star Micronics SP700 - 76mm</option>
                            <option value="Bixolon|SRP-350|80mm">Bixolon SRP-350 - 80mm</option>
                            <option value="Citizen|CT-S310II|80mm">Citizen CT-S310II - 80mm</option>
                            <option value="Epson|TM-T20II|80mm">Epson TM-T20II - 80mm</option>
                            <option value="Star|Micronics mC-Print3|80mm">Star Micronics mC-Print3 - 80mm</option>
                            <option value="Bixolon|SRP-275III|76mm">Bixolon SRP-275III - 76mm</option>
                            <option value="Zebra|ZD410|58mm">Zebra ZD410 - 58mm</option> -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tamano_papel">Tamaño de Papel:</label>
                        <select class="form-control" id="tamano_papel" name="tamano_papel">
                            <option value="80mm">80mm</option>
                            <option value="76mm">76mm</option>
                            <option value="58mm">58mm</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="conexion">Tipo de Conexión:</label>
                        <select class="form-control" id="conexion" name="conexion">
                            <option value="USB">USB</option>
                            <option value="Bluetooth">Bluetooth</option>
                            <option value="Red">Red</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Impresora</button>
                    <button onclick="mostrar_impresoras()" class="btn btn-info">Mostrar Impresoras</button>
                </form>
            </div>
            <div class="col-md-6">
                    <h3 class="text-center">Lista de Impresoras</h3>
                    <div id="impresorasTable"></div>
                </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="<?= base_url('bower_components/ConectorJavaScript.js')?>"></script>

    <script>
        // const obtenerListaDeImpresoras = async () => {
        //     return await ConectorPluginV3.obtenerImpresoras();
        // }
        const URLPlugin = "http://localhost:8000";
        const $listaDeImpresoras = document.querySelector("#modelo_impresora");

        const init = async () => {
            const impresoras = await ConectorPluginV3.obtenerImpresoras(URLPlugin);
            for (const impresora of impresoras) {
                $listaDeImpresoras.appendChild(Object.assign(document.createElement("option"), {
                    value: impresora,
                    text: impresora,
                }));
            }
        }

        init();



        document.getElementById('modelo_impresora').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var tamano = selectedOption.value.split('|')[2]; // Obtiene el tamaño del papel del valor seleccionado
            document.getElementById('tamano_papel').value = tamano; // Establece el tamaño del papel
        });
            // Function to load printers table
            function loadImpresorasTable() {
                $.ajax({
                    url: '<?php echo site_url("configuracion/obtener_impresoras"); ?>',
                    type: 'GET',
                    success: function(response) {
                        $('#impresorasTable').html(response);
                    }
                });
            }

            // Submit form via AJAX
            $('#formAgregarImpresora').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '<?php echo site_url("configuracion/guardar_impresora"); ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        loadImpresorasTable(); // Reload table after successful addition
                        $('#formAgregarImpresora')[0].reset(); // Reset form after submission
                    }
                });
            });



            

            // Función para eliminar una impresora mediante AJAX con el método POST y async/await
            async function deleteImpresora(id) {
                if (confirm('¿Estás seguro de eliminar esta impresora?')) {
                    try {
                        const response = await $.ajax({
                            url: '<?php echo site_url("configuracion/eliminar_impresora"); ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: { id: id }, // Envía el ID como parte del cuerpo de la solicitud
                        });

                        if(response.status === 'success') {
                            alert(response.message);
                            loadImpresorasTable(); // Recarga la tabla después de una eliminación exitosa
                        } else {
                            alert('Hubo un error al eliminar la impresora.');
                        }
                    } catch (error) {
                        console.error('Error al eliminar la impresora:', error);
                    }
                }
            }
            function mostrar_impresoras() {
                connetor_plugin.obtenerImpresoras()
                    .then(impresoras => {
                        console.log(impresoras);

                        // Obtener el elemento select
                        var selectElement = document.getElementById("modelo_impresora");

                        // Limpiar cualquier opción anterior
                        selectElement.innerHTML = '';

                        // Crear y agregar una opción por cada impresora
                        impresoras.forEach(impresora => {
                            var option = document.createElement("option");
                            option.text = impresora.modelo;
                            option.value = impresora.id; // Puedes usar un identificador único si lo necesitas
                            selectElement.add(option);
                        });
                    });
            }




            // Initial load of printers table
            $(document).ready(function() {
                $("#Configuracion,#conf_impresora").addClass('active');
                loadImpresorasTable();
                // mostrar_impresoras() ;

            });
        </script>
</div><!-- ./wrapper -->

    <!-- 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html> -->



