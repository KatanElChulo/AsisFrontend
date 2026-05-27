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
    <link rel="stylesheet" href="../css/dashboard.css?v=2">

</head>

<body>

<div class="dashboard-container">

    <section class="dashboard-panel">

        <!-- HEADER -->

        <div class="dashboard-header">

            <div class="dashboard-title-wrap">

                <div class="dashboard-main-icon" aria-hidden="true">
                    <span></span>
                </div>

                <div class="dashboard-title-box">

                    <h1>
                        Dashboard Administrador
                    </h1>

                    <p>
                        Gestiona el sistema DorayPaty
                    </p>

                </div>

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

                <div class="dashboard-icon icon-empleados" aria-hidden="true">
                    <span></span>
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

                <div class="dashboard-icon icon-roles" aria-hidden="true">
                    <span></span>
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

            <!-- NOMINAS -->

            <div
                class="dashboard-card card-nominas"
                onclick="irNominas()"
            >

                <div class="dashboard-icon icon-nominas" aria-hidden="true">
                    <span></span>
                </div>

                <h2>
                    Nóminas (No implementado)
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
            Sistema de Control de Asistencia
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

/* VALIDAR ADMIN */

if (usuario.rol !== "ADMIN") {

     window.location.href =
        "AsisFrontend/pages/Dashboard.php";
}

/* REDIRECCIONES */

function irEmpleados() {

    window.location.href =
        "Empleados.php";
}

function irRoles() {

    window.location.href =
        "Rol.php";
}

function irAsistencias() {

    window.location.href =
        "Asistencias.php";
}

function irNominas() {

    window.location.href =
        "Nominas.php";
}

function volverPortal() {

    window.location.href =
        "Portal.php";
}

</script>

</body>
</html>
