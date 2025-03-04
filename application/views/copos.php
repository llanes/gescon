<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Ticket</title>
    <script>
        // Función para imprimir el ticket (simulada)
        async function printTicket() {
    try {
        // Hacer una solicitud para obtener el contenido del ticket
        const response = await fetch('TicketController/generate_ticket');

        
        // Verificar si la respuesta fue exitosa
        if (!response.ok) {
            throw new Error('Error al obtener el ticket');
        }

        // Convertir la respuesta en formato JSON
        const data = await response.json();

        // Verificar que los datos y la propiedad 'ticket' estén presentes
        if (data && data.ticket) {
            console.log("Contenido del ticket:", data.ticket);  // Simula la impresión en la consola
        } else {
            console.log("El ticket no tiene el formato esperado o no se recibió.");
            return;
        }

        // Simular que la impresora se conecta y se imprime el ticket (sin impresora conectada)
        setTimeout(() => {
            console.log("Simulación: El ticket fue 'impreso' con éxito.");
            alert("Ticket impreso correctamente (simulado).");
        }, 1000);

    } catch (error) {
        console.error('Error al obtener el ticket o al imprimir:', error);
        alert('Hubo un error al intentar imprimir el ticket.');
    }
}




    </script>
</head>
<body>
    <button onclick="printTicket()">Imprimir</button>
</body>
</html>
