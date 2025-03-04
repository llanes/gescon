<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIFEN - Generación y Envío de Documentos Electrónicos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Integración con SIFEN - Facturación Electrónica</h1>

    <!-- Sección 1: Generar Documento Electrónico -->
    <div>
        <h2>1. Generar Documento Electrónico (DE)</h2>
        <form id="form-generar-de">
            <label for="ruc">RUC:</label>
            <input type="text" id="ruc" name="ruc" required><br><br>
            <button type="submit">Generar Documento Electrónico</button>
        </form>
        <div id="resultado-generar"></div>
    </div>

    <!-- Sección 2: Enviar Lote a SIFEN -->
    <div>
        <h2>2. Enviar Lote a SIFEN</h2>
        <button id="btn-enviar-lote">Enviar Lote</button>
        <div id="resultado-enviar"></div>
    </div>

    <!-- Sección 3: Consultar Estado del Lote -->
    <div>
        <h2>3. Consultar Estado del Lote</h2>
        <label for="numero-lote">Número de Lote:</label>
        <input type="text" id="numero-lote" name="numero-lote"><br><br>
        <button id="btn-consultar-lote">Consultar Lote</button>
        <div id="resultado-consultar-lote"></div>
    </div>

    <!-- Sección 4: Consultar Documento Electrónico por CDC -->
    <div>
        <h2>4. Consultar Documento Electrónico</h2>
        <label for="cdc">Código CDC:</label>
        <input type="text" id="cdc" name="cdc"><br><br>
        <button id="btn-consultar-de">Consultar Documento</button>
        <div id="resultado-consultar-de"></div>
    </div>

    <script>
        $(document).ready(function () {
            // Generar Documento Electrónico (DE)
            $('#form-generar-de').on('submit', function (e) {
                e.preventDefault();
                var ruc = $('#ruc').val();
                if (ruc) {
                    $.ajax({
                        url: '/sifen/generar_documento_electronico',
                        method: 'POST',
                        data: { ruc: ruc },
                        success: function (response) {
                            $('#resultado-generar').html(response);
                        },
                        error: function () {
                            $('#resultado-generar').html('Error al generar el documento electrónico.');
                        }
                    });
                } else {
                    alert('Por favor, ingresa el RUC.');
                }
            });

            // Enviar Lote a SIFEN
            $('#btn-enviar-lote').on('click', function () {
                var ruc = $('#ruc').val();
                if (ruc) {
                    $.ajax({
                        url: '/sifen/enviar_lote',
                        method: 'POST',
                        data: { ruc: ruc },
                        success: function (response) {
                            $('#resultado-enviar').html(response);
                        },
                        error: function () {
                            $('#resultado-enviar').html('Error al enviar el lote.');
                        }
                    });
                } else {
                    alert('Por favor, ingresa el RUC.');
                }
            });

            // Consultar Estado del Lote
            $('#btn-consultar-lote').on('click', function () {
                var numeroLote = $('#numero-lote').val();
                if (numeroLote) {
                    $.ajax({
                        url: '/sifen/consultar_lote',
                        method: 'POST',
                        data: { numero_lote: numeroLote },
                        success: function (response) {
                            $('#resultado-consultar-lote').html(response);
                        },
                        error: function () {
                            $('#resultado-consultar-lote').html('Error al consultar el estado del lote.');
                        }
                    });
                } else {
                    alert('Por favor, ingresa el número de lote.');
                }
            });

            // Consultar Documento Electrónico por CDC
            $('#btn-consultar-de').on('click', function () {
                var cdc = $('#cdc').val();
                if (cdc) {
                    $.ajax({
                        url: '/sifen/consultar_documento',
                        method: 'POST',
                        data: { cdc: cdc },
                        success: function (response) {
                            $('#resultado-consultar-de').html(response);
                        },
                        error: function () {
                            $('#resultado-consultar-de').html('Error al consultar el documento.');
                        }
                    });
                } else {
                    alert('Por favor, ingresa el Código CDC.');
                }
            });
        });
    </script>
</body>
</html>
