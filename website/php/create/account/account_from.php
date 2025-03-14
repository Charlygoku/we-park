<?php
// Iniciar la sesión
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    echo "
        <div class='account'>
            <h2>Detalles de la Cuenta</h2><br>
            <button id='no_account' type='button'>
                <img src='img/close.svg' alt='Cerrar'>
            </button>
            <p>Nombre de Usuario:</p>
            <input type='text' id='username' name='username' value='$username' readonly><br>
            <p>Correo Electrónico:</p> 
            <input type='text' id='email' name='email' value='$email' readonly><br><br>
            <button id='closeaccount'>Cerrar Sesión</button><br><br>
            <button id='deleteaccount'>Eliminar</button>
        </div>";
} else {
    header("Location: ../../index.html");
exit();
}
?>