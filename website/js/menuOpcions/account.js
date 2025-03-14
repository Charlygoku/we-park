// Selecciona los elementos del DOM
const menuOpcion = document.getElementById('menu_opcion');

const accountButton = document.getElementById('account');
const accountImg = accountButton.querySelector('img');
const accountP = accountButton.querySelector('p');

const warningButton = document.getElementById('warning');
const warningImg = warningButton.querySelector('img');
const warningP = warningButton.querySelector('p')

const bugButton =  document.getElementById('bug');
const bugImg = bugButton.querySelector('img');
const bugP = bugButton.querySelector('p');

const infoButton = document.getElementById('info');
const infoImg = infoButton.querySelector('img');
const infoP = infoButton.querySelector('p');

const button_lat = document.getElementById('bot_men_lat');
const butMenLat = document.getElementById('bot_men_lat');
const divMenLat = document.getElementById('men_lat');

// Inicializa la variable de estado
let accountCount = true; 
let warningCount = true;
let bugCount = true; 
let infoCount = true; 
let ControlParkCount = true;

//FUNCIONES

    // Quitar clases y vaciar el contenido
    const removeClass = () => {
        menuOpcion.innerHTML = '';
        menuOpcion.classList.remove('menu_item');
        menuOpcion.classList.remove('menu_opcion');
        menuOpcion.classList.remove('warning');
        menuOpcion.classList.remove('submenu-wrapper');
    }
    // botones desactivados 
    const ButtonOff = () => {
        accountImg.src = './img/Account.svg';
        accountP.style.color = "#6E6E6E";
        warningImg.src = './img/Car.svg';
        warningP.style.color = "#6E6E6E";
        bugImg.src = './img/Bug.svg';
        bugP.style.color = "#6E6E6E";
        infoImg.src = './img/Search.svg';
        infoP.style.color = "#6E6E6E";
    }
    // menu lateral
    const menuLat = () => {
        if (isMenuVisible === true) {
            isMenuVisible = false;
            divMenLat.classList.remove('aparicion_menu_lat');
            divMenLat.classList.add('aparicion_menu_lat_inv');
            butMenLat.classList.remove('deslizamiento_boton_lat');
            butMenLat.classList.add('deslizamiento_boton_lat_inv');
    }
    }
    // variables de control a true
    const controlTrue = () => {
        accountCount = true;
        warningCount = true;
        bugCount = true;
        infoCount = true;
        ControlParkCount = true;
    }

//MENU LATERAL
button_lat.addEventListener('click', () => {
    removeClass();
    controlTrue();
    ButtonOff();

});

// Mi cuenta
if (accountButton) {
    accountButton.addEventListener('click', () => {
        // quitar estilos de botones activos
        ButtonOff();
        // añadir al boton el estilo activo
        accountImg.src = './img/AccountActive.svg';
        accountP.style.color = "#050000";
        // eliminar clases
        removeClass();
        menuLat();
        if (accountCount === true) {
            // Cargar formulario
            loadForm(menuOpcion, './php/create/account/account_from.php')
                .then(() => {
                    // variable de control
                    controlTrue();
                    accountCount = false;
                    // agregar clase
                    menuOpcion.classList.add('menu_opcion');
                        
                });
                // Observador de account
                const accountObserver = new MutationObserver( () => {
                    const closeaccount = document.getElementById('closeaccount')
                    const deleteaccount = document.getElementById('deleteaccount')
                    const exitaccount = document.getElementById('no_account')
                        // Salir del menu
                        exitaccount.addEventListener('click' , function(event){
                            event.preventDefault();
                            removeClass();
                            accountCount = true;
                            accountObserver.disconnect();
                            // quitar estilos de botones activos
                            ButtonOff();
                        });
                        // Cerrar sesion
                        closeaccount.addEventListener('click' , function(event){
                            event.preventDefault();
                            removeClass();
                            // cargar formulario
                            loadForm(menuOpcion, './php/create/account/close_from.php');
                            // variable de control
                            accountCount = true;
                            //agregar clase
                            menuOpcion.classList.add('menu_item');
                            const close_from = new MutationObserver(()=> {
                                const noclose = document.getElementById('noclose')
                                const yesclose = document.getElementById('yesclose')
                                    // No cerrar sesion
                                    noclose.addEventListener('click' , function(event){
                                        event.preventDefault();
                                        // eliminar clases
                                        removeClass();
                                        // cargar formulario
                                        loadForm(menuOpcion, './php/create/account/account_from.php');
                                        // cambiar varible de control
                                        accountCount = false;
                                        // agregar clase
                                        menuOpcion.classList.add('menu_opcion');
                                        // Observador de account
                                        accountObserver.observe(document.body, { childList: true, subtree: true });
                                    });
                                    // Si cerrar sesion
                                    yesclose.addEventListener('click' , function(event){
                                        event.preventDefault();
                                        // Valor que se enviará al servidor
                                        const closevalue = 1; 
                                        
                                        // Enviar los datos al archivo PHP usando fetch
                                            fetch('./php/logic/login/close.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded', 
                                                },
                                                body: `closevalue=${encodeURIComponent(closevalue)}`, 
                                            })
                                            .then(response => response.json()) 
                                            .then(data => {
                                                if (data.success) {
                                                    if (data.redirect) {
                                                        window.location.href = data.redirect; 
                                                        
                                                    } else {
                                                        location.reload(); 
                                                    }
                                                } else {
                                                    alert('Error: ' + data.message); 
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error); 
                                            });   
                                    });
                                    close_from.disconnect();
                            });
                            // iniciar observer
                            close_from.observe(document.body, { childList: true, subtree: true });
                        });
                        // Eliminar cuenta
                        deleteaccount.addEventListener('click' , function(event){
                            event.preventDefault();
                            removeClass();
                            // cargar formulario
                            loadForm(menuOpcion, './php/create/account/delete_from.php');
                            // variable de control
                            accountCount = true;
                            //agregar clase
                            menuOpcion.classList.add('menu_item');
                            const delete_from = new MutationObserver(()=> {
                                const nodelete = document.getElementById('nodelete')
                                const yesdelete = document.getElementById('yesdelete')
                                    // No eliminar cuenta
                                    nodelete.addEventListener('click' , function(event){
                                        event.preventDefault();
                                        removeClass();
                                        // cargar formulario
                                        loadForm(menuOpcion, './php/create/account/account_from.php');
                                        // cambiar varible de control
                                        accountCount = false;
                                        // agregar clase
                                        menuOpcion.classList.add('menu_opcion');
                                        // Observador de account
                                        accountObserver.observe(document.body, { childList: true, subtree: true });
                                        // Observador de delete
                                        delete_from.disconnect();
                                    });
                                    // Si eliminar cuenta
                                    yesdelete.addEventListener('click' , function(event){
                                        event.preventDefault();
                                        // Valor que se enviará al servidor
                                        const deletevalue = 1; 
                                        
                                        // Enviar los datos al archivo PHP usando fetch
                                            fetch('./php/logic/login/delete.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded', 
                                                },
                                                body: `deletevalue=${encodeURIComponent(deletevalue)}`, 
                                            })
                                            .then(response => response.json()) 
                                            .then(data => {
                                                if (data.success) {
                                                    if (data.redirect) {
                                                        window.location.href = data.redirect; 
                                                        
                                                    } else {
                                                        location.reload(); 
                                                    }
                                                } else {
                                                    alert('Error: ' + data.message); 
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error); 
                                            });
                                            delete_from.disconnect();
                                    });
                            });
                            delete_from.observe(document.body, { childList: true, subtree: true });     
                        });
                accountObserver.disconnect();
                });
                accountObserver.observe(document.body, { childList: true, subtree: true });
    } else {
        removeClass();
        ButtonOff();
        accountCount = true;
    }
    });
}