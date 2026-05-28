<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Escanear QR</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/qr.css?v=3">

</head>

<body>

<div class="qr-page">

    <h1 id="tituloScanner">Escanear QR</h1>

    <p>Permite el acceso a la cámara y ubicación para registrar asistencia</p>

    <div class="qr-container">
        <div id="reader"></div>
    </div>

    <br>

    <button class="btn-eliminar" onclick="volverPortal()">
        Cancelar
    </button>

</div>

<div id="alerta" class="alerta"></div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function() {

    const usuario =
        JSON.parse(localStorage.getItem("usuario"));

    if (!usuario) {
        window.location.href = "../index.php";
        return;
    }

    const params =
        new URLSearchParams(window.location.search);

    const tipo =
        params.get("tipo");

    if (tipo !== "entrada" && tipo !== "salida") {
        window.location.href = "Portal.php";
        return;
    }

    const tituloScanner =
        document.getElementById("tituloScanner");

    if (tituloScanner) {
        tituloScanner.innerText =
            tipo === "entrada"
                ? "Registrar Entrada"
                : "Registrar Salida";
    }

    let scannerActivo = true;

    const html5QrCode =
        new Html5Qrcode("reader");

    html5QrCode.start(
        {
            facingMode: "environment"
        },
        {
            fps: 10,
            qrbox: {
                width: 220,
                height: 220
            },
            aspectRatio: 1.0
        },
        function(decodedText) {

            if (!scannerActivo) {
                return;
            }

            scannerActivo = false;

            html5QrCode.stop()
            .then(() => {
                obtenerUbicacion(decodedText);
            })
            .catch(() => {
                obtenerUbicacion(decodedText);
            });
        },
        function(errorMessage) {
            // No mostrar errores continuos del scanner.
        }
    )
    .catch(error => {

        console.error("Error cámara:", error);

        mostrarAlerta(
            "No se pudo abrir la cámara. Revisa permisos del navegador.",
            "error"
        );
    });

    function obtenerUbicacion(token) {

        if (!navigator.geolocation) {

            mostrarAlerta(
                "Tu navegador no soporta geolocalización. No se puede registrar asistencia.",
                "error"
            );

            setTimeout(() => {
                window.location.href = "Portal.php";
            }, 2500);

            return;
        }

        mostrarAlerta(
            "Obteniendo ubicación...",
            "success"
        );

        navigator.geolocation.getCurrentPosition(

            position => {

                registrarAsistencia(
                    token,
                    position.coords.latitude,
                    position.coords.longitude,
                    position.coords.accuracy
                );
            },

            error => {

                let mensaje = "No se pudo obtener tu ubicación.";

                if (error.code === error.PERMISSION_DENIED) {
                    mensaje = "Debes permitir la ubicación para registrar asistencia.";
                }

                if (error.code === error.POSITION_UNAVAILABLE) {
                    mensaje = "La ubicación no está disponible. Activa tu GPS.";
                }

                if (error.code === error.TIMEOUT) {
                    mensaje = "La ubicación tardó demasiado. Intenta de nuevo.";
                }

                mostrarAlerta(mensaje, "error");

                setTimeout(() => {
                    window.location.href = "Portal.php";
                }, 3000);
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    }

    async function registrarAsistencia(token, latitud, longitud, precision) {

        try {

            const res = await fetch(
                "../../AsisBackend/api/asistencia_qr.php",
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
                        longitud: longitud,
                        precision: precision
                    })
                }
            );

            const texto =
                await res.text();

            console.log("Respuesta asistencia_qr.php:", texto);

            const data =
                JSON.parse(texto);

            if (data.success) {

                mostrarAlerta(data.message, "success");

                setTimeout(() => {
                    window.location.href = "Portal.php";
                }, 1500);

            } else {

                mostrarAlerta(data.message, "error");

                setTimeout(() => {
                    window.location.href = "Portal.php";
                }, 3000);
            }

        } catch (error) {

            console.error(error);

            mostrarAlerta(
                "Error al registrar asistencia",
                "error"
            );

            setTimeout(() => {
                window.location.href = "Portal.php";
            }, 2500);
        }
    }

});

function mostrarAlerta(mensaje, tipoAlerta = "success") {

    const alerta =
        document.getElementById("alerta");

    if (!alerta) {
        alert(mensaje);
        return;
    }

    alerta.innerText =
        mensaje;

    alerta.classList.remove("success", "error");
    alerta.classList.add("mostrar", tipoAlerta);

    setTimeout(() => {
        alerta.classList.remove("mostrar");
    }, 3000);
}

function volverPortal() {
    window.location.href = "Portal.php";
}

</script>

</body>
</html>