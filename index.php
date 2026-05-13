<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Control de Asistencia</title>

  <link rel="stylesheet" href="css/login.css">
</head>
<body>

  <main class="login-page">

    <section class="login-card">

      <div class="login-icon">
        👤
      </div>

      <h1>Sistema de<br>Control de Asistencia</h1>

      <p class="login-subtitle">
        Inicia sesión para continuar
      </p>

      <form id="loginForm">

        <div class="form-group">
          <label for="correo">Correo electrónico</label>

          <div class="input-box">
            <span>✉️</span>
            <input 
              type="email" 
              id="correo" 
              placeholder="ejemplo@correo.com" 
              required
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>

          <div class="input-box">
            <span>🔒</span>
            <input 
              type="password" 
              id="password" 
              placeholder="Ingresa tu contraseña" 
              required
            >
          </div>
        </div>

        <div class="login-options">
          <label>
            <input type="checkbox">
            Recordarme
          </label>

          <a href="#">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="login-button">
          Iniciar sesión
        </button>

        <p id="mensaje" class="login-message"></p>

      </form>

      <div class="login-access">
        Acceso para <strong>Administrador</strong> y <strong>Empleado</strong>
      </div>

    </section>

  </main>

  <footer class="login-footer">
    © 2025 Sistema de Control de Asistencia. Todos los derechos reservados.
  </footer>

  <script>
    const form = document.getElementById("loginForm");
    const mensaje = document.getElementById("mensaje");

    form.addEventListener("submit", function(e) {
      e.preventDefault();

      const correo = document.getElementById("correo").value;
      const password = document.getElementById("password").value;

      fetch("http://localhost/AsisProyecto/AsisBackend/api/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          correo: correo,
          password: password
        })
      })
      .then(res => res.json())
      .then(data => {

        if (data.success) {
          localStorage.setItem("usuario", JSON.stringify(data.usuario));

          if (data.rol === "ADMIN") {
            window.location.href = "pages/empleados.php";
          } else {
            window.location.href = "pages/asistencia.php";
          }

        } else {
          mensaje.textContent = data.message;
          mensaje.classList.add("error");
        }

      })
      .catch(error => {
        console.error(error);
        mensaje.textContent = "Error al conectar con el servidor";
        mensaje.classList.add("error");
      });
    });
  </script>

</body>
</html>