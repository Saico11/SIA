<?php
include('conexion.php');

// Obtener productos
$query_productos = "SELECT Grupo, Cantidad, Descripcion, Unidad FROM Producto";  
$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->execute();
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener almacenes
$query_almacenes = "SELECT id_almacen, Nombre FROM Almacen";  
$stmt_almacenes = $conn->prepare($query_almacenes);
$stmt_almacenes->execute();
$almacenes = $stmt_almacenes->fetchAll(PDO::FETCH_ASSOC);

// Insertar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    // Recoger datos del formulario
    $id_almacen = $_POST['id_almacen'];
    $grupo = $_POST['grupo'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];
    $unidad = $_POST['unidad'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $fecha_ingreso = $_POST['fecha_ingreso'];

    try {
        // Llamar al procedimiento para insertar el producto
        $query_insert = "CALL insertar_producto(:id_almacen, :grupo, :cantidad, :descripcion, :unidad, :fecha_vencimiento, :fecha_ingreso)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bindParam(':id_almacen', $id_almacen);
        $stmt_insert->bindParam(':grupo', $grupo);
        $stmt_insert->bindParam(':cantidad', $cantidad);
        $stmt_insert->bindParam(':descripcion', $descripcion);
        $stmt_insert->bindParam(':unidad', $unidad);
        $stmt_insert->bindParam(':fecha_vencimiento', $fecha_vencimiento);
        $stmt_insert->bindParam(':fecha_ingreso', $fecha_ingreso);
        $stmt_insert->execute();

        echo "<div class='alert alert-success'>Producto agregado exitosamente.</div>";
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
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Productos</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_almacen" class="form-label">Almacén</label>
                <select class="form-control" id="id_almacen" name="id_almacen" required>
                    <option value="">Seleccione un almacén</option>
                    <?php 
                    // Verificar si los almacenes están disponibles
                    if (!empty($almacenes)): 
                        foreach ($almacenes as $almacen): ?>
                            <option value="<?php echo $almacen['id_almacen']; ?>"><?php echo $almacen['nombre']; ?></option>
                        <?php endforeach; 
                    else: ?>
                        <option value="">No hay almacenes disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="grupo" class="form-label">Grupo</label>
                <input type="text" class="form-control" id="grupo" name="grupo" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="unidad" class="form-label">Unidad</label>
                <input type="text" class="form-control" id="unidad" name="unidad" required>
            </div>
            <div class="mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
            </div>

            <button type="submit" name="insertar" class="btn btn-primary">Agregar Producto</button>
        </form>

        <h3 class="mt-4">Lista de Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Grupo</th>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['grupo']; ?></td>
                        <td><?php echo $producto['cantidad']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['unidad']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

