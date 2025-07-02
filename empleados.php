<?php
include('conexion.php');

// Obtener almacenes
$query_almacenes = "SELECT ID_Almacen, Nombre FROM Almacen";  // Asegúrate de que esta consulta es correcta
$stmt_almacenes = $conn->prepare($query_almacenes);
$stmt_almacenes->execute();
$almacenes = $stmt_almacenes->fetchAll(PDO::FETCH_ASSOC);  // Asegúrate de que esto esté bien

// Verificar que los datos de los almacenes están correctamente obtenidos
// Puedes eliminar este var_dump en producción


// Insertar empleado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    // Recoger datos del formulario
    $id_almacen = $_POST['id_almacen'];
    $rol = $_POST['rol'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];

    try {
        // Llamar al procedimiento para insertar el empleado
        $query_insert = "CALL insertar_empleado(:id_almacen, :rol, :nombres, :apellidos, :correo, :telefono, :genero)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bindParam(':id_almacen', $id_almacen);
        $stmt_insert->bindParam(':rol', $rol);
        $stmt_insert->bindParam(':nombres', $nombres);
        $stmt_insert->bindParam(':apellidos', $apellidos);
        $stmt_insert->bindParam(':correo', $correo);
        $stmt_insert->bindParam(':telefono', $telefono);
        $stmt_insert->bindParam(':genero', $genero);
        $stmt_insert->execute();

        echo "<div class='alert alert-success'>Empleado agregado exitosamente.</div>";
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
    <title>Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Empleados</h2>

        <!-- Formulario para insertar empleado -->
        <form method="POST">
            <div class="mb-3">
                <label for="id_almacen" class="form-label">Almacén</label>
                <select id="id_almacen" name="id_almacen" class="form-select" required>
                    <option value="">Seleccione un almacén</option>
                    <?php 
                    // Comprobamos si hay almacenes y los mostramos en el select
                    if (!empty($almacenes)) {
                        foreach ($almacenes as $almacen): ?>
                            <option value="<?php echo $almacen['id_almacen']; ?>"><?php echo $almacen['nombre']; ?></option>
                        <?php endforeach; 
                    } else {
                        echo "<option value=''>No hay almacenes disponibles</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <input type="text" class="form-control" id="rol" name="rol" required>
            </div>
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select class="form-control" id="genero" name="genero" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            <button type="submit" name="insertar" class="btn btn-primary">Agregar Empleado</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

