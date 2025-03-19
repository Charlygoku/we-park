<?php
// formulario.php
session_start();

if (isset($_SESSION['username'])) {
    echo '<br id="borrar">';
} else {
    echo '
    <form class="createForm" method="POST">
        <h2>Crear una cuenta</h2>
        <label for="username">Nombre de Usuario:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required placeholder="ejemplo@dominio.com"><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" 
        id="password" 
        name="password" 
        required 
        minlength="8" 
        maxlength="16" 
        <br>
               
        <label for="re_password">Repetir Contraseña:</label><br>
        <input type="password" id="re_password" name="re_password" required><br><br>

        <button type="submit">Regístrate Ahora</button>

        <p>¿Ya tienes cuenta? <a href="" id="login_a"> Inicia sesión ahora</a></p>
    </form>';
}
?>