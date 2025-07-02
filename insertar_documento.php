<?php
include('conexion.php');

// Obtener empleados y proveedores para desplegar en los formularios
$query_empleados = "SELECT ID_Empleado, CONCAT(Nombres, ' ', Apellidos) AS nombre FROM Empleado";
$stmt_empleados = $conn->prepare($query_empleados);
$stmt_empleados->execute();
$empleados = $stmt_empleados->fetchAll(PDO::FETCH_ASSOC);

$query_proveedores = "SELECT * FROM Proveedor";
$stmt_proveedores = $conn->prepare($query_proveedores);
$stmt_proveedores->execute();
$proveedores = $stmt_proveedores->fetchAll(PDO::FETCH_ASSOC);

// Insertar el documento de ingreso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $ruc = $_POST['ruc'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $tipo_documento = $_POST['tipo_documento'];

    $query_insert = "CALL insertar_documento_ingreso(:id_empleado, :ruc, :fecha_ingreso, :tipo_documento)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bindParam(':id_empleado', $id_empleado);
    $stmt_insert->bindParam(':ruc', $ruc);
    $stmt_insert->bindParam(':fecha_ingreso', $fecha_ingreso);
    $stmt_insert->bindParam(':tipo_documento', $tipo_documento);
    $stmt_insert->execute();

    echo "<div class='alert alert-success'>Documento de ingreso insertado correctamente</div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Documento de Ingreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Insertar Documento de Ingreso</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_empleado" class="form-label">Empleado</label>
                <select id="id_empleado" name="id_empleado" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?php echo $empleado['ID_Empleado']; ?>"><?php echo $empleado['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="ruc" class="form-label">Proveedor (RUC)</label>
                <select id="ruc" name="ruc" class="form-select" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?php echo $proveedor['RUC']; ?>"><?php echo $proveedor['Razon_Social']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                <input type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Insertar Documento</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

