<?php
// Iniciar la sesión
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['user_id'];
    echo "
        <div class='account'>
            <h2>Detalles de la Cuenta</h2><br>
            <button id='no_account' type='button'>
                <img src='img/close.svg' alt='Cerrar'>
            </button>
            <p>Nombre de Usuario:</p>
            <input type='text' id='username' name='username' value='$username' readonly><br>
            <p>Correo Electrónico:</p> 
            <input type='text' id='email' name='email' value='$email' readonly><br>
            <button id='closeaccount'>Cerrar Sesión</button><br>
            <button id='deleteaccount'>Eliminar</button>
        ";
    if ($id == 1) {
        echo '
            <br>
            <button id="Reportes" onclick="window.location.href=\'./php/logic/report/report.php\'">Reportes</button>
            <button id="Incidencias" onclick="window.location.href=\'./php/logic/report/incident.php\'">Incidencias</button>
        ';
    }  
    echo"
        </div>";
} else {
    header("Location: ../../index.html");
exit();
}
?>
