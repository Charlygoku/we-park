// Mis sitios
if (warningButton) {
    warningButton.addEventListener('click', () => {
        // quitar estilos de botones activos
        ButtonOff();
        // añadir al boton el estilo activo
        warningImg.src = './img/CarActive.svg';
        warningP.style.color = "#050000";
        // eliminar clases 
        removeClass();
        // desacticar el menu lateral
        menuLat();
            if (warningCount === true) {
                // cargar formulario
                loadForm(menuOpcion, './php/create/warning/warning_from.php')
                .then(() => {
                    // variable de control
                    controlTrue();
                    warningCount = false;
                    // agregar clase
                    menuOpcion.classList.add('warning');
                });
                // Observador de warning
                const WarningObserver = new MutationObserver ( () => {
                    const addWarning = document.getElementById('green');
                    const delWarning = document.getElementById('red');
                    const timWarning = document.getElementById('blue');
                    const danWarning = document.getElementById('yellow');
                    const clooseWarning = document.getElementById('closewarning');
                    // salir del menu
                    clooseWarning.addEventListener('click', function(event){
                        event.preventDefault();
                      	removeClass();
                        warningCount = true;
                        WarningObserver.disconnect();
                        // quitar estilos de botones activos
                        ButtonOff();
                    });
                    // añadir aviso
                    addWarning.addEventListener('click', () => {
                        removeClass();
                        // comprobar si ya tiene id de markadores
                        fetch('./php/create/warning/add_warning.php')
                        .then(response => response.json())
                        .then(data => {
                        if (data.existe) {
                            removeClass();
                            // mensaje al usuario 
                            alert('Ya tienes un parking asignado');
                            // cargar formulario
                            loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                .then(() => {
                                // variable de control
                                controlTrue();
                                warningCount = false;
                            });
                            // Observador addWarning
                            AddWarningObserver.disconnect();
                            // Observador Warning
                            WarningObserver.observe(document.body, { childList: true, subtree: true });
                            // agregar clase
                            menuOpcion.classList.add('warning');
                        } else {
                            // Inserta el HTML
                            menuOpcion.innerHTML = data.html; 
                        }
                        })
                        .catch(error => {
                          console.error('Error:', error);
                        });
                        // Observador de addwarnig
                        const AddWarningObserver = new MutationObserver ( () => {
                            if (menuOpcion.innerHTML !== '') {   
                                // variable de control
                                warningCount = true;
                                // agregar clase
                                menuOpcion.classList.add('menu_opcion');
                                // constante del formulario
                                const addWarning = document.getElementById('addwarning');
                                const clooseWarning = document.getElementById('no_warning');
                                const clearaddwarning = document.getElementById('clear_addwarning');
                                // limpiar formulario
                                clearaddwarning.addEventListener('click', function(event){
                                    event.preventDefault();
                                    addWarning.reset();
                                }); 
                                // añadir aviso
                                addWarning.addEventListener('submit', function (event) {
                                    event.preventDefault(); 
                                    // Obtén los datos del formulario
                                    const formData = new FormData(addWarning); 
                            
                                    // Enviar los datos del formulario al servidor
                                    fetch('./php/logic/warning/addwarning.php', {
                                        method: 'POST',
                                        body: formData,
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            
                                            // quitar clase
                                            removeClass();
                                            warningCount = true;
                                            
                                            // añadir formulario de agradecimiento
                                            menuOpcion.classList.add('submenu-wrapper');
                                            loadForm(menuOpcion, './php/create/warning/add_warning_thank.php');
                                            setTimeout(() => {
                                                if (menuOpcion.classList.contains('submenu-wrapper')) {
                                                    // quitar clase
                                                    removeClass();
                                                }
                                            }, 15000);
                                            
                                        } else {
                                            alert('Error: ' + result.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Ocurrió un error al enviar el reporte: ' + error.message);
                                    });
                                });
                                // cerrar formulario
                                clooseWarning.addEventListener('click', function (event) {
                                    event.preventDefault(); 
                                    // quitar clase
                                    removeClass();
                                    // cargar formulario
                                    loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                    .then(() => {
                                        // variable de control
                                        controlTrue();
                                        warningCount = false;
                                    });
                                    // Observador Warning
                                    WarningObserver.observe(document.body, { childList: true, subtree: true });
                                    // agregar clase
                                    menuOpcion.classList.add('warning');
                                    AddWarningObserver.disconnect();
                                });
                            }
                            AddWarningObserver.disconnect();
                        });
                        AddWarningObserver.observe(document.body, { childList: true, subtree: true });
                    });
                // eliminar aviso
                delWarning.addEventListener('click', () => { 
                    removeClass();
                    // comprobar si ya tiene id de markadores
                    fetch('./php/create/warning/dele_warning.php')
                    .then(response => response.json())
                    .then(data => {
                    if (data.existe) {
                        menuOpcion.innerHTML = data.html; // Inserta el HTML
                    } else {
                        // eliminar clases
                        removeClass();
                        // alerta al usuario
                        alert('No tienes un parking asignado');
                        // cargar formulario
                        loadForm(menuOpcion, './php/create/warning/warning_from.php')
                            .then(() => {
                            // variable de control
                            controlTrue();
                            warningCount = false;
                        });
                        // Observador addWarning
                        DelWarningObserver.disconnect();
                        // Observador Warning
                        WarningObserver.observe(document.body, { childList: true, subtree: true });
                        // agregar clase
                        menuOpcion.classList.add('warning');
                    }
                    })
                    .catch(error => {
                      console.error('Error:', error);
                    });

                    // Observador de delwarnig
                    DelWarningObserver = new MutationObserver ( () => {
                        if (menuOpcion.innerHTML !== '') {
                        // variable de control
                        warningCount = true;
                        // agregar clase
                        menuOpcion.classList.add('menu_item');
                            // constante del formulario
                            const delWarning = document.getElementById('delWarning');
                            const cloosedelWarning = document.getElementById('no_warning'); 
                            // eliminar sitio
                            delWarning.addEventListener('click', function (event) {
                                event.preventDefault(); 
                                // Valor que se enviará al servidor
                                const delewarningvalue = 1;   
                                // Enviar los datos al archivo PHP usando fetch
                                    fetch('./php/logic/warning/delwarning.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded', 
                                        },
                                        body: `delewarningvalue=${encodeURIComponent(delewarningvalue)}`, 
                                    })
                                    .then(response => response.json()) 
                                    .then(data => {
                                        if (data.success) {
                                            removeClass()
                                            warningCount = true; 
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error); 
                                    });   
                            });        
                            // cerrar formulario
                            cloosedelWarning.addEventListener('click', function (event) {
                                event.preventDefault(); 
                                // quitar clase
                                removeClass();
                                // cargar formulario
                                loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                .then(() => {
                                    // variable de control
                                    controlTrue();
                                    warningCount = false;
                                });
                                // Observador Warning
                                WarningObserver.observe(document.body, { childList: true, subtree: true });
                                // agregar clase
                                menuOpcion.classList.add('warning');
                                DelWarningObserver.disconnect();
                            });
                        }
                        DelWarningObserver.disconnect();
                    });    
                    DelWarningObserver.observe(document.body, { childList: true, subtree: true });
                    });
                    // tiempo de espera
                    timWarning.addEventListener('click', () => {
                        removeClass();
                        
                        // comprobar si ya tiene id de markadores
                        fetch('./php/create/warning/time_warning.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                menuOpcion.innerHTML = data.html; // Inserta el HTML
                            } else {
                                removeClass();
                                alert('No tienes un parking asignado');
                                // cargar formulario
                                loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                    .then(() => {
                                    // variable de control
                                    controlTrue();
                                    warningCount = false;
                                    
                                });
                                // Observador addWarning
                                TimWarningObserver.disconnect();
                                // Observador Warning
                                WarningObserver.observe(document.body, { childList: true, subtree: true });
                                // agregar clase
                                menuOpcion.classList.add('warning');
                            }
                        })
                        .catch(error => {
                          console.error('Error:', error);
                        });
                        // Observador de Timwarnig
                        TimWarningObserver = new MutationObserver ( () => { 
                            if (menuOpcion.innerHTML !== '') {
                                // variable de control
                                warningCount = true;
                                // agregar clase
                                menuOpcion.classList.add('submenu-wrapper');
                
                                //constante de los botones del formulario
                                const timWarning = document.getElementById('timWarningForm');
                                const cloosedelWarning = document.getElementById('no_warning');
                                // Salgo en ...
                                timWarning.addEventListener('submit', function (event) {
                                    event.preventDefault(); 

                                    // Obtén los datos del formulario
                                    const formData = new FormData(timWarning); 

                                    // Enviar los datos del formulario al servidor
                                    fetch('./php/logic/warning/timWarning.php', {
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
                                    warningCount = true;
                                });
                                // cerrar formulario
                                cloosedelWarning.addEventListener('click', function (event) {
                                    event.preventDefault(); 
                                    // quitar clase
                                    removeClass();
                                    // cargar formulario
                                    loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                    .then(() => {
                                        // variable de control
                                        controlTrue();
                                        warningCount = false;
                                    });
                                    // Observador Warning
                                    WarningObserver.observe(document.body, { childList: true, subtree: true });
                                    // agregar clase
                                    menuOpcion.classList.add('warning');
                                    TimWarningObserver.disconnect();
                                });
                            }
                            TimWarningObserver.disconnect();
                        });
                        TimWarningObserver.observe(document.body, { childList: true, subtree: true });
                    });
                    // incidencias
                    danWarning.addEventListener('click', () => {
                        removeClass();
                        
                        // Comprobar si tiene mute activado
                        fetch('./php/create/warning/dang_warning.php')
                            .then(response => response.json())
                            .then(data => {
                                if (data.existe) {
                                    menuOpcion.innerHTML = data.html; // Inserta el HTML que devuelve el PHP
                                } else {
                                    removeClass(); 
                                    alert('Tiempo de espera para la proxima incidencia');
                                    loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                        .then(() => {
                                            // Variable de control
                                            controlTrue();
                                            warningCount = false;
                                        });
                                    
                                    // Desconectar el observador addWarning
                                    DanWarningObserver.disconnect();
                                    
                                    // Observar cambios en el DOM para los warnings
                                    WarningObserver.observe(document.body, { childList: true, subtree: true });
                                    
                                    // Agregar clase 'warning' al menú de opciones
                                    menuOpcion.classList.add('warning');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error); // Manejo de errores
                            });
                        // Observador de addwarnig
                        DanWarningObserver = new MutationObserver ( () => {
                            if (menuOpcion.innerHTML !== '') {
                                // variable de control
                                warningCount = true;
                                // agregar clase
                                menuOpcion.classList.add('menu_opcion');
                                // constantes del formulario
                                const dangWarning = document.getElementById('dangwarning');
                                const cloosedelWarning = document.getElementById('no_warning');
                                const clearaddwarning = document.getElementById('clear_addwarning');
                                // limpiar formulario
                                clearaddwarning.addEventListener('click', function(event){
                                    event.preventDefault();
                                    dangWarning.reset();
                                }); 
                                // subir reporte
                                dangWarning.addEventListener('submit', function (event) {
                                    event.preventDefault(); 

                                    // Obtén los datos del formulario
                                    const formData = new FormData(dangWarning); 

                                    // Enviar los datos del formulario al servidor
                                    fetch('./php/logic/warning/danWarning.php', {
                                        method: 'POST',
                                        body: formData,
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        if (result.success) {
                                            // quitar clase
                                            removeClass();
                                            warningCount = true;

                                            // añadir formulario de agradecimiento
                                            menuOpcion.classList.add('submenu-wrapper');
                                            loadForm(menuOpcion, './php/create/warning/dang_warning_thank.php');
                                            setTimeout(() => {
                                                if (menuOpcion.classList.contains('submenu-wrapper')) {
                                                    // quitar clase
                                                    removeClass();
                                                }
                                            }, 15000);
                                            
                                        } else {
                                            alert('Error: ' + result.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Ocurrió un error al enviar el reporte: ' + error.message);
                                    });
                                    
                                });
                            
                                cloosedelWarning.addEventListener('click', function (event) {
                                    event.preventDefault(); 
                                    // quitar clase
                                    removeClass();

                                    loadForm(menuOpcion, './php/create/warning/warning_from.php')
                                    .then(() => {
                                        // variable de control
                                        controlTrue();
                                        warningCount = false;
                                    });
                                    // Observador Warning
                                    WarningObserver.observe(document.body, { childList: true, subtree: true });
                                    // agregar clase
                                    menuOpcion.classList.add('warning');
                                    DanWarningObserver.disconnect();
                                });
                                DanWarningObserver.disconnect();
                                }
                            });
                        DanWarningObserver.observe(document.body, { childList: true, subtree: true });     
                    });
                    WarningObserver.disconnect();
                });
                WarningObserver.observe(document.body, { childList: true, subtree: true });
            } else {
                removeClass();
                ButtonOff();
                warningCount = true;
            }
    });
}