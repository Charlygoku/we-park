<?php
// Iniciar la sesión al inicio del archivo PHP
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION['username'])) {
    // Si la sesión está iniciada
    echo '<br id=borrar >';

} else {
    // Si no está logueado, mostrar el formulario de inicio de sesión
    echo '
    <form class="loginForm" method="POST">
        <h2>Inicio de Sesión</h2>
        <label for="username">Nombre de Usuario</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Correo Electronico:</label><br>
        <input type="text" id="email" name="email" required><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Acceder</button>

        <p>¿Aun no tienes cuenta? <a href="" id="Create_a"> Create una cuenta ahora</a>
        </p>
        
        

    </form>';
}
?>