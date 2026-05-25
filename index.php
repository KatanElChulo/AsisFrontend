<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Sistema de Control de Asistencia
    </title>

    <link rel="stylesheet" href="css/login.css">

</head>

<body>

<main class="login-page">

    <section class="login-info">

        <div class="brand-icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>

        <h1>
            Sistema de Control<br>
            de Asistencia
        </h1>

        <p class="info-description">
            Gestiona y controla las entradas, salidas y registros del personal
            de manera eficiente, segura y en tiempo real.
        </p>

        <div class="feature-list">

            <article class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <div>
                    <h3>Seguro</h3>
                    <p>Protegemos la información de tu empresa con acceso autorizado.</p>
                </div>
            </article>

            <article class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 3v18h18"/>
                        <path d="M8 17V9"/>
                        <path d="M13 17V5"/>
                        <path d="M18 17v-6"/>
                    </svg>
                </div>

                <div>
                    <h3>Eficiente</h3>
                    <p>Registra y consulta la asistencia del equipo de forma sencilla.</p>
                </div>
            </article>

            <article class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>

                <div>
                    <h3>En tiempo real</h3>
                    <p>Accede a registros y reportes actualizados al instante.</p>
                </div>
            </article>

        </div>

    </section>

    <section class="login-section">

        <div class="login-card">

            <div class="login-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5"/>
                    <path d="M21 12a9 9 0 1 1-3-6.7"/>
                </svg>
            </div>

            <h2>
                Bienvenido
            </h2>

            <p class="login-subtitle">
                Inicia sesión para continuar
            </p>

            <form id="loginForm">

                <div class="form-group">

                    <label for="correo">
                        Usuario o correo
                    </label>

                    <div class="input-box">

                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>

                        <input
                            type="email"
                            id="correo"
                            placeholder="Ingresa tu usuario o correo"
                            required
                        >

                    </div>

                </div>

                <div class="form-group">

                    <label for="password">
                        Contraseña
                    </label>

                    <div class="input-box">

                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>

                        <input
                            type="password"
                            id="password"
                            placeholder="Ingresa tu contraseña"
                            required
                        >

                    </div>

                </div>

                <button
                    type="submit"
                    class="login-button"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>

                    Iniciar sesión
                </button>

                <p id="mensaje"
                    class="login-message">
                </p>

            </form>

            <div class="role-message">
                <div class="role-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>

                <p>
                    Al iniciar sesión, serás redirigido a tu panel correspondiente
                    según tu rol.
                </p>
            </div>

            <div class="login-access">

                <div class="access-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <div>
                    <strong>Acceso seguro y autorizado</strong>
                    <span>Solo usuarios registrados pueden acceder al sistema.</span>
                </div>

            </div>

        </div>

    </section>

</main>

<script>

const form =
    document.getElementById("loginForm");

const mensaje =
    document.getElementById("mensaje");

form.addEventListener("submit", async function(e) {

    e.preventDefault();

    const correo =
        document.getElementById("correo").value;

    const password =
        document.getElementById("password").value;

    try {

        const respuesta = await fetch(
    "../AsisBackend/api/login.php",
    {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            correo: correo,
            password: password
        })
    }
);

        const data = await respuesta.json();

        if (data.success) {

            localStorage.setItem(
                "usuario",
                JSON.stringify(data.usuario)
            );

            window.location.href =
                "pages/Portal.php";

        } else {

            mensaje.textContent =
                data.message;

            mensaje.classList.add("error");
        }

    } catch (error) {

        console.error(error);

        mensaje.textContent =
            "Error al conectar con el servidor";

        mensaje.classList.add("error");
    }

});

</script>

</body>
</html>
