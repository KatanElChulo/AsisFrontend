const API_MI_NOMINA = "../../AsisBackend/api/nominas/mi_nomina.php";

const usuario = JSON.parse(localStorage.getItem("usuario"));

if (!usuario) {
    window.location.href = "../index.php";
}

document.addEventListener("DOMContentLoaded", function () {
    cargarMiNomina();
});

async function cargarMiNomina() {
    try {
        const response = await fetch(API_MI_NOMINA, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                empleado_id: usuario.id
            })
        });

        const texto = await response.text();

        console.log("Respuesta mi_nomina.php:", texto);

        const result = JSON.parse(texto);

        if (!result.success) {
            mostrarAlerta(result.message || "Error al cargar nómina", "error");
            return;
        }

        pintarNomina(result.data);

    } catch (error) {
        console.error(error);
        mostrarAlerta("Error al conectar con el servidor", "error");
    }
}

function pintarNomina(data) {
    document.getElementById("nombreEmpleado").textContent = data.empleado;

    document.getElementById("rangoSemana").textContent =
        `Semana del ${formatearFecha(data.fecha_inicio)} al ${formatearFecha(data.fecha_fin)}`;

    document.getElementById("diasTrabajados").textContent =
        data.dias_trabajados;

    document.getElementById("faltas").textContent =
        data.faltas;

    document.getElementById("retardos").textContent =
        data.retardos ?? 0;

    document.getElementById("sueldoDiario").textContent =
        `$${Number(data.sueldo_diario).toFixed(2)}`;

    document.getElementById("totalPago").textContent =
        `$${Number(data.total_pago).toFixed(2)}`;

    const estado =
        document.getElementById("estadoNomina");

    if (Number(data.faltas) > 0) {
        estado.textContent = "Con faltas";
        estado.className = "estado-nomina estado-rojo";
    } else if (Number(data.retardos) > 0) {
        estado.textContent = "Con retardos";
        estado.className = "estado-nomina estado-amarillo";
    } else if (Number(data.dias_trabajados) > 0) {
        estado.textContent = "Al corriente";
        estado.className = "estado-nomina estado-verde";
    } else {
        estado.textContent = "Sin asistencias registradas";
        estado.className = "estado-nomina estado-rojo";
    }

    pintarDetalle(data.detalle_semana || []);
}

function pintarDetalle(detalle) {
    const tabla = document.getElementById("tablaDetalle");

    tabla.innerHTML = "";

    if (detalle.length === 0) {
        tabla.innerHTML = `
            <tr>
                <td colspan="4">No hay detalle disponible.</td>
            </tr>
        `;
        return;
    }

    detalle.forEach(item => {
        const fila = document.createElement("tr");

        let clase = "badge gris";

        if (item.estado === "Asistencia") {
            clase = "badge verde";
        } else if (item.estado === "Retardo") {
            clase = "badge amarillo";
        } else if (item.estado === "Falta") {
            clase = "badge rojo";
        } else if (item.estado === "Entrada sin salida") {
            clase = "badge amarillo";
        }

        fila.innerHTML = `
            <td>${formatearFecha(item.fecha)}</td>
            <td>${item.hora_entrada || "--"}</td>
            <td>${item.hora_salida || "--"}</td>
            <td>
                <span class="${clase}">
                    ${item.estado}
                </span>
            </td>
        `;

        tabla.appendChild(fila);
    });
}

function volverPortal() {
    window.location.href = "Portal.php";
}

function mostrarAlerta(mensaje, tipo = "success") {
    const alerta = document.getElementById("alerta");

    alerta.textContent = mensaje;
    alerta.style.display = "block";
    alerta.style.background = tipo === "success" ? "#27ae60" : "#c0392b";

    setTimeout(() => {
        alerta.style.display = "none";
    }, 3500);
}

function formatearFecha(fecha) {
    if (!fecha) return "";

    const partes = fecha.split("-");

    if (partes.length !== 3) return fecha;

    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}