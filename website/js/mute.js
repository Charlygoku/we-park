const checkSession = () => {
    // checkSessionReport: Función que consulta al servidor si la sesión sigue activa
    const checkSessionReport = () =>  {
        fetch('./php/logic/mute/mute_report.php')
            .catch(error => console.error('Error al ejecutar el PHP:', error));
    };
    
    // checkSessionIncident: Función que consulta al servidor si la sesión sigue activa
    const checkSessionIncident = () => {
        fetch('./php/logic/mute/mute_incident.php')
            .catch(error => console.error('Error al ejecutar el PHP:', error));
    };
    
    // Llamar a la función para ejecutar el PHP
    checkSessionIncident();
    checkSessionReport();
}

checkSession();

// Ejecutar la función cada 1 minuto 
setInterval(checkSession , 60000);


