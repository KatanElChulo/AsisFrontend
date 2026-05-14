<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Administrador</title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- CSS DASHBOARD -->
    <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

<div class="dashboard-container">

    <!-- HEADER -->

    <div class="dashboard-header">

        <div class="dashboard-title-box">

            <h1>
                Dashboard Administrador
            </h1>

            <p>
                Gestiona el sistema DorayPaty
            </p>

        </div>

        <button
            class="dashboard-back"
            onclick="volverPortal()"
        >

            Volver al portal

        </button>

    </div>

    <!-- GRID -->

    <div class="dashboard-grid">

        <!-- EMPLEADOS -->

        <div
            class="dashboard-card card-empleados"
            onclick="irEmpleados()"
        >

            <div class="dashboard-icon">
                👨‍💼
            </div>

            <h2>
                Empleados
            </h2>

            <p>
                Administra empleados,
                información personal,
                roles y accesos.
            </p>

        </div>

        <!-- ROLES -->

        <div
            class="dashboard-card card-roles"
            onclick="irRoles()"
        >

            <div class="dashboard-icon">
                🛡️
            </div>

            <h2>
                Roles
            </h2>

            <p>
                Gestiona permisos,
                accesos y tipos
                de usuario.
            </p>

        </div>

        <!-- ASISTENCIAS -->

        <div
            class="dashboard-card card-asistencias"
            onclick="irAsistencias()"
        >

            <div class="dashboard-icon">
                📅
            </div>

            <h2>
                Asistencias
            </h2>

            <p>
                Consulta entradas,
                salidas y registros
                del personal.
            </p>

        </div>

        <!-- NOMINAS -->

        <div
            class="dashboard-card card-nominas"
            onclick="irNominas()"
        >

            <div class="dashboard-icon">
                💰
            </div>

            <h2>
                Nóminas
            </h2>

            <p>
                Administra pagos,
                cálculos y reportes
                financieros.
            </p>

        </div>

    </div>

    <!-- FOOTER -->

    <div class="dashboard-footer">

        Hecho con ❤️ 

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

/* VALIDAR ADMIN */

if (usuario.rol !== "ADMIN") {

    window.location.href =
        "portal.php";
}

/* REDIRECCIONES */

function irEmpleados() {

    window.location.href =
        "empleados.php";
}

function irRoles() {

    window.location.href =
        "roles.php";
}

function irAsistencias() {

    window.location.href =
        "asistencias.php";
}

function irNominas() {

    window.location.href =
        "nominas.php";
}

function volverPortal() {

    window.location.href =
        "portal.php";
}

</script>

</body>
</html>