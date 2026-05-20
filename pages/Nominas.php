<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nóminas</title>
    <link rel="stylesheet" href="../css/nominas.css">
</head>
<body>

    <h1>Gestión de Nóminas</h1>

    <div class="acciones-superiores">
        <div class="grupo-botones">
            <button class="btn btn-agregar" onclick="abrirModal()">+ Nueva Nómina Manual</button>
            <button class="btn btn-generar" onclick="abrirModalGenerar()">Generar Nómina Automática</button>
        </div>

        <input type="text" id="busqueda" placeholder="Buscar nómina...">
    </div>

    <div id="alerta" class="alerta"></div>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Días Trabajados</th>
                    <th>Faltas</th>
                    <th>Sueldo Diario</th>
                    <th>Total Pago</th>
                    <th>Fecha Generación</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="tablaNominas">
                <!-- Aquí se cargan las nóminas con JS -->
            </tbody>
        </table>
    </div>

    <!-- MODAL PARA CREAR / EDITAR NÓMINA MANUAL -->
    <div id="modalNomina" class="modal">
        <div class="modal-contenido">
            <h2 id="tituloModal">Nueva Nómina Manual</h2>

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
                    <button type="submit" class="btn btn-guardar">Guardar</button>
                    <button type="button" class="btn btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PARA GENERAR NÓMINA AUTOMÁTICA -->
    <div id="modalGenerar" class="modal">
        <div class="modal-contenido">
            <h2>Generar Nómina Automática</h2>

            <form id="formGenerarNomina">
                <label>Empleado ID</label>
                <input type="number" id="generar_empleado_id" min="1" required>

                <label>Fecha inicio</label>
                <input type="date" id="generar_fecha_inicio" required>

                <label>Fecha fin</label>
                <input type="date" id="generar_fecha_fin" required>

                <div class="info-generar">
                    <p>
                        El sistema calculará automáticamente los días trabajados,
                        faltas, sueldo diario y total a pagar con base en la tabla de asistencias.
                    </p>
                </div>

                <div class="modal-botones">
                    <button type="submit" class="btn btn-guardar">Generar</button>
                    <button type="button" class="btn btn-cancelar" onclick="cerrarModalGenerar()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/nominas.js"></script>
</body>
</html>