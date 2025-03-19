<?php
session_start(); // Iniciar la sesión

// Verificar si la sesión ha expirado
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['muteIncident'], $_SESSION['muteIncident_day'])) { 
        if ($_SESSION['muteIncident'] <= time() || $_SESSION['muteIncident_day'] < date('Y-m-d')) { 
            unset($_SESSION['muteIncident']); 
            unset($_SESSION['muteIncident_day']); 
        }
    }
}
?>

