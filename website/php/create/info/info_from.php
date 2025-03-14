<?php
// Iniciar la sesión al inicio del archivo PHP
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION['username'])) {
    // Si no está logueado, mostrar el formulario de inicio de sesión
    echo '
        <div class="info">
            <h2>Acerca de...</h2>
            <button id="closeinfo" type="button">
                <img src="img/close.svg" alt="Cerrar">
            </button>
                <div>
                    <p>
                    WePark es una aplicación web colaborativa diseñada para ayudar a los conductores
                     a encontrar y compartir información sobre lugares donde aparcar en la vía pública. 
                     Ya sea que hayas encontrado un espacio libre o desees notificar sobre un lugar ocupado, 
                     esta plataforma permite a los usuarios informar y acceder a avisos actualizados en tiempo real.
                    </p>
                    <p>
                    La idea es crear una comunidad en la que todos contribuyan para hacer más fácil y eficiente el 
                    aparcamiento, ayudando a los conductores a ahorrar tiempo y reducir el estrés de buscar un lugar 
                    para estacionar. Al colaborar, mejoramos la experiencia de todos.
                    </p>
                    <h3>¿Cómo funciona?</h3>
                    <ol>
                        <li>Los usuarios pueden reportar los lugares donde dejan su coche, 
                        ya sea ocupando un espacio o encontrando uno disponible.</li>
                        <li>Otros conductores podrán ver esta información y tomar 
                        decisiones sobre dónde estacionar.</li>
                        <li>
                        Todos los avisos pueden actualizarse o eliminarse según sea necesario para 
                         mantener la información precisa.
                        </li>
                    </ol>
                    <p>
                    ¡Juntos hacemos más fácil el día a día!
                    </p>
                </div>
        </div> ';
} else {
    // Si la sesión está iniciada
    header("Location: ../../index.html");
    
}
?>