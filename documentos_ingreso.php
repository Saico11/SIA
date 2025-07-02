<?php
include('conexion.php');

// Obtener empleados y proveedores para los formularios
$query_empleados = "SELECT * FROM Empleado";
$stmt_empleados = $conn->prepare($query_empleados);
$stmt_empleados->execute();
$empleados = $stmt_empleados->fetchAll(PDO::FETCH_ASSOC);

$query_proveedores = "SELECT * FROM Proveedor";
$stmt_proveedores = $conn->prepare($query_proveedores);
$stmt_proveedores->execute();
$proveedores = $stmt_proveedores->fetchAll(PDO::FETCH_ASSOC);

// Obtener productos para los detalles
$query_productos = "SELECT * FROM producto";
$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->execute();
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// Insertar documento de ingreso y detalles
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    $id_empleado = $_POST['id_empleado'];
    $ruc = $_POST['ruc'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $tipo_documento = $_POST['tipo_documento'];

    // Recoger los detalles del documento
    $detalles = [];
    $detalles_json = json_decode($_POST['detalles'], true);

    foreach ($detalles_json as $detalle) {
        $detalles[] = [
            'id_producto' => $detalle['id_producto'],
            'cantidad' => $detalle['cantidad'],
            'precio_unitario' => $detalle['precio_unitario'],
            'precio_total' => $detalle['precio_total'],
            'observaciones' => $detalle['observaciones']
        ];
    }

    // Llamar al procedimiento para insertar documento y detalles
    $query_insert = "CALL insertar_documento_y_detalles(:id_empleado, :ruc, :fecha_ingreso, :tipo_documento, :detalles)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':id_empleado', $id_empleado);
    $stmt_insert->bindParam(':ruc', $ruc);
    $stmt_insert->bindParam(':fecha_ingreso', $fecha_ingreso);
    $stmt_insert->bindParam(':tipo_documento', $tipo_documento);
    $stmt_insert->bindParam(':detalles', json_encode($detalles));  // Pasar los detalles como JSON
    $stmt_insert->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento de Ingreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Documento de Ingreso</h2>
        <form method="POST">
            <!-- Datos del Documento de Ingreso -->
            <div class="mb-3">
                <label for="id_empleado" class="form-label">Empleado</label>
                <select id="id_empleado" name="id_empleado" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?php echo $empleado['id_empleado']; ?>"><?php echo $empleado['nombres'] . ' ' . $empleado['apellidos']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="ruc" class="form-label">Proveedor (RUC)</label>
                <select id="ruc" name="ruc" class="form-select" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?php echo $proveedor['ruc']; ?>"><?php echo $proveedor['razon_social']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
            </div>

            <div class="mb-3">
                <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                <input type="text" class="form-control" id="tipo_documento" name="tipo_documento" required>
            </div>

            <!-- Detalles del Documento de Ingreso -->
            <h4>Detalles del Documento de Ingreso</h4>
            <div id="detalles_container">
                <div class="mb-3 detalle">
                    <label for="id_producto" class="form-label">Producto</label>
                    <select class="form-select" name="detalles[0][id_producto]" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['descripcion']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" name="detalles[0][cantidad]" required>

                    <label for="precio_unitario" class="form-label">Precio Unitario</label>
                    <input type="number" class="form-control" name="detalles[0][precio_unitario]" step="0.01" required>

                    <label for="precio_total" class="form-label">Precio Total</label>
                    <input type="number" class="form-control" name="detalles[0][precio_total]" step="0.01" required>

                    <label for="observaciones" class="form-label">Observaciones</label>
                    <input type="text" class="form-control" name="detalles[0][observaciones]" required>
                </div>
            </div>

            <button type="submit" name="insertar" class="btn btn-primary">Registrar Documento de Ingreso</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para agregar más detalles dinámicamente
        let detallesIndex = 1;

        // Función para agregar más productos a los detalles
        function agregarDetalle() {
            const container = document.getElementById("detalles_container");
            const nuevoDetalle = document.createElement("div");
            nuevoDetalle.classList.add("mb-3", "detalle");
            nuevoDetalle.innerHTML = `
                <label for="id_producto" class="form-label">Producto</label>
                <select class="form-select" name="detalles[${detallesIndex}][id_producto]" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['descripcion']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" name="detalles[${detallesIndex}][cantidad]" required>

                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                <input type="number" class="form-control" name="detalles[${detallesIndex}][precio_unitario]" step="0.01" required>

                <label for="precio_total" class="form-label">Precio Total</label>
                <input type="number" class="form-control" name="detalles[${detallesIndex}][precio_total]" step="0.01" required>

                <label for="observaciones" class="form-label">Observaciones</label>
                <input type="text" class="form-control" name="detalles[${detallesIndex}][observaciones]" required>
            `;
            container.appendChild(nuevoDetalle);
            detallesIndex++;
        }
    </script>
</body>
</html>

