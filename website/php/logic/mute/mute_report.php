<?php
session_start(); // Iniciar la sesión

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['muteReport'], $_SESSION['muteReport_day'])) { 
        $muteReportExpired = $_SESSION['muteReport'] <= time();
        $muteReportDayExpired = $_SESSION['muteReport_day'] < date('Y-m-d');

        if ($_SESSION['muteReport'] <= time() || $_SESSION['muteReport_day'] <= date('Y-m-d')) { 
            unset($_SESSION['muteReport']); 
            unset($_SESSION['muteReport_day']); 
        }
    }
}
?>


