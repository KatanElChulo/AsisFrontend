<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Empleados</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/crud.css?v=3">

</head>

<body>

<main class="crud-page">

    <section class="crud-panel">

        <header class="crud-header">

            <div class="crud-title-box">
                <span class="crud-header-icon"></span>

                <div>
                    <h1>Lista de empleados</h1>
                    <p>Gestiona y administra los registros de empleados del sistema.</p>
                </div>
            </div>

            <div class="crud-user-box">
                <div class="crud-avatar-admin">AD</div>
                <div>
                    <strong>Admin</strong>
                    <span>Administrador</span>
                </div>
            </div>

        </header>

        <div class="acciones-superiores">

            <div class="acciones-botones">
                <button class="btn-agregar" onclick="abrirModal()">
                    Agregar empleado
                </button>

                <a href="Dashboard.php" class="btn-regresar">
                    Regresar al Dashboard
                </a>
            </div>

            <div class="busqueda-box">
                <input
                    type="text"
                    id="busqueda"
                    placeholder="Buscar empleado..."
                    onkeyup="buscarEmpleado()"
                >
            </div>

        </div>

        <div class="tabla-responsive">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Sueldo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody id="tablaEmpleados">

                </tbody>

            </table>

        </div>

    </section>

</main>

<div id="alerta" class="alerta"></div>

<div id="modalEmpleado" class="modal">

    <div class="modal-contenido">

        <span class="cerrar" onclick="cerrarModal()">
            &times;
        </span>

        <h2 id="tituloModal">
            Agregar empleado
        </h2>

        <select id="rol_id"></select>

        <input type="text" id="nombre" placeholder="Nombre">

        <input type="text" id="apellido_paterno" placeholder="Apellido paterno">

        <input type="text" id="apellido_materno" placeholder="Apellido materno">

        <input type="email" id="correo" placeholder="Correo">

        <input type="text" id="telefono" placeholder="Teléfono">

        <input type="number" id="sueldo_diario" placeholder="Sueldo diario">

        <label for="horario_entrada">Horario de entrada</label>
        <input type="time" id="horario_entrada" value="07:00">

        <label for="horario_salida">Horario de salida</label>
        <input type="time" id="horario_salida" value="16:00">

        <br><br>

        <button class="btn-guardar" onclick="guardarEmpleado()">
            Guardar empleado
        </button>

    </div>

</div>

<script>

const API_EMPLEADOS = "../../AsisBackend/api/empleados.php";
const API_ROLES = "../../AsisBackend/api/roles.php";

let editando = false;
let empleadoId = null;

function abrirModal() {

    document.getElementById("modalEmpleado").style.display = "flex";

    if (!editando) {
        document.getElementById("horario_entrada").value = "07:00";
        document.getElementById("horario_salida").value = "16:00";
    }
}

function cerrarModal() {

    document.getElementById("modalEmpleado").style.display = "none";

    document.getElementById("rol_id").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("apellido_paterno").value = "";
    document.getElementById("apellido_materno").value = "";
    document.getElementById("correo").value = "";
    document.getElementById("telefono").value = "";
    document.getElementById("sueldo_diario").value = "";
    document.getElementById("horario_entrada").value = "07:00";
    document.getElementById("horario_salida").value = "16:00";

    document.getElementById("tituloModal").innerText =
        "Agregar empleado";

    editando = false;
    empleadoId = null;
}

function mostrarAlerta(mensaje, tipo = "success") {

    const alerta = document.getElementById("alerta");

    alerta.innerText = mensaje;

    alerta.classList.remove("success", "error");

    alerta.classList.add("mostrar", tipo);

    setTimeout(() => {

        alerta.classList.remove("mostrar");

    }, 3000);
}

async function cargarRoles() {

    try {

        const respuesta = await fetch(API_ROLES);

        const texto = await respuesta.text();
        console.log("Respuesta roles:", texto);

        const data = JSON.parse(texto);

        let html = "";

        data.forEach(rol => {

            html += `
                <option value="${rol.id}">
                    ${rol.nombre}
                </option>
            `;
        });

        document.getElementById("rol_id").innerHTML = html;

    } catch (error) {

        console.error("Error al cargar roles:", error);

        mostrarAlerta(
            "Error al cargar roles",
            "error"
        );
    }
}

async function cargarEmpleados() {

    try {

        const respuesta = await fetch(API_EMPLEADOS);

        const texto = await respuesta.text();
        console.log("Respuesta empleados:", texto);

        const data = JSON.parse(texto);

        let html = "";

        data.forEach(emp => {

            html += `
                <tr>

                    <td>${emp.id}</td>

                    <td>
                        ${emp.nombre ?? ""}
                        ${emp.apellido_paterno ?? ""}
                    </td>

                    <td>${emp.rol_nombre ?? ""}</td>

                    <td>${emp.correo ?? ""}</td>

                    <td>${emp.telefono ?? ""}</td>

                    <td>$${emp.sueldo_diario ?? "0.00"}</td>

                    <td>${emp.horario_entrada ?? "--"}</td>

                    <td>${emp.horario_salida ?? "--"}</td>

                    <td>

                        <button
                            class="btn-editar"
                            onclick="editarEmpleado(${emp.id})"
                        >
                            Editar
                        </button>

                        <button
                            class="btn-eliminar"
                            onclick="eliminarEmpleado(${emp.id})"
                        >
                            Eliminar
                        </button>

                    </td>

                </tr>
            `;
        });

        document.getElementById("tablaEmpleados").innerHTML = html;

    } catch (error) {

        console.error("Error al cargar empleados:", error);

        mostrarAlerta(
            "Error al cargar empleados",
            "error"
        );
    }
}

function buscarEmpleado() {

    let filtro =
        document.getElementById("busqueda")
        .value
        .toLowerCase();

    let filas =
        document.querySelectorAll("#tablaEmpleados tr");

    filas.forEach(fila => {

        let texto =
            fila.innerText.toLowerCase();

        if (texto.includes(filtro)) {

            fila.style.display = "";
        } else {

            fila.style.display = "none";
        }
    });
}

async function guardarEmpleado() {

    if (
        document.getElementById("nombre").value === "" ||
        document.getElementById("correo").value === "" ||
        document.getElementById("telefono").value === "" ||
        document.getElementById("horario_entrada").value === "" ||
        document.getElementById("horario_salida").value === ""
    ) {

        mostrarAlerta(
            "Completa todos los campos obligatorios",
            "error"
        );

        return;
    }

    const empleado = {

        rol_id: document.getElementById("rol_id").value,
        nombre: document.getElementById("nombre").value,
        apellido_paterno: document.getElementById("apellido_paterno").value,
        apellido_materno: document.getElementById("apellido_materno").value,
        correo: document.getElementById("correo").value,
        password: "123456",
        telefono: document.getElementById("telefono").value,
        sueldo_diario: document.getElementById("sueldo_diario").value,

        horario_entrada: document.getElementById("horario_entrada").value + ":00",
        horario_salida: document.getElementById("horario_salida").value + ":00"
    };

    let url = API_EMPLEADOS;
    let method = "POST";

    if (editando) {

        url += `?id=${empleadoId}`;
        method = "PUT";
    }

    try {

        const respuesta = await fetch(url, {

            method: method,

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify(empleado)
        });

        const texto = await respuesta.text();
        console.log("Respuesta guardar empleado:", texto);

        if (texto) {
            const resultado = JSON.parse(texto);

            if (resultado.success === false) {
                mostrarAlerta(
                    resultado.message || "Error al guardar empleado",
                    "error"
                );
                return;
            }
        }

        if (editando) {

            mostrarAlerta("Empleado actualizado");
        } else {

            mostrarAlerta("Empleado agregado");
        }

        cerrarModal();

        cargarEmpleados();

    } catch (error) {

        console.error("Error al guardar empleado:", error);

        mostrarAlerta(
            "Error al guardar empleado",
            "error"
        );
    }
}

async function editarEmpleado(id) {

    try {

        const respuesta = await fetch(`${API_EMPLEADOS}?id=${id}`);

        const texto = await respuesta.text();
        console.log("Respuesta editar empleado:", texto);

        const emp = JSON.parse(texto);

        abrirModal();

        editando = true;
        empleadoId = id;

        document.getElementById("tituloModal").innerText =
            "Editar empleado";

        document.getElementById("rol_id").value =
            emp.rol_id;

        document.getElementById("nombre").value =
            emp.nombre;

        document.getElementById("apellido_paterno").value =
            emp.apellido_paterno;

        document.getElementById("apellido_materno").value =
            emp.apellido_materno;

        document.getElementById("correo").value =
            emp.correo;

        document.getElementById("telefono").value =
            emp.telefono;

        document.getElementById("sueldo_diario").value =
            emp.sueldo_diario;

        document.getElementById("horario_entrada").value =
            formatearHoraInput(emp.horario_entrada || "07:00:00");

        document.getElementById("horario_salida").value =
            formatearHoraInput(emp.horario_salida || "16:00:00");

    } catch (error) {

        console.error("Error al obtener empleado:", error);

        mostrarAlerta(
            "Error al obtener empleado",
            "error"
        );
    }
}

async function eliminarEmpleado(id) {

    let confirmar = confirm(
        "¿Seguro que deseas eliminar este empleado?"
    );

    if (!confirmar) {

        return;
    }

    try {

        const respuesta = await fetch(
            `${API_EMPLEADOS}?id=${id}`,
            {
                method: "DELETE"
            }
        );

        const texto = await respuesta.text();
        console.log("Respuesta eliminar empleado:", texto);

        mostrarAlerta("Empleado eliminado");

        cargarEmpleados();

    } catch (error) {

        console.error("Error al eliminar empleado:", error);

        mostrarAlerta(
            "Error al eliminar empleado",
            "error"
        );
    }
}

function formatearHoraInput(hora) {

    if (!hora) {
        return "";
    }

    return hora.substring(0, 5);
}

cargarRoles();
cargarEmpleados();

</script>

</body>
</html>