<?php
// Incluir la conexión a la base de datos
include "conexion.php";

// Procesar la acción de agregar, eliminar o modificar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['accion'] == 'agregar') {
        // Obtener los datos del formulario
        $numero_fact = $_POST['numero_fact'];
        $total = $_POST['total'];
        $fecha = $_POST['fecha'];
        $id_empleado = $_POST['id_empleado'];
        $ruc = $_POST['ruc'];

        // Llamar al procedimiento almacenado para agregar factura
        $sql = "CALL InsertarFactura('$numero_fact', '$total', '$fecha', '$id_empleado', '$ruc')";
        if ($conexion->query($sql) === TRUE) {
            echo "Factura agregada exitosamente.";
        } else {
            echo "Error al agregar factura: " . $conexion->error;
        }
    } elseif ($_POST['accion'] == 'eliminar') {
        $numero_fact = $_POST['numero_fact'];

        // Llamar al procedimiento almacenado para eliminar factura
        $sql = "CALL EliminarFactura('$numero_fact')";
        if ($conexion->query($sql) === TRUE) {
            echo "Factura eliminada exitosamente.";
        } else {
            echo "Error al eliminar factura: " . $conexion->error;
        }
    } elseif ($_POST['accion'] == 'modificar') {
        // Obtener los datos del formulario
        $numero_fact = $_POST['numero_fact'];
        $total = $_POST['total'];
        $fecha = $_POST['fecha'];
        $id_empleado = $_POST['id_empleado']; // Asegúrate de que este campo esté en el formulario
        $ruc = $_POST['ruc'];

        // Llamar al procedimiento almacenado para modificar factura
        $sql = "CALL ActualizarFactura('$numero_fact', '$fecha', '$total', '$id_empleado', '$ruc')";
        if ($conexion->query($sql) === TRUE) {
            echo "Factura modificada exitosamente.";
        } else {
            echo "Error al modificar factura: " . $conexion->error;
        }
    }
}

// Obtener todas las facturas para mostrarlas
$sql = "SELECT * FROM Factura";
$facturas = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Facturas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Administrar Facturas</h1>

    <!-- Formulario para agregar factura -->
    <form method="post">
        <h2>Agregar Factura</h2>
        <label for="numero_fact">Número de Factura</label>
        <input type="text" name="numero_fact" required>

        <label for="total">Total</label>
        <input type="text" name="total" required>

        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" required>

        <label for="id_empleado">ID Empleado</label>
        <input type="text" name="id_empleado" required>

        <label for="ruc">RUC</label>
        <input type="text" name="ruc" required>

        <input type="hidden" name="accion" value="agregar">
        <button type="submit">Agregar Factura</button>
    </form>

    <!-- Formulario para eliminar factura -->
    <form method="post">
        <h2>Eliminar Factura</h2>
        <label for="numero_fact">Número de Factura</label>
        <input type="text" name="numero_fact" required>

        <input type="hidden" name="accion" value="eliminar">
        <button type="submit">Eliminar Factura</button>
    </form>

    <!-- Formulario para modificar factura -->
    <form method="post">
        <h2>Modificar Factura</h2>
        <label for="numero_fact">Número de Factura</label>
        <input type="text" name="numero_fact" required>

        <label for="total">Nuevo Total</label>
        <input type="text" name="total" required>

        <label for="fecha">Nueva Fecha</label>
        <input type="date" name="fecha" required>

        <label for="id_empleado">ID Empleado</label>
        <input type="text" name="id_empleado" required>

        <label for="ruc">Nuevo RUC</label>
        <input type="text" name="ruc" required>

        <input type="hidden" name="accion" value="modificar">
        <button type="submit">Modificar Factura</button>
    </form>

    <h2>Listado de Facturas</h2>
    <table>
        <tr>
            <th>Número de Factura</th>
            <th>Total</th>
            <th>Fecha</th>
            <th>ID Empleado</th>
            <th>RUC</th>
        </tr>

        <?php while ($factura = $facturas->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $factura['numero_fact']; ?></td>
                <td><?php echo $factura['total']; ?></td>
                <td><?php echo $factura['fecha']; ?></td>
                <td><?php echo $factura['id_empleado']; ?></td>
                <td><?php echo $factura['RUC']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
