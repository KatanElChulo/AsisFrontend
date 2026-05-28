const API_CREAR_JUSTIFICACION =
    "../../AsisBackend/api/justificaciones/crear.php";

const API_ADMINS =
    "../../AsisBackend/api/justificaciones/admins.php";

const usuario =
    JSON.parse(localStorage.getItem("usuario"));

if (!usuario) {
    window.location.href = "../index.php";
}

document.addEventListener("DOMContentLoaded", function () {
    cargarAdmins();

    const form =
        document.getElementById("formJustificacion");

    form.addEventListener("submit", enviarJustificacion);
});

async function cargarAdmins() {
    try {
        const response =
            await fetch(API_ADMINS);

        const texto =
            await response.text();

        console.log("Respuesta admins.php:", texto);

        const result =
            JSON.parse(texto);

        const select =
            document.getElementById("admin_id");

        if (!result.success) {
            select.innerHTML =
                `<option value="">Error al cargar administradores</option>`;

            mostrarAlerta(
                result.message || "Error al cargar administradores",
                "error"
            );

            return;
        }

        if (!result.data || result.data.length === 0) {
            select.innerHTML =
                `<option value="">No hay administradores disponibles</option>`;

            return;
        }

        let html =
            `<option value="">Selecciona un administrador</option>`;

        result.data.forEach(admin => {
            html += `
                <option value="${admin.id}">
                    ${admin.nombre_completo}
                </option>
            `;
        });

        select.innerHTML = html;

    } catch (error) {
        console.error(error);

        mostrarAlerta(
            "Error al conectar con administradores",
            "error"
        );
    }
}

async function enviarJustificacion(e) {
    e.preventDefault();

    const fechaFalta =
        document.getElementById("fecha_falta").value;

    const adminId =
        document.getElementById("admin_id").value;

    const motivo =
        document.getElementById("motivo").value.trim();

    const archivo =
        document.getElementById("archivo").files[0];

    if (!fechaFalta || !adminId || !motivo || !archivo) {
        mostrarAlerta(
            "Completa todos los campos",
            "error"
        );

        return;
    }

    const formData =
        new FormData();

    formData.append("empleado_id", usuario.id);
    formData.append("admin_id", adminId);
    formData.append("fecha_falta", fechaFalta);
    formData.append("motivo", motivo);
    formData.append("archivo", archivo);

    try {
        const response =
            await fetch(API_CREAR_JUSTIFICACION, {
                method: "POST",
                body: formData
            });

        const texto =
            await response.text();

        console.log("Respuesta crear.php:", texto);

        const result =
            JSON.parse(texto);

        if (result.success) {
            mostrarAlerta(
                result.message,
                "success"
            );

            document.getElementById("formJustificacion").reset();

            setTimeout(() => {
                window.location.href = "Portal.php";
            }, 2500);

        } else {
            mostrarAlerta(
                result.message || "Error al enviar justificación",
                "error"
            );
        }

    } catch (error) {
        console.error(error);

        mostrarAlerta(
            "Error al enviar justificación",
            "error"
        );
    }
}

function volverPortal() {
    window.location.href = "Portal.php";
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