<?php
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION["username"])) {
    // Si la sesión está iniciada
    echo "
    <div class='tank_bug'>
        <h3>¡Gracias por tu reporte!</h3>
        <p>Tu mensaje ha sido enviado correctamente y nuestro 
            equipo lo revisará a la mayor brevedad. Apreciamos 
            mucho tu ayuda para mejorar la experiencia de todos 
            los usuarios. Si es necesario, nos pondremos en contacto 
            contigo para más detalles.
        </p>
    </div>";
} else {
    // Si no está logueado, mostrar error
    header("Location: ../../index.html");
}
?>