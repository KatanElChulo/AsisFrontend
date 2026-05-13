<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Roles</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <h1>Lista de Roles</h1>

    <div class="acciones-superiores">

        <button class="btn-agregar" onclick="abrirModal()">
            Agregar rol
        </button>

        <input
            type="text"
            id="busqueda"
            placeholder="Buscar rol..."
            onkeyup="buscarRol()"
        >

    </div>

    <br>

    <div class="tabla-responsive">

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

    </div>

    <!-- ALERTA IGUAL QUE EMPLEADOS -->
    <div id="alerta" class="alerta"></div>

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

            <button class="btn-guardar" onclick="guardarRol()">
                Guardar rol
            </button>

        </div>

    </div>

<script>

let editando = false;
let nombreActual = null;

/* MODAL */
function abrirModal() {
    document.getElementById("modalRol").style.display = "flex";
}

function cerrarModal() {

    document.getElementById("modalRol").style.display = "none";
    document.getElementById("nombre").value = "";
    document.getElementById("tituloModal").innerText = "Agregar rol";

    editando = false;
    nombreActual = null;
}

/* ALERTA (MISMO SISTEMA QUE EMPLEADOS) */
function mostrarAlerta(mensaje, tipo = "success") {

    const alerta = document.getElementById("alerta");

    alerta.innerText = mensaje;

    alerta.classList.remove("success", "error");

    alerta.classList.add("mostrar", tipo);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 3000);
}

/* CARGAR */
async function cargarRoles() {

    const res = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/roles.php"
    );

    const data = await res.json();

    let html = "";

    data.forEach(rol => {

        html += `
            <tr>

                <td>${rol.nombre}</td>

                <td>

                    <button
                        class="btn-editar"
                        onclick="editarRol('${rol.nombre}')"
                    >
                        Editar
                    </button>

                    <button
                        class="btn-eliminar"
                        onclick="eliminarRol('${rol.nombre}')"
                    >
                        Eliminar
                    </button>

                </td>

            </tr>
        `;
    });

    document.getElementById("tablaRoles").innerHTML = html;
}

/* BUSCAR */
function buscarRol() {

    let filtro =
        document.getElementById("busqueda").value.toLowerCase();

    let filas =
        document.querySelectorAll("#tablaRoles tr");

    filas.forEach(fila => {

        fila.style.display =
            fila.innerText.toLowerCase().includes(filtro)
            ? ""
            : "none";
    });
}

/* GUARDAR */
async function guardarRol() {

    const nombre = document.getElementById("nombre").value;

    if (nombre === "") {
        mostrarAlerta("Escribe un nombre de rol", "error");
        return;
    }

    let url =
        "http://localhost/AsisProyecto/AsisBackend/api/roles.php";

    let method = "POST";

    if (editando) {
        url += `?nombre=${encodeURIComponent(nombreActual)}`;
        method = "PUT";
    }

    await fetch(url, {

        method: method,
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ nombre })
    });

    mostrarAlerta(
        editando ? "Rol actualizado, success" : "Rol agregado, success"
    );

    cerrarModal();
    cargarRoles();
}

/* EDITAR */
async function editarRol(nombre) {

    const res = await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/roles.php?nombre=${encodeURIComponent(nombre)}`
    );

    const rol = await res.json();

    abrirModal();

    editando = true;
    nombreActual = nombre;

    document.getElementById("tituloModal").innerText =
        "Editar rol";

    document.getElementById("nombre").value =
        rol.nombre;

    
}

/* ELIMINAR */
async function eliminarRol(nombre) {

    if (!confirm("¿Seguro que deseas eliminar este rol?")) return;

    await fetch(
        `http://localhost/AsisProyecto/AsisBackend/api/roles.php?nombre=${encodeURIComponent(nombre)}`,
        {
            method: "DELETE"
        }
    );

    mostrarAlerta("Rol eliminado, success");
    cargarRoles();
}

/* INIT */
cargarRoles();

</script>

</body>
</html>