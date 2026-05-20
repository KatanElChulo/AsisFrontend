const API_URL = "http://localhost/AsisProyecto/AsisBackend/api/nominas/index.php";
const API_GENERAR = "http://localhost/AsisProyecto/AsisBackend/api/nominas/generar.php";

const tablaNominas = document.getElementById("tablaNominas");
const formNomina = document.getElementById("formNomina");
const modalNomina = document.getElementById("modalNomina");
const tituloModal = document.getElementById("tituloModal");
const busqueda = document.getElementById("busqueda");

const modalGenerar = document.getElementById("modalGenerar");
const formGenerarNomina = document.getElementById("formGenerarNomina");

let nominas = [];

document.addEventListener("DOMContentLoaded", function () {
    listarNominas();
});

async function listarNominas() {
    try {
        const response = await fetch(API_URL);

        const text = await response.text();
        console.log("Respuesta listar nóminas:", text);

        const result = JSON.parse(text);

        if (result.success) {
            nominas = result.data;
            pintarTabla(nominas);
        } else {
            mostrarAlerta(result.message, "error");
        }

    } catch (error) {
        console.error("Error al cargar nóminas:", error);
        mostrarAlerta("Error al cargar nóminas", "error");
    }
}

function pintarTabla(data) {
    tablaNominas.innerHTML = "";

    if (!data || data.length === 0) {
        tablaNominas.innerHTML = `
            <tr>
                <td colspan="10">No hay nóminas registradas</td>
            </tr>
        `;
        return;
    }

    data.forEach(nomina => {
        const nombreCompleto = `
            ${nomina.nombre ?? ""}
            ${nomina.apellido_paterno ?? ""}
            ${nomina.apellido_materno ?? ""}
        `.trim();

        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${nomina.id}</td>
            <td>${nombreCompleto || "Empleado ID: " + nomina.empleado_id}</td>
            <td>${nomina.fecha_inicio}</td>
            <td>${nomina.fecha_fin}</td>
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

        tablaNominas.appendChild(fila);
    });
}

/* ============================
   MODAL NÓMINA MANUAL
============================ */

function abrirModal() {
    formNomina.reset();

    document.getElementById("nomina_id").value = "";
    tituloModal.textContent = "Nueva Nómina Manual";

    modalNomina.style.display = "flex";
}

function cerrarModal() {
    modalNomina.style.display = "none";
}

function editarNomina(nomina) {
    tituloModal.textContent = "Editar Nómina";

    document.getElementById("nomina_id").value = nomina.id;
    document.getElementById("empleado_id").value = nomina.empleado_id;
    document.getElementById("fecha_inicio").value = nomina.fecha_inicio;
    document.getElementById("fecha_fin").value = nomina.fecha_fin;
    document.getElementById("dias_trabajados").value = nomina.dias_trabajados;
    document.getElementById("faltas").value = nomina.faltas;
    document.getElementById("sueldo_diario").value = nomina.sueldo_diario;

    modalNomina.style.display = "flex";
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

    const metodo = id ? "PUT" : "POST";
    const url = id ? `${API_URL}?id=${id}` : API_URL;

    try {
        const response = await fetch(url, {
            method: metodo,
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const text = await response.text();
        console.log("Respuesta guardar nómina:", text);

        const result = JSON.parse(text);

        mostrarAlerta(result.message, result.success ? "success" : "error");

        if (result.success) {
            cerrarModal();
            listarNominas();
        }

    } catch (error) {
        console.error("Error al guardar nómina:", error);
        mostrarAlerta("Error al guardar nómina", "error");
    }
});

async function eliminarNomina(id) {
    const confirmar = confirm("¿Seguro que quieres eliminar esta nómina?");

    if (!confirmar) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}?id=${id}`, {
            method: "DELETE"
        });

        const text = await response.text();
        console.log("Respuesta eliminar nómina:", text);

        const result = JSON.parse(text);

        mostrarAlerta(result.message, result.success ? "success" : "error");

        if (result.success) {
            listarNominas();
        }

    } catch (error) {
        console.error("Error al eliminar nómina:", error);
        mostrarAlerta("Error al eliminar nómina", "error");
    }
}

/* ============================
   MODAL GENERAR AUTOMÁTICA
============================ */

function abrirModalGenerar() {
    formGenerarNomina.reset();
    modalGenerar.style.display = "flex";
}

function cerrarModalGenerar() {
    modalGenerar.style.display = "none";
}

formGenerarNomina.addEventListener("submit", async function (e) {
    e.preventDefault();

    const fechaInicio = document.getElementById("generar_fecha_inicio").value;
    const fechaFin = document.getElementById("generar_fecha_fin").value;

    if (fechaInicio > fechaFin) {
        mostrarAlerta("La fecha de inicio no puede ser mayor a la fecha fin", "error");
        return;
    }

    const data = {
        empleado_id: document.getElementById("generar_empleado_id").value,
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin
    };

    try {
        const response = await fetch(API_GENERAR, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const text = await response.text();
        console.log("Respuesta generar nómina:", text);

        const result = JSON.parse(text);

        mostrarAlerta(result.message, result.success ? "success" : "error");

        if (result.success) {
            cerrarModalGenerar();
            listarNominas();
        }

    } catch (error) {
        console.error("Error al generar nómina:", error);
        mostrarAlerta("Error al generar nómina", "error");
    }
});

/* ============================
   BUSCADOR
============================ */

busqueda.addEventListener("input", function () {
    const texto = busqueda.value.toLowerCase();

    const filtradas = nominas.filter(nomina => {
        const empleado = `
            ${nomina.nombre ?? ""}
            ${nomina.apellido_paterno ?? ""}
            ${nomina.apellido_materno ?? ""}
        `.toLowerCase();

        return (
            empleado.includes(texto) ||
            String(nomina.id).includes(texto) ||
            String(nomina.empleado_id).includes(texto) ||
            String(nomina.fecha_inicio).includes(texto) ||
            String(nomina.fecha_fin).includes(texto)
        );
    });

    pintarTabla(filtradas);
});

/* ============================
   ALERTAS
============================ */

function mostrarAlerta(mensaje, tipo = "success") {
    const alerta = document.getElementById("alerta");

    alerta.textContent = mensaje;
    alerta.style.display = "block";
    alerta.style.background = tipo === "success" ? "#27ae60" : "#c0392b";

    setTimeout(() => {
        alerta.style.display = "none";
    }, 3000);
}

/* ============================
   CERRAR MODALES AL DAR CLIC FUERA
============================ */

window.addEventListener("click", function (e) {
    if (e.target === modalNomina) {
        cerrarModal();
    }

    if (e.target === modalGenerar) {
        cerrarModalGenerar();
    }
});