/// Inicializar el mapa sin establecer una ubicación inicial
const map = L.map('map');

// Agregar una capa de mapa base
const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


// Variable para almacenar el zoom actual, fuera de whenReady
let zoomActual;

// Variable para almacenar los marcadores activos
let marcadoresActivos = new Map();

// Esperar a que el mapa y los mosaicos terminen de cargar
map.whenReady(() => {
    tileLayer.on('load', () => {
      setTimeout(() => {
        if (loadDiv) loadDiv.style.display = 'none';
      }, 1000);
    });

    // Inicializar el zoom actual después de que el mapa esté listo
    zoomActual = map.getZoom();

    // Agregar evento de zoom para actualizar la variable una vez que el mapa esté listo
    map.on('zoomend', () => {
        zoomActual = map.getZoom();
    });

    const cargarMarcadores = () => {
      fetch('./Leaflet/php/marker.php') // Ruta al archivo PHP que devuelve los datos
          .then(response => {
              if (!response.ok) {
                  throw new Error('Error en la respuesta del servidor');
              }
              return response.json();
          })
          .then(data => {
              let nuevosMarcadores = new Map();

              data.forEach(calle => {
                  const lat = calle.x;
                  const lng = calle.y;
                  const clave = `${lat},${lng}`; // Clave única para el marcador
                  const popupContent = `
                      <strong>Calle:</strong> ${calle.Calle}<br>
                      <strong>Parking:</strong> ${calle.Parking}
                  `;

                  if (marcadoresActivos.has(clave)) {
                      let marcadorExistente = marcadoresActivos.get(clave);

                      // Verificar si el popup necesita actualización
                      if (marcadorExistente.getPopup().getContent() !== popupContent) {
                          marcadorExistente.setPopupContent(popupContent);
                      }

                      nuevosMarcadores.set(clave, marcadorExistente);
                  } else {
                      // Crear el nuevo marcador
                      const marcador = L.marker([lat, lng])
                          .addTo(map)
                          .bindPopup(popupContent);
                      nuevosMarcadores.set(clave, marcador);
                  }
              });

              // Esperar 1 segundo antes de eliminar los marcadores antiguos
              setTimeout(() => {
                  marcadoresActivos.forEach((marcador, clave) => {
                      if (!nuevosMarcadores.has(clave)) {
                          map.removeLayer(marcador);
                      }
                  });

                  // Actualizar la lista de marcadores activos
                  marcadoresActivos = nuevosMarcadores;
              }, 1000); // Espera 1 segundo antes de eliminar los antiguos
          })
          .catch(error => console.error('Error al obtener los datos:', error));
  };

  // Cargar los marcadores inicialmente
  cargarMarcadores();

  // Recargar los marcadores cada 5 segundos sin hacerlos desaparecer bruscamente
  setInterval(cargarMarcadores, 5000);
});