<?php
session_start(); 

// Verificar si la sesión ha expirado
if (isset($_SESSION['muteReport'], $_SESSION['muteReport_day'])) {
    if ($_SESSION['muteReport'] <= time() || $_SESSION['muteReport_day'] < date('Y-m-d')) { 
        unset($_SESSION['muteReport']); 
        unset($_SESSION['muteReport_day']); 
    }
}
?>




