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
               pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[.,_-#@$!%*?&\[\]()]).{8,}" 
               title="La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial."
               <br>
               
        <label for="re_password">Repetir Contraseña:</label><br>
        <input  type="password" id="re_password" name="re_password" required><br><br>

        <button type="submit">Registrate Ahora</button>

        <p>¿Ya tienes cuenta? <a href="" id="login_a"> Inicia sesion ahora</a>
        </p>
        

    </form>';
}
?>