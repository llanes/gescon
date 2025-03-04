<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Modelo</th>
            <th>Tamaño de Papel</th>
            <th>Conexión</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($impresoras as $impresora): ?>
            <tr>
                <td><?php echo $impresora->nombre; ?></td>
                <td><?php echo $impresora->modelo; ?></td>
                <td><?php echo $impresora->tamano_papel; ?></td>
                <td><?php echo $impresora->conexion; ?></td>
                <td>
                    <a href="javascript:void(0);" onclick="deleteImpresora(<?php echo $impresora->id; ?>);" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
