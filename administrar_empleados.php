<?php
include 'conexion.php';

// Insertar Empleado
if (isset($_POST['insertar_empleado'])) {
    $id_empleado = $_POST['id_empleado'];
    $rol = $_POST['rol'];
    $nombres = $_POST['nombres'];
    $apellido_pat = $_POST['apellido_pat'];
    $apellido_mat = $_POST['apellido_mat'];

    $query = "CALL InsertarEmpleado('$id_empleado', '$rol', '$nombres', '$apellido_pat', '$apellido_mat')";
    if (mysqli_query($conexion, $query)) {
        echo "Empleado agregado correctamente.";
    } else {
        echo "Error al agregar empleado: " . mysqli_error($conexion);
    }
}

// Actualizar Empleado
if (isset($_POST['actualizar_empleado'])) {
    $id_empleado = $_POST['id_empleado'];
    $rol = $_POST['rol'];
    $nombres = $_POST['nombres'];
    $apellido_pat = $_POST['apellido_pat'];
    $apellido_mat = $_POST['apellido_mat'];

    $query = "CALL ActualizarEmpleado('$id_empleado', '$rol', '$nombres', '$apellido_pat', '$apellido_mat')";
    if (mysqli_query($conexion, $query)) {
        echo "Empleado actualizado correctamente.";
    } else {
        echo "Error al actualizar empleado: " . mysqli_error($conexion);
    }
}

// Eliminar Empleado
if (isset($_POST['eliminar_empleado'])) {
    $id_empleado = $_POST['id_empleado'];

    $query = "CALL EliminarEmpleado('$id_empleado')";
    if (mysqli_query($conexion, $query)) {
        echo "Empleado eliminado correctamente.";
    } else {
        echo "Error al eliminar empleado: " . mysqli_error($conexion);
    }
}

// Consultar Empleado por ID
$empleado = null;
if (isset($_POST['consultar_empleado'])) {
    $id_empleado = $_POST['id_empleado'];
    $query = "CALL ConsultarEmpleadoPorId('$id_empleado')";
    $result = mysqli_query($conexion, $query);
    if ($result) {
        $empleado = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Empleados</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Administrar Empleados</h1>

    <!-- Formulario de insertar empleado -->
    <form method="POST">
        <h2>Agregar Empleado</h2>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <input type="text" name="rol" placeholder="Rol" required><br>
        <input type="text" name="nombres" placeholder="Nombres" required><br>
        <input type="text" name="apellido_pat" placeholder="Apellido Paterno" required><br>
        <input type="text" name="apellido_mat" placeholder="Apellido Materno" required><br>
        <button name='insertar_empleado'>Insertar Empleado</button>
    </form>

    <!-- Formulario de actualizar empleado -->
    <form method="POST">
        <h2>Actualizar Empleado</h2>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <input type="text" name="rol" placeholder="Rol" required><br>
        <input type="text" name="nombres" placeholder="Nombres" required><br>
        <input type="text" name="apellido_pat" placeholder="Apellido Paterno" required><br>
        <input type="text" name="apellido_mat" placeholder="Apellido Materno" required><br>
        <button name='actualizar_empleado'>Actualizar Empleado</button>
    </form>

    <!-- Formulario de eliminar empleado -->
    <form method="POST">
        <h2>Eliminar Empleado</h2>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <button name='eliminar_empleado'>Eliminar Empleado</button>
    </form>

    <!-- Formulario de consultar empleado -->
    <form method="POST">
        <h2>Consultar Empleado por ID</h2>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <button name='consultar_empleado'>Consultar Empleado</button>
    </form>

    <?php if ($empleado): ?>
        <h3>Datos del Empleado</h3>
        <p>ID: <?= $empleado['id_empleado'] ?></p>
        <p>Rol: <?= $empleado['rol'] ?></p>
        <p>Nombres: <?= $empleado['nombres'] ?></p>
        <p>Apellido Paterno: <?= $empleado['apellido_pat'] ?></p>
        <p>Apellido Materno: <?= $empleado['apellido_mat'] ?></p>
    <?php endif; ?>
</body>
</html>
