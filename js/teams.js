fetch("https://http://asistenciaproyecto.online//api/teams.php")
  .then(res => res.json())
  .then(data => {
    const lista = document.getElementById("lista");

    data.forEach(team => {
      const li = document.createElement("li");
      li.textContent = team.name;
      lista.appendChild(li);
    });
  });