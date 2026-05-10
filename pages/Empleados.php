<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Empleados</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <h1>Lista de empleados</h1>

    <button onclick="abrirModal()">
        Agregar empleado
    </button>

    <input
    type="text"
    id="busqueda"
    placeholder="Buscar empleado..."
    onkeyup="buscarEmpleado()"
>

    <br><br>

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Sueldo</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody id="tablaEmpleados">

        </tbody>

    </table>

    <!-- MODAL -->

    <div id="modalEmpleado" class="modal">

        <div class="modal-contenido">

            <span class="cerrar" onclick="cerrarModal()">
                &times;
            </span>

            <h2 id="tituloModal">
                Agregar empleado
            </h2>

            <select id="rol_id">

            </select>

            <input type="text" id="nombre" placeholder="Nombre">

            <input type="text" id="apellido_paterno" placeholder="Apellido paterno">

            <input type="text" id="apellido_materno" placeholder="Apellido materno">

            <input type="email" id="correo" placeholder="Correo">

            <input type="text" id="telefono" placeholder="Teléfono">

            <input type="number" id="sueldo_diario" placeholder="Sueldo diario">

            <br><br>

            <button onclick="crearEmpleado()">
                Guardar empleado
            </button>

        </div>

    </div>

<script>

let editando = false;

let empleadoId = null;

function abrirModal() {

    document.getElementById("modalEmpleado").style.display = "flex";
}

function cerrarModal() {

    document.getElementById("modalEmpleado").style.display = "none";

    document.getElementById("nombre").value = "";

    document.getElementById("apellido_paterno").value = "";

    document.getElementById("apellido_materno").value = "";

    document.getElementById("correo").value = "";

    document.getElementById("telefono").value = "";

    document.getElementById("sueldo_diario").value = "";

    document.getElementById("tituloModal").innerText =
        "Agregar empleado";

    editando = false;

    empleadoId = null;
}

async function cargarRoles() {

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/roles.php"
    );

    const data = await respuesta.json();

    let html = "";

    data.forEach(rol => {

        html += `
            <option value="${rol.id}">
                ${rol.nombre}
            </option>
        `;
    });

    document.getElementById("rol_id").innerHTML = html;
}

async function cargarEmpleados() {

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php"
    );

    const data = await respuesta.json();

    let html = "";

    data.forEach(emp => {

        html += `
            <tr>

                <td>${emp.id}</td>

                <td>
                    ${emp.nombre}
                    ${emp.apellido_paterno}
                </td>

                <td>${emp.rol_nombre}</td>

                <td>${emp.correo}</td>

                <td>${emp.telefono}</td>

                <td>$${emp.sueldo_diario}</td>

                <td>

                    <button onclick="editarEmpleado(${emp.id})">
                        Editar
                    </button>

                    <button onclick="eliminarEmpleado(${emp.id})">
                        Eliminar
                    </button>

                </td>

            </tr>
        `;
    });

    document.getElementById("tablaEmpleados").innerHTML = html;
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

        if(texto.includes(filtro)) {

            fila.style.display = "";
        }
        else {

            fila.style.display = "none";
        }
    });
}

async function crearEmpleado() {

    const empleado = {

        rol_id: document.getElementById("rol_id").value,

        nombre: document.getElementById("nombre").value,

        apellido_paterno: document.getElementById("apellido_paterno").value,

        apellido_materno: document.getElementById("apellido_materno").value,

        correo: document.getElementById("correo").value,

        password: "123456",

        telefono: document.getElementById("telefono").value,

        sueldo_diario: document.getElementById("sueldo_diario").value,

        horario_entrada: "08:00:00",

        horario_salida: "17:00:00"
    };

    let url =
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php";

    let method = "POST";

    if(editando) {

        url += `?id=${empleadoId}`;

        method = "PUT";
    }

    await fetch(url, {

        method: method,

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify(empleado)
    });

    cerrarModal();

    cargarEmpleados();
}

async function editarEmpleado(id) {

    const respuesta = await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/empleados.php?id=${id}`
    );

    const emp = await respuesta.json();

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
}

async function eliminarEmpleado(id) {

    await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/empleados.php?id=${id}`,
        {
            method: "DELETE"
        }
    );

    cargarEmpleados();
}

cargarRoles();

cargarEmpleados();

</script>

</body>
</html>