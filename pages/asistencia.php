<?php
require_once "../models/Asistencia.php";

// 🔹 REGISTRAR
if (isset($_POST['qr'])) {
    $mensaje = Asistencia::registrarPorQR($_POST['qr']);
}

// 🔹 ELIMINAR
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    global $conn;
    $conn->query("DELETE FROM asistencias WHERE id = $id");
}

// 🔹 ACTUALIZAR
if (isset($_POST['editar_id'])) {
    $id = (int)$_POST['editar_id'];
    $tipo = $_POST['tipo'];

    global $conn;
    $conn->query("UPDATE asistencias SET tipo = '$tipo' WHERE id = $id");
}

// 🔹 OBTENER DATOS
$asistencias = Asistencia::obtenerTodas();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asistencias</title>
</head>
<body>

<h2>Registro de Asistencia</h2>

<form method="POST">
    <input type="text" name="qr" placeholder="Escanea QR">
    <button type="submit">Registrar</button>
</form>

<?php if (isset($mensaje)) : ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<h3>Lista de asistencias</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Empleado</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($asistencias as $a): ?>
    <tr>
        <td><?php echo $a['id']; ?></td>
        <td><?php echo $a['nombre']; ?></td>
        <td><?php echo $a['fecha']; ?></td>
        <td><?php echo $a['tipo']; ?></td>
        <td>

            <!-- EDITAR -->
            <form method="POST" style="display:inline;">
                <input type="hidden" name="editar_id" value="<?php echo $a['id']; ?>">
                <select name="tipo">
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
                <button type="submit">Editar</button>
            </form>

            <!-- ELIMINAR -->
            <a href="?eliminar=<?php echo $a['id']; ?>">Eliminar</a>

        </td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>