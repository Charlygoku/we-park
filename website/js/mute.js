const checkSession = () => {
    // Función general que consulta al servidor si la sesión sigue activa
    const checkSessionStatus = (url) => {
        fetch(url)
            .catch(error => console.error(`Error al ejecutar el PHP en ${url}:`, error));
    };
    
    checkSessionStatus('./php/logic/mute/mute_report.php');
    checkSessionStatus('./php/logic/mute/mute_incident.php');
};

checkSession();

// Ejecutar la función cada 1 minuto
setInterval(checkSession, 60000);


