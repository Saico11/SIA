<?php
include 'conexion.php';

// Insertar Negocio
if (isset($_POST['insertar_negocio'])) {
    $ruc = $_POST['ruc'];
    $razon_social = $_POST['razon_social'];

    $query = "CALL CrearNegocio('$ruc', '$razon_social')";
    if (mysqli_query($conexion, $query)) {
        echo "Negocio agregado correctamente.";
    } else {
        echo "Error al agregar negocio: " . mysqli_error($conexion);
    }
}

// Consultar todos los Negocios
$negocios = null;
if (isset($_POST['consultar_negocios'])) {
    $query = "CALL LeerNegocios()";
    $result = mysqli_query($conexion, $query);
    if ($result) {
        $negocios = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

// Consultar Negocio por RUC
$negocio = null;
if (isset($_POST['consultar_negocio'])) {
    $ruc = $_POST['ruc'];
    $query = "CALL LeerNegocioPorRUC('$ruc')";
    $result = mysqli_query($conexion, $query);
    if ($result) {
        $negocio = mysqli_fetch_assoc($result);
    }
}

// Actualizar Negocio
if (isset($_POST['actualizar_negocio'])) {
    $ruc = $_POST['ruc'];
    $razon_social = $_POST['razon_social'];

    $query = "CALL ActualizarNegocio('$ruc', '$razon_social')";
    if (mysqli_query($conexion, $query)) {
        echo "Negocio actualizado correctamente.";
    } else {
        echo "Error al actualizar negocio: " . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Negocios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Administrar Negocios</h1>

    <!-- Formulario de insertar negocio -->
    <form method="POST">
        <h2>Agregar Negocio</h2>
        <input type="text" name="ruc" placeholder="RUC" required><br>
        <input type="text" name="razon_social" placeholder="Razón Social" required><br>
        <input type="submit" name="insertar_negocio" value="Insertar Negocio">
    </form>

    <!-- Formulario de actualizar negocio -->
    <form method="POST">
        <h2>Actualizar Negocio</h2>
        <input type="text" name="ruc" placeholder="RUC" required><br>
        <input type="text" name="razon_social" placeholder="Razón Social" required><br>
        <input type="submit" name="actualizar_negocio" value="Actualizar Negocio">
    </form>

    <!-- Formulario de consultar negocio por RUC -->
    <form method="POST">
        <h2>Consultar Negocio por RUC</h2>
        <input type="text" name="ruc" placeholder="RUC" required><br>
        <input type="submit" name="consultar_negocio" value="Consultar Negocio">
    </form>

    <!-- Formulario de consultar todos los negocios -->
    <form method="POST">
        <h2>Consultar Todos los Negocios</h2>
        <input type="submit" name="consultar_negocios" value="Consultar Negocios">
    </form>

    <?php if ($negocios): ?>
        <h3>Listado de Negocios</h3>
        <ul>
            <?php foreach ($negocios as $negocio_item): ?>
                <li>
                    RUC: <?= $negocio_item['RUC'] ?>, Razón Social: <?= $negocio_item['razon_social'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($negocio): ?>
        <h3>Datos del Negocio</h3>
        <p>RUC: <?= $negocio['RUC'] ?></p>
        <p>Razón Social: <?= $negocio['razon_social'] ?></p>
    <?php endif; ?>
</body>
</html>
