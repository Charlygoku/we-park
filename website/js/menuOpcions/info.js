// Acerca de mi
if (infoButton) {
    infoButton.addEventListener('click', () => {
        // quitar estilos de botones activos
        ButtonOff();
        // añadir al boton el estilo activo
        infoImg.src = './img/SearchActive.svg';
        infoP.style.color = "#050000";
        // eliminar clases 
        removeClass();
        // desacticar el menu lateral
        if (infoCount === true) {
            // Cargar formulario
            loadForm(menuOpcion, './php/create/info/info_from.php')
                .then(() => {
                    // variable de control
                    controlTrue();
                    infoCount = false;
                    // agregar clase
                    menuOpcion.classList.add('menu_opcion');
                });
                // Observador de informacion
                const InfoObserver = new MutationObserver( () => {
                    const closeinfo = document.getElementById('closeinfo');
                    // cerrar menu
                    closeinfo.addEventListener('click', () => {
                        removeClass();
                        infoCount = true;
                        InfoObserver.disconnect();
                        // quitar estilos de botones activos
                        ButtonOff()
                    });
                    InfoObserver.disconnect();
                });
                InfoObserver.observe(document.body, { childList: true, subtree: true });
        } else {
            removeClass();
            ButtonOff();
            infoCount = true;
        }
    });
}