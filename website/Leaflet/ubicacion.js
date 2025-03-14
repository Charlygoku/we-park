// Obtener referencias a elementos del DOM
const ubi_butMenLat = document.getElementById('bot_men_lat');
const ubi_divMenLat = document.getElementById('men_lat');

// Función para obtener la ubicación del usuario
function obtenerUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(mostrarUbicacion, errorUbicacion);
    } else {
        alert("Tu navegador no soporta geolocalización.");
        establecerUbicacionPredeterminada();
    }
}

// Función para establecer una ubicación predeterminada
function establecerUbicacionPredeterminada() {
    if (typeof map !== "undefined") {
        map.setView([40.256786, -3.697198], 15);
    }
}

// Función para mostrar la ubicación en el mapa
function mostrarUbicacion(posicion) {
    const latitud = posicion.coords.latitude;
    const longitud = posicion.coords.longitude;
    const precision = posicion.coords.accuracy;

    if (typeof map !== "undefined") {
        map.setView([latitud, longitud], 15);
        console.log("Latitud:", latitud, "Longitud:", longitud, "Precisión:", precision, "m");
    }
}

// Función para manejar errores de geolocalización
function errorUbicacion(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert("El usuario no permitió compartir su ubicación.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("No se pudo determinar tu ubicación.");
            break;
        case error.TIMEOUT:
            alert("La solicitud de ubicación ha caducado.");
            break;
        default:
            alert("Se ha producido un error desconocido.");
    }
    establecerUbicacionPredeterminada();
}

// Llamar a la función para obtener la ubicación al cargar el script
obtenerUbicacion();

// Agregar el evento submit al formulario
document.querySelector('.form_men_lat').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío tradicional del formulario
    
    // Obtener el formulario
    const form = document.querySelector('.form_men_lat');
    
    // Crear objeto con los datos del formulario
    const formData = new FormData(form);
    const datos = {};

    // Convertir FormData a objeto JSON
    formData.forEach((value, key) => {
        datos[key] = value;
    });

    // Enviar datos al PHP usando fetch
    fetch('./Leaflet/php/location.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => {
        if (!response.ok) {
            return Promise.reject('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            return Promise.reject(data.mensaje);
        }

        // Validar que las coordenadas sean números válidos
        if (typeof data.x !== 'number' || typeof data.y !== 'number' || isNaN(data.x) || isNaN(data.y)) {
            return Promise.reject('Coordenadas inválidas recibidas del servidor');
        }

        // Asegurar que el mapa existe antes de manipularlo
        if (typeof map !== 'undefined' && map) {
            setTimeout(() => {
                if (map.invalidateSize) {
                    map.invalidateSize();
                }
                map.setView([data.x, data.y], zoomActual || 15, {
                    animate: true,
                    duration: 1
                });

                // Agregar un marcador en la nueva ubicación
                L.marker([data.x, data.y]).addTo(map);
            }, 100);
          form.reset();
        }

        // Cerrar el menú lateral con una animación
        isMenuVisible = false;
        divMenLat.classList.remove('aparicion_menu_lat');
        divMenLat.classList.add('aparicion_menu_lat_inv');
        butMenLat.classList.remove('deslizamiento_boton_lat');
        butMenLat.classList.add('deslizamiento_boton_lat_inv');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al buscar la ubicación: ' + error);
    });
});
