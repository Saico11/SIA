<?php
include('conexion.php');

// Obtener almacenes
$query = "SELECT Nombre, Descripcion, Cantidad FROM Almacen";  // Consulta directa
$stmt = $conn->prepare($query);
$stmt->execute();
$almacenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Insertar almacén
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];

    try {
        // Llamar al procedimiento para insertar el almacén
        $query_insert = "CALL insertar_almacen(:nombre, :descripcion, :cantidad)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bindParam(':nombre', $nombre);
        $stmt_insert->bindParam(':descripcion', $descripcion);
        $stmt_insert->bindParam(':cantidad', $cantidad);
        $stmt_insert->execute();

        echo "<div class='alert alert-success'>Almacén agregado exitosamente.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Almacenes</h2>

        <!-- Formulario para insertar almacén -->
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
            </div>
            <button type="submit" name="insertar" class="btn btn-primary">Agregar Almacén</button>
        </form>

        <h3 class="mt-4">Lista de Almacenes</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Comprobamos si los almacenes existen y los mostramos
                if (!empty($almacenes)) {
                    foreach ($almacenes as $almacen): ?>
                        <tr>
                            <td><?php echo $almacen['nombre']; ?></td>
                            <td><?php echo $almacen['descripcion']; ?></td>
                            <td><?php echo $almacen['cantidad']; ?></td>
                        </tr>
                    <?php endforeach; 
                } else {
                    echo "<tr><td colspan='3'>No hay almacenes registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

