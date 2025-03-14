<?php
// Iniciar la sesión al inicio del archivo PHP
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION['username'])) {
    // Si no está logueado, mostrar el formulario de inicio de sesión
    echo '
    <h2>Mis Sitios </h2>
    <button id="closewarning" type="button">
        <img src="img/close.svg" alt="Cerrar">
    </button>
    <div id="warning_select">
        <button>
            <img src="./img/marker1.svg"  id="green" alt="Modificar Plazas Disponibles">
            <h3>Agregar Sitio</h3>
        </button>
        <button>
            <img src="./img/marker2.svg"  id="red" alt="Modificar Plazas Ocupadas">
            <h3>Quitar Sitio</h3>

        </button>
        <button>
            <img src="./img/speed.svg"  id="blue" alt="Salgo en...">
            <h3>Salgo en...</h3>
        </button>
        <button>
            <img src="./img/danger.svg"  id="yellow" alt="Mis Avisos">
            <h3>Incidencias</h3>
        </button>
    </div>';
} else {
    // Si la sesión está iniciada
    header("Location: ../../index.html");  
}
?>