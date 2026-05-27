<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Nómina Semanal</title>
    <link rel="stylesheet" href="../css/miNomina.css">
</head>
<body>

<div class="contenedor">

    <div class="header">
        <div>
            <h1>Mi nómina semanal</h1>
            <p>Consulta tus días trabajados, faltas y pago acumulado de la semana actual.</p>
        </div>

        <button class="btn-regresar" onclick="volverPortal()">
            Volver al portal
        </button>
    </div>

    <div id="alerta" class="alerta"></div>

    <section class="tarjeta-principal">
        <h2 id="nombreEmpleado">Cargando...</h2>
        <p id="rangoSemana">Semana actual</p>

        <div class="resumen-grid">
            <div class="resumen-card">
                <span>Días trabajados</span>
                <strong id="diasTrabajados">0</strong>
            </div>

            <div class="resumen-card">
                <span>Faltas</span>
                <strong id="faltas">0</strong>
            </div>

            <div class="resumen-card">
                <span>Sueldo diario</span>
                <strong id="sueldoDiario">$0.00</strong>
            </div>

            <div class="resumen-card pago">
                <span>Pago acumulado</span>
                <strong id="totalPago">$0.00</strong>
            </div>
        </div>

        <div id="estadoNomina" class="estado-nomina">
            Estado
        </div>
    </section>

    <section class="tabla-section">
        <h2>Detalle de la semana</h2>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody id="tablaDetalle">
                    <tr>
                        <td colspan="4">Cargando detalle...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</div>

<script src="../js/miNomina.js"></script>

</body>
</html>