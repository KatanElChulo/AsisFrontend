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

    <section class="portal-card">

        <div class="portal-header">

            <div class="portal-user-icon">
                <span></span>
            </div>

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

            <div class="portal-divider"></div>

        </div>

        <!-- BOTONES -->

        <div class="portal-buttons">

            <!-- ENTRADA -->

            <button
                class="portal-btn portal-entrada"
                onclick="registrarEntrada()"
            >

                <span class="portal-icon icon-entrada"></span>

                <span class="portal-text">
                    Registrar Entrada

                    <small>
                        Escanea el QR para registrar entrada
                    </small>
                </span>

                <span class="portal-arrow"></span>

            </button>

            <!-- SALIDA -->

            <button
                class="portal-btn portal-salida"
                onclick="registrarSalida()"
            >

                <span class="portal-icon icon-salida"></span>

                <span class="portal-text">
                    Registrar Salida

                    <small>
                        Escanea el QR para registrar salida
                    </small>
                </span>

                <span class="portal-arrow"></span>

            </button>

            <!-- ADMIN -->

            <button
                id="btnAdmin"
                class="portal-btn portal-admin"
                style="display:none;"
                onclick="irDashboard()"
            >

                <span class="portal-icon icon-admin"></span>

                <span class="portal-text">
                    Dashboard Administrador

                    <small>
                        Administrar sistema y CRUDs
                    </small>
                </span>

                <span class="portal-arrow"></span>

            </button>

            <!-- LOGOUT -->

            <button
                class="portal-btn portal-logout"
                onclick="cerrarSesion()"
            >

                <span class="portal-icon icon-logout"></span>

                <span class="portal-text">
                    Cerrar sesión

                    <small>
                        Salir del sistema
                    </small>
                </span>

                <span class="portal-arrow"></span>

            </button>

        </div>

    </section>

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
        .style.display = "flex";
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
