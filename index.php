<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Sistema de Asistencia</title>
</head>
<body>

  <h1>Iniciar sesión</h1>

  <form id="loginForm">
    <label>Correo:</label>
    <input type="email" id="correo" required>

    <br><br>

    <label>Contraseña:</label>
    <input type="password" id="password" required>

    <br><br>

    <button type="submit">Entrar</button>
  </form>

  <p id="mensaje"></p>

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
        console.log(data);

        if (data.success) {
          localStorage.setItem("usuario", JSON.stringify(data.usuario));

          if (data.rol === "ADMIN") {
            window.location.href = "pages/empleados.php";
          } else {
            window.location.href = "pages/asistencia.php";
          }

        } else {
          mensaje.textContent = data.message;
        }
      })
      .catch(error => {
        console.error("Error:", error);
        mensaje.textContent = "Error al conectar con el servidor";
      });
    });
  </script>

</body>
</html>