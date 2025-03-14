<?php
// Se inicia la sesión
session_start();
// Si la variable es 0 (default) se muestra el formulario para añadir un aviso
if ($_SESSION['mark_id'] === 0) {  
        echo json_encode(['existe' => false, 'html' => '']);   
} else {
    $html = "
    <div class='timeWarning'>
        <h3>Salgo en...</h3>
        <form id='timWarningForm'>
        <label for='tiempo'>Tiempo estimado:</label>
        <br>
        <select id='tiempo' name='tiempo'>
            <option value='5'>5 minutos</option>
            <option value='10'>10 minutos</option>
            <option value='15'>15 minutos</option>
            <option value='30'>30 minutos</option>
            <option value='1'>1 hora</option>
            <option value='2'>2 horas</option>
        </select>
        <br>
        <button type='submit' id='timWarning'>Guardar</button>
        <br>
        <button type='button' id='no_warning'>Cancelar</button>
    </form>
    </div>";
    echo json_encode(['existe' => true, 'html' => $html]);
}
?>
