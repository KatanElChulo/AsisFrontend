<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Portal</title>

    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/portal.css">

</head>

<body>

<div class="portal-container">

    <!-- TITULO -->

    <h1
        class="portal-title"
        id="bienvenida">
    </h1>

    <!-- SUBTITULO -->

    <p
        class="portal-subtitle"
        id="rolUsuario">
    </p>

    <!-- BOTONES -->

    <div class="portal-buttons">

        <!-- ENTRADA -->

        <button
            class="portal-btn portal-entrada"
            onclick="registrarEntrada()"
        >

            Registrar Entrada

            <span>
                Escanea el QR para registrar entrada
            </span>

        </button>

        <!-- SALIDA -->

        <button
            class="portal-btn portal-salida"
            onclick="registrarSalida()"
        >

            Registrar Salida

            <span>
                Escanea el QR para registrar salida
            </span>

        </button>

        <!-- ADMIN -->

        <button
            id="btnAdmin"
            class="portal-btn portal-admin"
            style="display:none;"
            onclick="irDashboard()"
        >

            Dashboard Administrador

            <span>
                Administrar sistema y CRUDs
            </span>

        </button>

        <!-- LOGOUT -->

        <button
            class="portal-btn portal-logout"
            onclick="cerrarSesion()"
        >

            Cerrar sesión

            <span>
                Salir del sistema
            </span>

        </button>

    </div>

</div>

<script>

/* OBTENER USUARIO */

const usuario =
    JSON.parse(localStorage.getItem("usuario"));

/* VALIDAR LOGIN */

if (!usuario) {

    window.location.href =
        "../login.php";
}

/* MOSTRAR NOMBRE */

document.getElementById("bienvenida").innerText =
    "Bienvenido " + usuario.nombre;

/* MOSTRAR ROL */

document.getElementById("rolUsuario").innerText =
    "Rol: " + usuario.rol;

/* MOSTRAR BOTON ADMIN */

if (usuario.rol === "ADMIN") {

    document.getElementById("btnAdmin")
        .style.display = "block";
}

/* FUNCIONES */

function registrarEntrada() {

    window.location.href =
        "scanner.php?tipo=entrada";
}

function registrarSalida() {

    window.location.href =
        "scanner.php?tipo=salida";
}

function irDashboard() {

    window.location.href =
        "../pages/Dashboard.php";
}

function cerrarSesion() {

    localStorage.removeItem("usuario");

    window.location.href =
        "../index.php";
}

</script>

</body>
</html>