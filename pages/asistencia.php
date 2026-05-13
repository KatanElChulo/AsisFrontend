<!DOCTYPE html>
<html>
<head>
    <title>Asistencias</title>

    
    <link rel="stylesheet" href="../css/style.css">
        
    
</head>

<body>

<h2>Registro de Asistencia</h2>

<input type="text" id="qr" placeholder="Escanea QR">
<button onclick="registrarQR()"class ="btn-agregar">Registrar</button>

<p id="mensaje"></p>

<h3>Lista de asistencias</h3>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody id="tabla"></tbody>

</table>

<script>

const API = "http://localhost/AsisProyecto/AsisBackend/api/asistencias.php";

/* =========================
   FUNCIÓN SEGURA JSON
========================= */
async function fetchJSON(url, options = {}) {

    const res = await fetch(url, options);
    const text = await res.text();

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error("Respuesta NO JSON del servidor:", text);
        return { success: false, message: "Error en servidor" };
    }
}

/* =========================
   CARGAR ASISTENCIAS
========================= */
async function cargar() {

    const result = await fetchJSON(API);

    if (!result.success) {
        document.getElementById("mensaje").innerText =
            result.message || "Error al cargar datos";
        return;
    }

    let html = "";

    result.data.forEach(a => {

        html += `
        <tr>
            <td>${a.id}</td>
            <td>${a.nombre}</td>
            <td>${a.fecha}</td>
            <td>${a.tipo}</td>
            <td>

                <select onchange="editar(${a.id}, this.value)">
                    <option value="entrada" ${a.tipo === "entrada" ? "selected" : ""}>Entrada</option>
                    <option value="salida" ${a.tipo === "salida" ? "selected" : ""}>Salida</option>
                </select>

                <button onclick="eliminar(${a.id})">Eliminar</button>

            </td>
        </tr>
        `;
    });

    document.getElementById("tabla").innerHTML = html;
}

/* =========================
   REGISTRAR QR
========================= */
async function registrarQR() {

    const qr = document.getElementById("qr").value;

    const result = await fetchJSON(API, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ qr })
    });

    document.getElementById("mensaje").innerText =
        result.message || "Respuesta sin mensaje";

    cargar();
}

/* =========================
   EDITAR
========================= */
async function editar(id, tipo) {

    await fetchJSON(`${API}?id=${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ tipo })
    });

    cargar();
}

/* =========================
   ELIMINAR
========================= */
async function eliminar(id) {

    if (!confirm("¿Eliminar registro?")) return;

    await fetchJSON(`${API}?id=${id}`, {
        method: "DELETE"
    });

    cargar();
}

/* INIT */
cargar();

</script>

</body>
</html>