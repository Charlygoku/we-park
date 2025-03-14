<?php
// Iniciar la sesión al inicio del archivo PHP
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION['username'])) {
    // Si no está logueado, mostrar el formulario de inicio de sesión
    echo '
        <div class="sub_account">
            <h2>¿Seguro que quieres cerrar sesión?</h2>
            <br>
            <button id="yesclose">Si</button>
            <button id="noclose">No</button>
        </div> ';
} else {
    // Si la sesión está iniciada
    header("Location: ../../index.html");

}
?>