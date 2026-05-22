const API_HISTORIAL = "http://localhost/AsisProyecto/AsisBackend/api/nominas/index.php";
const API_SEMANA_ACTUAL = "http://localhost/AsisProyecto/AsisBackend/api/nominas/semana_actual.php";
const API_CERRAR_SEMANA = "http://localhost/AsisProyecto/AsisBackend/api/nominas/cerrar_semana.php";
const tablaNominaActual = document.getElementById("tablaNominaActual");
const tablaHistorialNominas = document.getElementById("tablaHistorialNominas");

const busquedaActual = document.getElementById("busquedaActual");
const busquedaHistorial = document.getElementById("busquedaHistorial");

const rangoSemana = document.getElementById("rangoSemana");
const diasHabiles = document.getElementById("diasHabiles");
const diasTranscurridos = document.getElementById("diasTranscurridos");
const ultimaActualizacion = document.getElementById("ultimaActualizacion");

const modalNomina = document.getElementById("modalNomina");
const formNomina = document.getElementById("formNomina");

let nominaActual = [];
let historialNominas = [];

document.addEventListener("DOMContentLoaded", function () {
    cargarNominaActual();
    cargarHistorialNominas();

    setInterval(() => {
        cargarNominaActual();
    }, 10000);
});

/* ============================
   CARGAR NÓMINA ACTUAL
============================ */

async function cargarNominaActual() {
    try {
        const response = await fetch(API_SEMANA_ACTUAL);
        const text = await response.text();

        console.log("Respuesta nómina actual:", text);

        const result = JSON.parse(text);

        if (!result.success) {
            mostrarAlerta(result.message || "Error al cargar nómina actual", "error");
            return;
        }

        nominaActual = result.data || [];

        rangoSemana.textContent = `${formatearFecha(result.semana.fecha_inicio)} al ${formatearFecha(result.semana.fecha_fin)}`;
        diasHabiles.textContent = result.semana.dias_habiles_semana;
        diasTranscurridos.textContent = result.semana.dias_habiles_transcurridos;
        ultimaActualizacion.textContent = obtenerHoraActual();

        pintarTablaNominaActual(nominaActual);

    } catch (error) {
        console.error("Error al cargar nómina actual:", error);
        mostrarAlerta("Error al cargar nómina actual", "error");
    }
}

function pintarTablaNominaActual(data) {
    tablaNominaActual.innerHTML = "";

    if (!data || data.length === 0) {
        tablaNominaActual.innerHTML = `
            <tr>
                <td colspan="7">No hay empleados activos para mostrar.</td>
            </tr>
        `;
        return;
    }

    data.forEach(item => {
        const fila = document.createElement("tr");

        let estado = "Sin asistencias";
        let claseEstado = "estado-rojo";

        if (Number(item.dias_trabajados) > 0 && Number(item.faltas) === 0) {
            estado = "Al corriente";
            claseEstado = "estado-verde";
        } else if (Number(item.dias_trabajados) > 0 && Number(item.faltas) > 0) {
            estado = "Con faltas";
            claseEstado = "estado-amarillo";
        }

        fila.innerHTML = `
            <td>${item.empleado_id}</td>
            <td>${item.empleado}</td>
            <td>${item.dias_trabajados}</td>
            <td>${item.faltas}</td>
            <td>$${Number(item.sueldo_diario).toFixed(2)}</td>
            <td>$${Number(item.total_pago).toFixed(2)}</td>
            <td>
                <span class="badge ${claseEstado}">
                    ${estado}
                </span>
            </td>
        `;

        tablaNominaActual.appendChild(fila);
    });
}

/* ============================
   CERRAR SEMANA
============================ */

async function cerrarSemana() {
    const confirmar = confirm(
        "¿Seguro que quieres cerrar la semana actual?\n\nEsto guardará o actualizará la nómina de todos los empleados activos."
    );

    if (!confirmar) {
        return;
    }

    try {
        const response = await fetch(API_CERRAR_SEMANA, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            }
        });

        const text = await response.text();

        console.log("Respuesta cerrar semana:", text);

        const result = JSON.parse(text);

        if (result.success) {
            mostrarAlerta(result.message, "success");
            cargarHistorialNominas();
            cargarNominaActual();
        } else {
            mostrarAlerta(result.message || "Error al cerrar semana", "error");
        }

    } catch (error) {
        console.error("Error al cerrar semana:", error);
        mostrarAlerta("Error al cerrar semana", "error");
    }
}

/* ============================
   HISTORIAL DE NÓMINAS
============================ */

async function cargarHistorialNominas() {
    try {
        const response = await fetch(API_HISTORIAL);
        const text = await response.text();

        console.log("Respuesta historial:", text);

        const result = JSON.parse(text);

        if (!result.success) {
            mostrarAlerta(result.message || "Error al cargar historial", "error");
            return;
        }

        historialNominas = result.data || [];
        pintarTablaHistorial(historialNominas);

    } catch (error) {
        console.error("Error al cargar historial:", error);
        mostrarAlerta("Error al cargar historial de nóminas", "error");
    }
}

function pintarTablaHistorial(data) {
    tablaHistorialNominas.innerHTML = "";

    if (!data || data.length === 0) {
        tablaHistorialNominas.innerHTML = `
            <tr>
                <td colspan="10">No hay nóminas cerradas todavía.</td>
            </tr>
        `;
        return;
    }

    data.forEach(nomina => {
        const nombreCompleto = `
            ${nomina.nombre ?? ""}
            ${nomina.apellido_paterno ?? ""}
            ${nomina.apellido_materno ?? ""}
        `.replace(/\s+/g, " ").trim();

        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${nomina.id}</td>
            <td>${nombreCompleto || "Empleado ID: " + nomina.empleado_id}</td>
            <td>${formatearFecha(nomina.fecha_inicio)}</td>
            <td>${formatearFecha(nomina.fecha_fin)}</td>
            <td>${nomina.dias_trabajados}</td>
            <td>${nomina.faltas}</td>
            <td>$${Number(nomina.sueldo_diario).toFixed(2)}</td>
            <td>$${Number(nomina.total_pago).toFixed(2)}</td>
            <td>${nomina.fecha_generacion ?? ""}</td>
            <td>
                <button class="btn btn-editar" onclick='editarNomina(${JSON.stringify(nomina)})'>
                    Editar
                </button>

                <button class="btn btn-eliminar" onclick="eliminarNomina(${nomina.id})">
                    Eliminar
                </button>
            </td>
        `;

        tablaHistorialNominas.appendChild(fila);
    });
}

/* ============================
   EDITAR HISTORIAL
============================ */

function editarNomina(nomina) {
    document.getElementById("nomina_id").value = nomina.id;
    document.getElementById("empleado_id").value = nomina.empleado_id;
    document.getElementById("fecha_inicio").value = nomina.fecha_inicio;
    document.getElementById("fecha_fin").value = nomina.fecha_fin;
    document.getElementById("dias_trabajados").value = nomina.dias_trabajados;
    document.getElementById("faltas").value = nomina.faltas;
    document.getElementById("sueldo_diario").value = nomina.sueldo_diario;

    modalNomina.style.display = "flex";
}

function cerrarModal() {
    modalNomina.style.display = "none";
}

formNomina.addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = document.getElementById("nomina_id").value;

    const fechaInicio = document.getElementById("fecha_inicio").value;
    const fechaFin = document.getElementById("fecha_fin").value;

    if (fechaInicio > fechaFin) {
        mostrarAlerta("La fecha de inicio no puede ser mayor a la fecha fin", "error");
        return;
    }

    const data = {
        empleado_id: document.getElementById("empleado_id").value,
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin,
        dias_trabajados: document.getElementById("dias_trabajados").value,
        faltas: document.getElementById("faltas").value,
        sueldo_diario: document.getElementById("sueldo_diario").value
    };

    try {
        const response = await fetch(`${API_HISTORIAL}?id=${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const text = await response.text();

        console.log("Respuesta editar nómina:", text);

        const result = JSON.parse(text);

        mostrarAlerta(result.message, result.success ? "success" : "error");

        if (result.success) {
            cerrarModal();
            cargarHistorialNominas();
        }

    } catch (error) {
        console.error("Error al editar nómina:", error);
        mostrarAlerta("Error al editar nómina", "error");
    }
});

async function eliminarNomina(id) {
    const confirmar = confirm("¿Seguro que quieres eliminar esta nómina del historial?");

    if (!confirmar) {
        return;
    }

    try {
        const response = await fetch(`${API_HISTORIAL}?id=${id}`, {
            method: "DELETE"
        });

        const text = await response.text();

        console.log("Respuesta eliminar nómina:", text);

        const result = JSON.parse(text);

        mostrarAlerta(result.message, result.success ? "success" : "error");

        if (result.success) {
            cargarHistorialNominas();
        }

    } catch (error) {
        console.error("Error al eliminar nómina:", error);
        mostrarAlerta("Error al eliminar nómina", "error");
    }
}

/* ============================
   BUSCADORES
============================ */

busquedaActual.addEventListener("input", function () {
    const texto = busquedaActual.value.toLowerCase();

    const filtrados = nominaActual.filter(item => {
        return (
            String(item.empleado_id).includes(texto) ||
            String(item.empleado).toLowerCase().includes(texto)
        );
    });

    pintarTablaNominaActual(filtrados);
});

busquedaHistorial.addEventListener("input", function () {
    const texto = busquedaHistorial.value.toLowerCase();

    const filtrados = historialNominas.filter(nomina => {
        const nombreCompleto = `
            ${nomina.nombre ?? ""}
            ${nomina.apellido_paterno ?? ""}
            ${nomina.apellido_materno ?? ""}
        `.toLowerCase();

        return (
            String(nomina.id).includes(texto) ||
            String(nomina.empleado_id).includes(texto) ||
            nombreCompleto.includes(texto) ||
            String(nomina.fecha_inicio).includes(texto) ||
            String(nomina.fecha_fin).includes(texto)
        );
    });

    pintarTablaHistorial(filtrados);
});

/* ============================
   ALERTAS Y UTILIDADES
============================ */

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
    if (!fecha) {
        return "";
    }

    const partes = fecha.split("-");

    if (partes.length !== 3) {
        return fecha;
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function obtenerHoraActual() {
    const ahora = new Date();

    const horas = String(ahora.getHours()).padStart(2, "0");
    const minutos = String(ahora.getMinutes()).padStart(2, "0");
    const segundos = String(ahora.getSeconds()).padStart(2, "0");

    return `${horas}:${minutos}:${segundos}`;
}

window.addEventListener("click", function (e) {
    if (e.target === modalNomina) {
        cerrarModal();
    }
});