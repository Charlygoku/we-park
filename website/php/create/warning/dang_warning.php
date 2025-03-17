<?php
// Iniciar la sesión
session_start();

// Comprobar si el usuario está autenticado
if (isset($_SESSION['username'])) {

    // Verificar si tiene mute activado (en función de los valores de la sesión)
    if (isset($_SESSION['muteIncident']) && isset($_SESSION['muteIncident_day'])) {
        // Si tiene mute, no mostrar el formulario de incidencia
        echo json_encode(['existe' => false, 'html' => '']);
    } else {
        // Si no tiene mute, enviar el formulario HTML
        $html = '
            <div class="adddel_warning">
                <h2>Incidencias</h2><br>
                <button id="no_warning" type="button">
                    <img src="img/close.svg" alt="Cerrar">
                </button>
                <form id="dangwarning">
                    <label for="tipo-via">Tipo de Vía:</label>
                    <br>
                    <select id="tipo-via" name="tipo-via" required>
                        <option value="">Seleccione</option>
                        <option value="calle">Calle</option>
                        <option value="avenida">Avenida</option>
                        <option value="plaza">Plaza</option>
                    </select>
                    <br>

                    <label for="nombre-via">Nombre de la Vía:</label>
                    <br>
                    <input type="text" id="nombre-via" name="nombre-via" placeholder="Nombre de la vía" required>
                    <br>

                    <label for="poblacion">Población:</label>
                    <br>
                    <input type="text" id="poblacion" name="poblacion" placeholder="Población" required>  
                    <br>

                    <label for="provincia">Provincia:</label>
                    <br>
                    <select id="provincia" name="provincia" required>
                        <option value="">Seleccione</option>
                        <option value="Madrid">Madrid</option>
                    </select>
                    <br>

                    <label for="comunidad">Comunidad Autónoma:</label>
                    <br>
                    <select id="comunidad" name="comunidad" required>
                        <option value="">Seleccione</option>
                        <option value="Comunidad de Madrid">Comunidad de Madrid</option>
                    </select>
                    <br>   

                    <label for="pais">País:</label>
                    <br>
                    <select id="pais" name="pais" required>
                        <option value="">Seleccione</option>
                        <option value="es">España</option>
                    </select>
                    <br>

                    <label for="NumPlazasModif">Numero de plazas a modificar:</label>
                    <br>
                    <input type="number" name="NumPlazasModif" id="NumPlazasModif" required>
                    <br>
                    <label for="observacion">Otras observacion (Opcional):</label><br>
                    <textarea name="observacion" id="observacion"></textarea><br>
                <br>
                <button id="yes_addwarning" type="submit">Guardar</button>
                <br>
                <button id="clear_addwarning" type="button">Limpiar</button>
                <br>
                <br>
            </form>
            </div>';
        echo json_encode(['existe' => true, 'html' => $html]);
    }
} else {
    // Si no hay sesión activa, redirigir al login
    header("Location: ../../index.html");
    exit();
}
?>
