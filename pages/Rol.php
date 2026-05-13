<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Roles</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <h1>Lista de Roles</h1>

    <button onclick="abrirModal()">
        Agregar rol
    </button>

    <input
        type="text"
        id="busqueda"
        placeholder="Buscar rol..."
        onkeyup="buscarRol()"
    >

    <br><br>

    <table>

        <thead>

            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody id="tablaRoles">

        </tbody>

    </table>

    <!-- MODAL -->

    <div id="modalRol" class="modal">

        <div class="modal-contenido">

            <span class="cerrar" onclick="cerrarModal()">
                &times;
            </span>

            <h2 id="tituloModal">
                Agregar rol
            </h2>

            <input
                type="text"
                id="nombre"
                placeholder="Nombre del rol"
            >

            <br><br>

            <button onclick="guardarRol()">
                Guardar rol
            </button>

        </div>

    </div>

<script>

let editando = false;

let nombreActual = null;

function abrirModal() {

    document.getElementById("modalRol").style.display = "flex";
}

function cerrarModal() {

    document.getElementById("modalRol").style.display = "none";

    document.getElementById("nombre").value = "";

    document.getElementById("tituloModal").innerText =
        "Agregar rol";

    editando = false;

    nombreActual = null;
}

async function cargarRoles() {

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/roles.php"
    );

    const data = await respuesta.json();

    let html = "";

    data.forEach(rol => {

        html += `
            <tr>

                <td>${rol.nombre}</td>

                <td>

                    <button onclick="editarRol('${rol.nombre}')">
                        Editar
                    </button>

                    <button onclick="eliminarRol('${rol.nombre}')">
                        Eliminar
                    </button>

                </td>

            </tr>
        `;
    });

    document.getElementById("tablaRoles").innerHTML = html;
}

function buscarRol() {

    let filtro =
        document.getElementById("busqueda")
        .value
        .toLowerCase();

    let filas =
        document.querySelectorAll("#tablaRoles tr");

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

async function guardarRol() {

    const rol = {

        nombre: document.getElementById("nombre").value
    };

    let url =
        "http://localhost/AsisProyecto/AsisBackend/api/roles.php";

    let method = "POST";

    if(editando) {

        url += `?nombre=${encodeURIComponent(nombreActual)}`;

        method = "PUT";
    }

    await fetch(url, {

        method: method,

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify(rol)
    });

    cerrarModal();

    cargarRoles();
}

async function editarRol(nombre) {

    const respuesta = await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/roles.php?nombre=${encodeURIComponent(nombre)}`
    );

    const rol = await respuesta.json();

    abrirModal();

    editando = true;

    nombreActual = nombre;

    document.getElementById("tituloModal").innerText =
        "Editar rol";

    document.getElementById("nombre").value =
        rol.nombre;
}

async function eliminarRol(nombre) {

    await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/roles.php?nombre=${encodeURIComponent(nombre)}`,
        {
            method: "DELETE"
        }
    );

    cargarRoles();
}

cargarRoles();

</script>

</body>
</html>