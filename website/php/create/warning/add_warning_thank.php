<?php
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION["username"])) {
    // Si la sesión está iniciada
    echo "
    <div class='tank_bug'>
        <h3>¡Gracias por tu aporte! </h3>
        <p>Tu parking ha sido registrado correctamente. 
        Ahora otros usuarios podrán ver la información y 
        aprovechar el espacio disponible. ¡Tu colaboración 
        hace que la comunidad sea más útil para todos!
        </p>
    </div>";
} else {
    // Si no está logueado, mostrar error
    header("Location: ../../index.html");
}
?>