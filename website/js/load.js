// Referencia al div de carga
const loadDiv = document.getElementById('loadDiv');

const option = [
    '<img src="./img/load1.svg" alt=""><br><h3>Revisa los niveles de líquidos periódicamente</h3><br><h4>Comprueba aceite, líquido de frenos y refrigerante para asegurar el buen funcionamiento del motor.</h4><div class="load_box"><img src="./img/load.svg" alt=""><h4>Cargando..</h4></div>',
    '<img src="./img/load2.svg" alt=""><br><h3>Utiliza las luces cuando sea necesario</h3><br><h4>Enciende las luces cortas al amanecer, anochecer, o en túneles, y las largas solo en vías con baja visibilidad y sin riesgo de deslumbrar a otros.</h4><div class="load_box"><img src="./img/load.svg" alt=""><h4>Cargando..</h4></div>',
    '<img src="./img/load3.svg" alt=""><br><h3>Planifica tu ruta antes de salir</h3><br><h4>Consulta el mapa o GPS para evitar desvíos innecesarios y reducir el riesgo de distracciones mientras conduces.</h4><div class="load_box"><img src="./img/load.svg" alt=""><h4>Cargando..</h4></div>',
    '<img src="./img/load4.svg" alt=""><br><h3>Conduce solo si estás descansado</h3><br><h4>Si sientes fatiga, detente y descansa. La somnolencia reduce drásticamente tus reflejos y capacidad de reacción.</h4><div class="load_box"><img src="./img/load.svg" alt=""><h4>Cargando..</h4></div>',
    '<img src="./img/load5.svg" alt=""><br><h3>Evita usar dispositivos móviles mientras conduces</h3><br><h4>Si necesitas atender una llamada, utiliza un sistema manos libres o detente en un lugar seguro.</h4><div class="load_box"><img src="./img/load.svg" alt=""><h4>Cargando..</h4></div>'
]
let ultimoIndice = -1;

function cambiarTexto() {
    let indiceAleatorio;
    do {
        indiceAleatorio = Math.floor(Math.random() * option.length);
    } while (indiceAleatorio === ultimoIndice); // Evitar repetir el último

    ultimoIndice = indiceAleatorio;
    loadDiv.innerHTML = option[indiceAleatorio];

}

// Cambiar el texto al cargar la página
cambiarTexto();

// Cambiar el texto cada 15 segundos
setInterval(cambiarTexto, 15000);