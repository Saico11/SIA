<?php
include('conexion.php');

// Obtener proveedores
$query = "SELECT RUC, Razon_Social FROM Proveedor";  // Consulta directa
$stmt = $conn->prepare($query);
$stmt->execute();
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Asegúrate de que esto esté bien

// Insertar proveedor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    // Recoger datos del formulario
    $ruc = $_POST['ruc'];
    $razon_social = $_POST['razon_social'];

    try {
        // Llamar al procedimiento para insertar el proveedor
        $query_insert = "CALL insertar_proveedor(:ruc, :razon_social)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bindParam(':ruc', $ruc);
        $stmt_insert->bindParam(':razon_social', $razon_social);
        $stmt_insert->execute();

        echo "<div class='alert alert-success'>Proveedor agregado exitosamente.</div>";
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
    <title>Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Proveedores</h2>

        <!-- Formulario para insertar proveedor -->
        <form method="POST">
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC</label>
                <input type="text" class="form-control" id="ruc" name="ruc" required>
            </div>
            <div class="mb-3">
                <label for="razon_social" class="form-label">Razón Social</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" required>
            </div>
            <button type="submit" name="insertar" class="btn btn-primary">Agregar Proveedor</button>
        </form>

        <h3 class="mt-4">Lista de Proveedores</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>RUC</th>
                    <th>Razón Social</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Comprobamos si los proveedores existen y los mostramos
                if (!empty($proveedores)) {
                    foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><?php echo $proveedor['ruc']; ?></td>
                            <td><?php echo $proveedor['razon_social']; ?></td>
                        </tr>
                    <?php endforeach; 
                } else {
                    echo "<tr><td colspan='2'>No hay proveedores registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

