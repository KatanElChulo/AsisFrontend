<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleados</title>
</head>
<body>

<h1>Empleados</h1>

<button onclick="crearEmpleado()">
    Agregar empleado
</button>

<button onclick="editarEmpleado()">
    Editar empleado
</button>

<button onclick="eliminarEmpleado()">
    Eliminar empleado
</button>

<div id="lista"></div>

<script>

async function cargarEmpleados() {

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php"
    );

    const data = await respuesta.json();

    let html = "";

    data.forEach(emp => {

        html += `
            <div>
                <h3>${emp.nombre}</h3>
                <p>${emp.correo}</p>
            </div>
        `;
    });

    document.getElementById("lista").innerHTML = html;
}

async function crearEmpleado() {

    const empleado = {
        rol_id: 1,
        nombre: "Mauricio",
        apellido_paterno: "Diaz",
        apellido_materno: "Hernandez",
        correo: "mauricio@gmail.com",
        password: "123456",
        telefono: "5512345678",
        sueldo_diario: 500,
        horario_entrada: "08:00:00",
        horario_salida: "17:00:00"
    };

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(empleado)
        }
    );

    const data = await respuesta.json();

    console.log(data);

    cargarEmpleados();
}

async function editarEmpleado() {

    const empleado = {
        rol_id: 1,
        nombre: "Mauricio Actualizado",
        apellido_paterno: "Diaz",
        apellido_materno: "Hernandez",
        correo: "actualizado@gmail.com",
        password: "123456",
        telefono: "5512345678",
        sueldo_diario: 900,
        horario_entrada: "08:00:00",
        horario_salida: "17:00:00"
    };

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php?id=1",
        {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(empleado)
        }
    );

    const data = await respuesta.json();

    console.log(data);

    cargarEmpleados();
}
async function eliminarEmpleado() {

    const respuesta = await fetch(
        "http://localhost/AsisProyecto/AsisBackend/api/empleados.php?id=1",
        {
            method: "DELETE"
        }
    );

    const data = await respuesta.json();

    console.log(data);

    cargarEmpleados();
}

cargarEmpleados();

</script>

</body>
</html>