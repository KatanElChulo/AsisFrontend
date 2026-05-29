<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Justificaciones Admin</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/justificacionesAdmin.css?v=1">

</head>

<body>

<div class="admin-container">

    <header class="admin-header">

        <div>
            <h1>Justificaciones de faltas</h1>

            <p>
                Revisa solicitudes enviadas por empleados y aprueba o rechaza sus comprobantes.
            </p>
        </div>

        <button
            class="btn-regresar"
            onclick="volverDashboard()"
        >
            Regresar al Dashboard
        </button>

    </header>

    <div id="alerta" class="alerta"></div>

    <section class="panel-filtros">

        <input
            type="text"
            id="busqueda"
            placeholder="Buscar empleado, fecha o estado..."
        >

        <button
            class="btn-recargar"
            onclick="cargarJustificaciones()"
        >
            Recargar
        </button>

    </section>

    <section class="tabla-card">

        <div class="tabla-contenedor">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Fecha falta</th>
                        <th>Motivo</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Solicitud</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaJustificaciones">

                    <tr>
                        <td colspan="9">Cargando justificaciones...</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</div>

<!-- MODAL RESPUESTA -->

<div id="modalRespuesta" class="modal">

    <div class="modal-contenido">

        <h2 id="tituloModal">Responder justificación</h2>

        <p id="textoModal"></p>

        <label for="observacion_admin">
            Observación del administrador
        </label>

        <textarea
            id="observacion_admin"
            placeholder="Escribe una observación opcional..."
        ></textarea>

        <div class="modal-botones">

            <button
                id="btnConfirmarRespuesta"
                class="btn-confirmar"
            >
                Confirmar
            </button>

            <button
                class="btn-cancelar"
                onclick="cerrarModal()"
            >
                Cancelar
            </button>

        </div>

    </div>

</div>

<script src="../js/justificacionesAdmin.js?v=1"></script>

</body>
</html>