<?php
session_start();

// Verificar si la variable de sesión que indica si el usuario está logueado existe
if (isset($_SESSION["username"])) {
    if (isset($_SESSION["muteReport"]) && isset($_SESSION["muteReport_day"])) {
        echo json_encode(['existe' => false, 'html' => '']); 
    } else {
        // Si la sesión está iniciada
        $username = $_SESSION["username"];
        $email = $_SESSION["email"];
        $html = "
            <div class='bug'>
                <h2>Reportes</h2>
                <button id='closebug' type='button'>
                        <img src='img/close.svg' alt='Cerrar'>
                    </button>
                <form id='BugForm' method='POST' enctype='multipart/form-data'>
                    <label for='username'>Nombre de Usuario:</label><br>
                    <input type='text' id='username' name='username' value='$username ' readonly><br>
        
                    <label for='email'>Correo Electrónico:</label><br>
                    <input type='email' id='email' name='email' value='$email' readonly><br>
        
                    <label for='Tit_bug'>Título del Bug:</label><br>
                    <input type='text' id='Tit_bug' name='Tit_bug' required><br>
        
                    <label for='Descripcion'>Descripción:</label><br>
                    <textarea name='Descripcion' id='Descripcion' required></textarea><br>
        
                    <label for='Fecha'>Fecha del incidente:</label><br>
                    <input type='date' id='Fecha' name='Fecha' required><br>
        
                    <label for='Hora'>Hora del incidente:</label><br>
                    <input type='time' id='Hora' name='Hora' required><br>
        
                    <label for='system'>Sistema Operativo Utilizado:</label><br>
                    <select name='system' id='system' required>
                        <option value=''>Seleccione una opción</option>
                        <option value='Android'>Android</option>
                        <option value='iPhone'>iPhone</option>
                        <option value='Windows'>Windows</option>
                        <option value='Linux'>Linux</option>
                    </select><br>
        
                    <label for='browser'>Navegador Web Utilizado:</label><br>
                    <select name='browser' id='browser' required>
                        <option value=''>Seleccione una opción</option>
                        <option value='Microsoft Edge'>Microsoft Edge</option>
                        <option value='Chrome'>Chrome</option>
                        <option value='Firefox'>Firefox</option>
                        <option value='Safari'>Safari</option>
                        <option value='Opera'>Opera</option>
                        <option value='Brave'>Brave</option>
                    </select><br>
        
                    <label for='device'>Dispositivo Utilizado:</label><br>
                    <input type='text' id='device' name='device' required><br>
        
                    <label for='img'>Datos Adjuntos (Opcional):</label><br>
                    <input type='file' id='img' name='img' accept='image/*'><br><br>
        
                    <button type='submit' id='savebug'>Guardar</button><br>
                    <button type='button' id='clearebug'>Limpiar</button>
                </form>
            </div>";   
        echo json_encode(['existe' => true, 'html' => $html]);   
    }
} else {
    // Si no está logueado, mostrar error
    header("Location: ../../index.html");
}
?>