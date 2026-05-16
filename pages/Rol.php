<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Roles</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/crud.css">

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
    <a href="/AsisFrontend/pages/Dashboard.php" class="btn-regresar">
     Regresar al Dashboard
</a>
    <br>

    <div class="tabla-responsive">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="tablaRoles"></tbody>

        </table>

    </div>

    <div id="alerta" class="alerta"></div>

    <!-- MODAL -->
    <div id="modalRol" class="modal">

        <div class="modal-contenido">

            <span class="cerrar" onclick="cerrarModal()">
                &times;
            </span>

            <h2 id="tituloModal">Agregar rol</h2>

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
let idActual = null;

/* MODAL */
function abrirModal() {
    document.getElementById("modalRol").style.display = "flex";
}

function cerrarModal() {

    document.getElementById("modalRol").style.display = "none";

    document.getElementById("nombre").value = "";
    document.getElementById("tituloModal").innerText = "Agregar rol";

    editando = false;
    idActual = null;
}

/* ALERTA */
function mostrarAlerta(mensaje, tipo = "success") {

    const alerta = document.getElementById("alerta");

    alerta.innerText = mensaje;

    alerta.classList.remove("success", "error");
    alerta.classList.add("mostrar", tipo);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 3000);
}

/* CARGAR ROLES */
async function cargarRoles() {

    try {

        const res = await fetch(
            "/AsisProyecto/AsisBackend/api/roles.php"
        );

        const data = await res.json();

        let html = "";

        data.forEach(rol => {

            html += `
                <tr>
                    <td>${rol.id}</td>
                    <td>${rol.nombre}</td>
                    <td>

                        <button class="btn-editar"
                            onclick="editarRol(${rol.id})">
                            Editar
                        </button>

                        <button class="btn-eliminar"
                            onclick="eliminarRol(${rol.id})">
                            Eliminar
                        </button>

                    </td>
                </tr>
            `;
        });

        document.getElementById("tablaRoles").innerHTML = html;

    } catch (error) {
        mostrarAlerta("Error al cargar roles", "error");
    }
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

    if (!nombre) {
        mostrarAlerta("Escribe un nombre", "error");
        return;
    }

    let url =
        "/AsisProyecto/AsisBackend/api/roles.php";

    let method = "POST";

    if (editando) {
        url += `?id=${idActual}`;
        method = "PUT";
    }

    try {

        const res = await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ nombre })
        });

        if (!res.ok) throw new Error();

        mostrarAlerta(
            editando ? "Rol actualizado" : "Rol agregado",
            "success"
        );

        cerrarModal();
        cargarRoles();

    } catch (error) {
        mostrarAlerta("Error al guardar rol", "error");
    }
}

/* EDITAR */
async function editarRol(id) {

    const res = await fetch(
        `/AsisProyecto/AsisBackend/api/roles.php?id=${id}`
    );

    const rol = await res.json();

    abrirModal();

    editando = true;
    idActual = id;

    document.getElementById("tituloModal").innerText =
        "Editar rol";

    document.getElementById("nombre").value =
        rol.nombre;
}

/* ELIMINAR */
async function eliminarRol(id) {

    if (!confirm("¿Seguro que deseas eliminar este rol?")) return;

    try {

        const res = await fetch(
            `/AsisProyecto/AsisBackend/api/roles.php?id=${id}`,
            { method: "DELETE" }
        );

        if (!res.ok) throw new Error();

        mostrarAlerta("Rol eliminado", "success");

        cargarRoles();

    } catch (error) {
        mostrarAlerta("Error al eliminar rol", "error");
    }
}

/* INIT */
cargarRoles();

</script>

</body>
</html>