<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nóminas</title>
    <link rel="stylesheet" href="../css/nominas.css">
</head>
<body>

    <header class="encabezado">
        <div>
            <h1>Nóminas</h1>
            <p>Control semanal automático de pagos, faltas y días trabajados</p>
        </div>

        <button class="btn btn-cerrar-semana" onclick="cerrarSemana()">
            Cerrar semana y guardar nóminas
        </button>
        <a href="Dashboard.php" class="btn-regresar">
                    Regresar al Dashboard
                </a>
    </header>

    <div id="alerta" class="alerta"></div>

    <!-- RESUMEN SEMANA ACTUAL -->
    <section class="panel-resumen">
        <div class="card-resumen">
            <span>Semana actual</span>
            <strong id="rangoSemana">Cargando...</strong>
        </div>

        <div class="card-resumen">
            <span>Días hábiles</span>
            <strong id="diasHabiles">0</strong>
        </div>

        <div class="card-resumen">
            <span>Días transcurridos</span>
            <strong id="diasTranscurridos">0</strong>
        </div>

        <div class="card-resumen">
            <span>Actualización</span>
            <strong id="ultimaActualizacion">--:--:--</strong>
        </div>
    </section>

    <!-- NÓMINA ACTUAL EN VIVO -->
    <section class="seccion">
        <div class="seccion-header">
            <div>
                <h2>Nómina semana actual en vivo</h2>
                <p>Se calcula automáticamente con las asistencias registradas de lunes a viernes.</p>
            </div>

            <input type="text" id="busquedaActual" placeholder="Buscar empleado...">
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID Empleado</th>
                        <th>Empleado</th>
                        <th>Días Trabajados</th>
                        <th>Faltas</th>
                        <th>Sueldo Diario</th>
                        <th>Pago Actual</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody id="tablaNominaActual">
                    <tr>
                        <td colspan="7">Cargando nómina actual...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- HISTORIAL DE NÓMINAS CERRADAS -->
    <section class="seccion">
        <div class="seccion-header">
            <div>
                <h2>Historial de nóminas cerradas</h2>
                <p>Aquí aparecen las semanas guardadas al cerrar nómina.</p>
            </div>

            <input type="text" id="busquedaHistorial" placeholder="Buscar historial...">
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID Nómina</th>
                        <th>Empleado</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Días Trabajados</th>
                        <th>Faltas</th>
                        <th>Sueldo Diario</th>
                        <th>Total Pago</th>
                        <th>Generada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tablaHistorialNominas">
                    <tr>
                        <td colspan="10">Cargando historial...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- MODAL EDITAR NÓMINA HISTORIAL -->
    <div id="modalNomina" class="modal">
        <div class="modal-contenido">
            <h2>Editar Nómina</h2>

            <form id="formNomina">
                <input type="hidden" id="nomina_id">

                <label>Empleado ID</label>
                <input type="number" id="empleado_id" min="1" required>

                <label>Fecha inicio</label>
                <input type="date" id="fecha_inicio" required>

                <label>Fecha fin</label>
                <input type="date" id="fecha_fin" required>

                <label>Días trabajados</label>
                <input type="number" id="dias_trabajados" min="0" required>

                <label>Faltas</label>
                <input type="number" id="faltas" min="0" required>

                <label>Sueldo diario</label>
                <input type="number" step="0.01" id="sueldo_diario" min="0" required>

                <div class="modal-botones">
                    <button type="submit" class="btn btn-guardar">Guardar cambios</button>
                    <button type="button" class="btn btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/nominas.js"></script>
</body>
</html>