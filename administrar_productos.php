<?php
include 'conexion.php';

// Insertar Producto
if (isset($_POST['insertar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $id_empleado = $_POST['id_empleado'];
    $id_subgrupo = $_POST['id_subgrupo'];
    $nombre = $_POST['nombre'];
    $unidad = $_POST['unidad'];
    $stock = $_POST['stock'];
    $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? $_POST["fecha_vencimiento"] : null;
    $fecha_produccion = isset($_POST["fecha_produccion"]) ? $_POST["fecha_produccion"] : null;
    $fecha_administracion = isset($_POST["fecha_administracion"]) ? $_POST["fecha_administracion"] : null;

    if ($fecha_vencimiento) {
        list($anio_vencimiento, $mes_vencimiento, $dia_vencimiento) = explode('-', $fecha_vencimiento);
    } else {
        $anio_vencimiento = $mes_vencimiento = $dia_vencimiento = null;
    }
    
    if ($fecha_produccion) {
        list($anio_produccion, $mes_produccion, $dia_produccion) = explode('-', $fecha_produccion);
    } else {
        $anio_produccion = $mes_produccion = $dia_produccion = null;
    }
    
    if ($fecha_administracion) {
        list($anio_administracion, $mes_administracion, $dia_administracion) = explode('-', $fecha_administracion);
    } else {
        $anio_administracion = $mes_administracion = $dia_administracion = null;
    }

    $query = "CALL InsertarProducto('$id_producto', '$id_empleado', '$id_subgrupo', '$nombre', '$unidad', '$stock', 
            '$anio_vencimiento', '$mes_vencimiento', '$dia_vencimiento', '$anio_produccion', '$mes_produccion', '$dia_produccion',
            '$anio_administracion', '$mes_administracion', '$dia_administracion')";
    if (mysqli_query($conexion, $query)) {
        echo "Producto agregado correctamente.";
    } else {
        echo "Error al agregar producto: " . mysqli_error($conexion);
    }
}

// Actualizar Producto
if (isset($_POST['actualizar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $id_empleado = $_POST['id_empleado'];
    $id_subgrupo = $_POST['id_subgrupo'];
    $nombre = $_POST['nombre'];
    $unidad = $_POST['unidad'];
    $stock = $_POST['stock'];

    $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? $_POST["fecha_vencimiento"] : null;
    $fecha_produccion = isset($_POST["fecha_produccion"]) ? $_POST["fecha_produccion"] : null;
    $fecha_administracion = isset($_POST["fecha_administracion"]) ? $_POST["fecha_administracion"] : null;

    if ($fecha_vencimiento) {
        list($anio_vencimiento, $mes_vencimiento, $dia_vencimiento) = explode('-', $fecha_vencimiento);
    } else {
        $anio_vencimiento = $mes_vencimiento = $dia_vencimiento = null;
    }

    if ($fecha_produccion) {
        list($anio_produccion, $mes_produccion, $dia_produccion) = explode('-', $fecha_produccion);
    } else {
        $anio_produccion = $mes_produccion = $dia_produccion = null;
    }

    if ($fecha_administracion) {
        list($anio_administracion, $mes_administracion, $dia_administracion) = explode('-', $fecha_administracion);
    } else {
        $anio_administracion = $mes_administracion = $dia_administracion = null;
    }

    $query = "CALL ActualizarProducto('$id_producto', '$id_empleado', '$id_subgrupo', '$nombre', '$unidad', '$stock', 
            '$anio_vencimiento', '$mes_vencimiento', '$dia_vencimiento', '$anio_produccion', '$mes_produccion', '$dia_produccion',
            '$anio_administracion', '$mes_administracion', '$dia_administracion')";
    if (mysqli_query($conexion, $query)) {
        echo "Producto actualizado correctamente.";
    } else {
        echo "Error al actualizar producto: " . mysqli_error($conexion);
    }
}

// Eliminar Producto
if (isset($_POST['eliminar_producto'])) {
    $id_producto = $_POST['id_producto'];

    $query = "CALL EliminarProducto('$id_producto')";
    if (mysqli_query($conexion, $query)) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar producto: " . mysqli_error($conexion);
    }
}

// Consultar Producto por ID
$producto = null;
if (isset($_POST['consultar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $query = "CALL ConsultarProductoPorId('$id_producto')";
    $result = mysqli_query($conexion, $query);
    if ($result) {
        $producto = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Administrar Productos</h1>

    <!-- Formulario de insertar producto -->
    <form method="POST">
        <h2>Agregar Producto</h2>
        <input type="text" name="id_producto" placeholder="ID Producto" required><br>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <input type="text" name="id_subgrupo" placeholder="ID Subgrupo" required><br>
        <input type="text" name="nombre" placeholder="Nombre Producto" required><br>
        <input type="text" name="unidad" placeholder="Unidad" required><br>
        <input type="number" name="stock" placeholder="Stock" required><br>
        <label for="fecha_vencimiento">Fecha Vencimiento</label>
        <input type="date" name="fecha_vencimiento" placeholder="Fecha Vencimiento" title="Fecha Vencimiento" required><br>
        <label for="fecha_vencimiento">Fecha Producción</label>
        <input type="date" name="fecha_produccion" placeholder="Fecha Producción" title="Fecha Producción"  required><br>
        <label for="fecha_vencimiento">Fecha Administración</label>
        <input type="date" name="fecha_administracion" placeholder="Fecha Administración" title="Fecha Administración"  required><br>
        <button type="submit" name="insertar_producto">Insertar Producto</button>
    </form>

    <!-- Formulario de actualizar producto -->
    <form method="POST">
        <h2>Actualizar Producto</h2>
        <input type="text" name="id_producto" placeholder="ID Producto" required><br>
        <input type="text" name="id_empleado" placeholder="ID Empleado" required><br>
        <input type="text" name="id_subgrupo" placeholder="ID Subgrupo" required><br>
        <input type="text" name="nombre" placeholder="Nombre Producto" required><br>
        <input type="text" name="unidad" placeholder="Unidad" required><br>
        <input type="number" name="stock" placeholder="Stock" required><br>
        <button type="submit" name="actualizar_producto">Actualizar Producto</button>
    </form>

    <!-- Formulario de eliminar producto -->
    <form method="POST">
        <h2>Eliminar Producto</h2>
        <input type="text" name="id_producto" placeholder="ID Producto" required><br>
        <button type="submit" name="eliminar_producto">Eliminar Producto</button>
    </form>

    <!-- Formulario de consultar producto -->
    <form method="POST">
        <h2>Consultar Producto por ID</h2>
        <input type="text" name="id_producto" placeholder="ID Producto" required><br>
        <button type="submit" name="consultar_producto">Consultar Producto</button>
    </form>

    <?php if ($producto): ?>
        <h3>Datos del Producto</h3>
        <p>ID: <?= $producto['id_producto'] ?></p>
        <p>Nombre: <?= $producto['nombre'] ?></p>
        <p>Stock: <?= $producto['stock'] ?></p>
        <p>Unidad: <?= $producto['unidad'] ?></p>
    <?php endif; ?>
</body>
</html>
