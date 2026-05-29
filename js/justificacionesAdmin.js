const API_LISTAR =
    "../../AsisBackend/api/justificaciones/listar_admin.php";

const API_RESPONDER =
    "../../AsisBackend/api/justificaciones/responder.php";

const usuario =
    JSON.parse(localStorage.getItem("usuario"));

if (!usuario) {
    window.location.href = "../index.php";
}

if (usuario.rol !== "ADMIN") {
    window.location.href = "Portal.php";
}

const tablaJustificaciones =
    document.getElementById("tablaJustificaciones");

const busqueda =
    document.getElementById("busqueda");

const modalRespuesta =
    document.getElementById("modalRespuesta");

const tituloModal =
    document.getElementById("tituloModal");

const textoModal =
    document.getElementById("textoModal");

const observacionAdmin =
    document.getElementById("observacion_admin");

const btnConfirmarRespuesta =
    document.getElementById("btnConfirmarRespuesta");

let justificaciones = [];
let justificacionSeleccionada = null;
let accionSeleccionada = null;

document.addEventListener("DOMContentLoaded", function () {
    cargarJustificaciones();

    busqueda.addEventListener("input", function () {
        filtrarJustificaciones();
    });
});

async function cargarJustificaciones() {
    try {
        const response =
            await fetch(`${API_LISTAR}?admin_id=${usuario.id}`);

        const texto =
            await response.text();

        console.log("Respuesta listar_admin.php:", texto);

        const result =
            JSON.parse(texto);

        if (!result.success) {
            mostrarAlerta(
                result.message || "Error al cargar justificaciones",
                "error"
            );
            return;
        }

        justificaciones =
            result.data || [];

        pintarTabla(justificaciones);

    } catch (error) {
        console.error(error);

        mostrarAlerta(
            "Error al conectar con justificaciones",
            "error"
        );
    }
}

function pintarTabla(data) {
    tablaJustificaciones.innerHTML = "";

    if (!data || data.length === 0) {
        tablaJustificaciones.innerHTML = `
            <tr>
                <td colspan="9">
                    No tienes justificaciones asignadas.
                </td>
            </tr>
        `;
        return;
    }

    data.forEach(item => {
        const fila =
            document.createElement("tr");

        const estadoClase =
            obtenerClaseEstado(item.estado);

        const acciones =
            item.estado === "PENDIENTE"
                ? `
                    <button
                        class="btn-aprobar"
                        onclick="abrirModal(${item.id}, 'APROBAR')"
                    >
                        Aprobar
                    </button>

                    <button
                        class="btn-rechazar"
                        onclick="abrirModal(${item.id}, 'RECHAZAR')"
                    >
                        Rechazar
                    </button>
                `
                : `
                    <span class="texto-revisado">
                        Revisada
                    </span>
                `;

        fila.innerHTML = `
            <td>${item.id}</td>

            <td>${item.empleado}</td>

            <td>${formatearFecha(item.fecha_falta)}</td>

            <td class="td-motivo">${item.motivo}</td>

            <td>
                <a
                    class="btn-archivo"
                    href="${item.archivo_url}"
                    target="_blank"
                >
                    Ver archivo
                </a>
            </td>

            <td>
                <span class="badge ${estadoClase}">
                    ${item.estado}
                </span>
            </td>

            <td>${item.fecha_solicitud ?? ""}</td>

            <td>${item.observacion_admin ?? "--"}</td>

            <td>${acciones}</td>
        `;

        tablaJustificaciones.appendChild(fila);
    });
}

function abrirModal(id, accion) {
    justificacionSeleccionada =
        justificaciones.find(item => Number(item.id) === Number(id));

    accionSeleccionada =
        accion;

    if (!justificacionSeleccionada) {
        mostrarAlerta(
            "No se encontró la justificación",
            "error"
        );
        return;
    }

    observacionAdmin.value = "";

    if (accion === "APROBAR") {
        tituloModal.textContent =
            "Aprobar justificación";

        textoModal.textContent =
            `¿Seguro que quieres aprobar la justificación de ${justificacionSeleccionada.empleado}?`;

        btnConfirmarRespuesta.className =
            "btn-confirmar btn-aprobar-modal";

        btnConfirmarRespuesta.textContent =
            "Aprobar";

    } else {
        tituloModal.textContent =
            "Rechazar justificación";

        textoModal.textContent =
            `¿Seguro que quieres rechazar la justificación de ${justificacionSeleccionada.empleado}?`;

        btnConfirmarRespuesta.className =
            "btn-confirmar btn-rechazar-modal";

        btnConfirmarRespuesta.textContent =
            "Rechazar";
    }

    btnConfirmarRespuesta.onclick =
        responderJustificacion;

    modalRespuesta.style.display =
        "flex";
}

function cerrarModal() {
    modalRespuesta.style.display =
        "none";

    justificacionSeleccionada =
        null;

    accionSeleccionada =
        null;

    observacionAdmin.value =
        "";
}

async function responderJustificacion() {
    if (!justificacionSeleccionada || !accionSeleccionada) {
        mostrarAlerta(
            "No hay justificación seleccionada",
            "error"
        );
        return;
    }

    try {
        const response =
            await fetch(API_RESPONDER, {
                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    id: justificacionSeleccionada.id,
                    admin_id: usuario.id,
                    accion: accionSeleccionada,
                    observacion_admin: observacionAdmin.value.trim()
                })
            });

        const texto =
            await response.text();

        console.log("Respuesta responder.php:", texto);

        const result =
            JSON.parse(texto);

        if (result.success) {
            mostrarAlerta(
                result.message,
                "success"
            );

            cerrarModal();

            cargarJustificaciones();

        } else {
            mostrarAlerta(
                result.message || "Error al responder justificación",
                "error"
            );
        }

    } catch (error) {
        console.error(error);

        mostrarAlerta(
            "Error al responder justificación",
            "error"
        );
    }
}

function filtrarJustificaciones() {
    const texto =
        busqueda.value.toLowerCase();

    const filtradas =
        justificaciones.filter(item => {
            return (
                String(item.id).includes(texto) ||
                String(item.empleado).toLowerCase().includes(texto) ||
                String(item.fecha_falta).includes(texto) ||
                String(item.estado).toLowerCase().includes(texto) ||
                String(item.motivo).toLowerCase().includes(texto)
            );
        });

    pintarTabla(filtradas);
}

function obtenerClaseEstado(estado) {
    if (estado === "APROBADA") {
        return "estado-aprobada";
    }

    if (estado === "RECHAZADA") {
        return "estado-rechazada";
    }

    return "estado-pendiente";
}

function formatearFecha(fecha) {
    if (!fecha) {
        return "";
    }

    const partes =
        fecha.split("-");

    if (partes.length !== 3) {
        return fecha;
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function mostrarAlerta(mensaje, tipo = "success") {
    const alerta =
        document.getElementById("alerta");

    alerta.textContent =
        mensaje;

    alerta.classList.remove("success", "error");
    alerta.classList.add("mostrar", tipo);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 5000);
}

function volverDashboard() {
    window.location.href =
        "Dashboard.php";
}

window.addEventListener("click", function (e) {
    if (e.target === modalRespuesta) {
        cerrarModal();
    }
});