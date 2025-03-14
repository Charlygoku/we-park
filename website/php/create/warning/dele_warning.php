<?php
// Se inicia la sesión
session_start();
// Si la variable no es 0 muestra el formulario para quitarlo un aviso
if ($_SESSION['mark_id'] === 0) {
        echo json_encode(['existe' => false, 'html' => ""]);       
} else {
    $html ='
        <div class="sub_account">
            <h2>¿Seguro que quieres quitar el sitio?</h2>
            <br>
            <button id="delWarning">Si</button>
            <button id="no_warning">No</button>
        </div> ';
    echo json_encode(['existe' => true, 'html' => $html]);
}
?>