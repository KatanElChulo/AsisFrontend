<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Escanear QR</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/qr.css">

</head>

<body>

<div class="qr-page">

    <h1 id="tituloScanner">Escanear QR</h1>

    <p>Permite el acceso a la cámara para registrar asistencia</p>

    <div class="qr-container">
        <div id="reader"></div>
    </div>

    <br>

    <button class="btn-eliminar" onclick="volverPortal()">
        Cancelar
    </button>

</div>

<div id="alerta" class="alerta"></div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const usuario =
    JSON.parse(localStorage.getItem("usuario"));

if (!usuario) {
    window.location.href = "../login.php";
}

const params = new URLSearchParams(window.location.search);
const tipo = params.get("tipo");

if (tipo !== "entrada" && tipo !== "salida") {
    window.location.href = "portal.php";
}

document.getElementById("tituloScanner").innerText =
    tipo === "entrada"
        ? "Registrar Entrada"
        : "Registrar Salida";

function mostrarAlerta(mensaje, tipoAlerta = "success") {

    const alerta = document.getElementById("alerta");

    alerta.innerText = mensaje;

    alerta.classList.remove("success", "error");
    alerta.classList.add("mostrar", tipoAlerta);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 3000);
}

let scannerActivo = true;

const html5QrCode =
    new Html5Qrcode("reader");

Html5Qrcode.getCameras()
.then(cameras => {

    if (!cameras || cameras.length === 0) {
        mostrarAlerta("No se encontró cámara", "error");
        return;
    }

    const camaraTrasera =
        cameras.find(camera =>
            camera.label.toLowerCase().includes("back") ||
            camera.label.toLowerCase().includes("rear")
        );

    const cameraId =
        camaraTrasera ? camaraTrasera.id : cameras[0].id;

    html5QrCode.start(
        cameraId,
        {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        onScanSuccess
    );

})
.catch(error => {
    console.error(error);
    mostrarAlerta("No se pudo abrir la cámara", "error");
});

function onScanSuccess(decodedText) {

    if (!scannerActivo) return;

    scannerActivo = false;

    html5QrCode.stop()
    .then(() => {

        obtenerUbicacion(decodedText);

    })
    .catch(() => {

        obtenerUbicacion(decodedText);
    });
}

function obtenerUbicacion(token) {

    if (!navigator.geolocation) {

        registrarAsistencia(token, null, null);
        return;
    }

    navigator.geolocation.getCurrentPosition(

        position => {

            registrarAsistencia(
                token,
                position.coords.latitude,
                position.coords.longitude
            );
        },

        error => {

            registrarAsistencia(token, null, null);
        },

        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

async function registrarAsistencia(token, latitud, longitud) {

    try {

        const res = await fetch(
            "http://localhost/AsisProyecto/AsisBackend/api/asistencia_qr.php",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    empleado_id: usuario.id,
                    tipo: tipo,
                    token: token,
                    latitud: latitud,
                    longitud: longitud
                })
            }
        );

        const data = await res.json();

        if (data.success) {

            mostrarAlerta(data.message, "success");

            setTimeout(() => {
                window.location.href = "portal.php";
            }, 1500);

        } else {

            mostrarAlerta(data.message, "error");

            setTimeout(() => {
                window.location.href = "portal.php";
            }, 2000);
        }

    } catch (error) {

        console.error(error);

        mostrarAlerta("Error al registrar asistencia", "error");

        setTimeout(() => {
            window.location.href = "portal.php";
        }, 2000);
    }
}

function volverPortal() {
    window.location.href = "portal.php";
}

</script>

</body>
</html>