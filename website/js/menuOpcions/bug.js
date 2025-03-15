// Mi Reporte
if (bugButton) {
    bugButton.addEventListener('click', () => {
        // quitar estilos de botones activos
        ButtonOff();
        // añadir al boton el estilo activo
        bugImg.src = './img/BugActive.svg';
        bugP.style.color = "#050000";
        // eliminar clases 
        removeClass();
        // desacticar el menu lateral
        if (bugCount === true) {
            // Comprobar si tiene mute activado
            fetch('./php/create/bug/bug_from.php')
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        menuOpcion.innerHTML = data.html; // Inserta el HTML que devuelve el PHP
                    } else {
                        removeClass(); 
                        alert('Tiempo de espera para el proximo reporte');
                            // Variable de control
                            removeClass();
                            controlTrue();
                            // quitar estilos de botones activos
                            ButtonOff();
                          
                        // Desconectar el observador bug
                        BugObserver.disconnect();
                    }
                })
                .catch(error => {
                    console.error('Error:', error); 
                });
            const BugObserver = new MutationObserver( () => {
                if (menuOpcion.innerHTML !== '') {
                    // variable de control
                    controlTrue();
                    bugCount = false;
                    // agregar clase
                    menuOpcion.classList.add('menu_opcion');
                    // constates del formulario
                    const closebug = document.getElementById('closebug');
                    const BugForm = document.getElementById('BugForm');
                    const clearbug = document.getElementById('clearebug');
                    // limpiar formulario
                    clearbug.addEventListener('click', function(event){
                        event.preventDefault();
                        BugForm.reset();
                    });
                    // subir reporte
                    BugForm.addEventListener('submit', function (event) {
                        event.preventDefault(); 
                        // Obtén los datos del formulario
                        const formData = new FormData(BugForm); 
                        // Enviar los datos del formulario al servidor
                        fetch('./php/logic/bug/bug.php', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                            } else {
                                alert('Error: ' + result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Ocurrió un error al enviar el reporte: ' + error.message);
                        });
                        // quitar clase
                        removeClass();
                        menuOpcion.classList.add('submenu-wrapper');
                            loadForm(menuOpcion, './php/create/bug/bug_thanks.php');
                            // quitar estilos de botones activos
                            ButtonOff();
                            setTimeout(() => {
                                if (menuOpcion.classList.contains('submenu-wrapper')) {
                                    // quitar clase
                                    removeClass();
                                }
                            }, 15000); 
                        bugCount = true;
                });
                // cerrar formulario
                closebug.addEventListener('click' , function(event){
                    event.preventDefault();
                    removeClass();
                    bugCount = true;
                    BugObserver.disconnect();
                    // quitar estilos de botones activos
                    ButtonOff();
                });
                }
                BugObserver.disconnect();
            });  
            BugObserver.observe(document.body, { childList: true, subtree: true });     
        } else {
            removeClass();
            ButtonOff();
            bugCount = true;
        }
    });
}
