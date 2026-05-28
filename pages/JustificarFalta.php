<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Justificar Falta</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/justificaciones.css?v=1">

</head>

<body>

<div class="justificacion-container">

    <div class="justificacion-card">

        <div class="justificacion-header">

            <div>
                <h1>Justificar falta</h1>

                <p>
                    Envía una solicitud con evidencia para justificar una falta.
                </p>
            </div>

            <button
                class="btn-regresar"
                onclick="volverPortal()"
            >
                Volver al portal
            </button>

        </div>

        <div id="alerta" class="alerta"></div>

        <form id="formJustificacion" enctype="multipart/form-data">

            <label for="fecha_falta">
                Fecha de la falta
            </label>

            <input
                type="date"
                id="fecha_falta"
                name="fecha_falta"
                required
            >

            <label for="admin_id">
                Administrador que revisará
            </label>

            <select
                id="admin_id"
                name="admin_id"
                required
            >
                <option value="">Cargando administradores...</option>
            </select>

            <label for="motivo">
                Motivo de la justificación
            </label>

            <textarea
                id="motivo"
                name="motivo"
                placeholder="Ejemplo: Tuve una cita médica y adjunto constancia."
                required
            ></textarea>

            <label for="archivo">
                Comprobante
            </label>

            <input
                type="file"
                id="archivo"
                name="archivo"
                accept=".jpg,.jpeg,.png,.pdf"
                required
            >

            <small>
                Formatos permitidos: JPG, PNG o PDF. Máximo 5 MB.
            </small>

            <button
                type="submit"
                class="btn-enviar"
            >
                Enviar justificación
            </button>

        </form>

    </div>

</div>

<script src="../js/justificarFalta.js?v=1"></script>

</body>
</html>