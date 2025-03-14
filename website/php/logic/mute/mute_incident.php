<?php
session_start(); // Iniciar la sesión

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['muteIncident'], $_SESSION['muteIncident_day'])) { 
        $muteIncidentExpired = $_SESSION['muteIncident'] <= time();
        $muteIncidentDayExpired = $_SESSION['muteIncident_day'] < date('Y-m-d');

        if ($_SESSION['muteIncident'] <= time() || $_SESSION['muteIncident_day'] <= date('Y-m-d')) { 
            unset($_SESSION['muteIncident']); 
            unset($_SESSION['muteIncident_day']); 
        }
    }
}
?>
