<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Empleados</title>
</head>
<body>

  <h1>Lista de empleados</h1>
  <pre id="output"></pre>

  <script>
    fetch("http://localhost/AsisProyecto/AsisBackend/api/empleados.php")
      .then(res => res.json())
      .then(data => {
        console.log("Empleados:", data);

        document.getElementById("output").textContent =
          JSON.stringify(data, null, 2);
      })
      .catch(err => console.error("Error:", err));
  </script>

</body>
</html>