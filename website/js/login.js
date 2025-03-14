// Cargar formularios
const loadForm = (container, url) => {
    return new Promise((resolve, reject) => {
        const request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.onload = () => {
            if (request.status === 200) {
                container.innerHTML = request.responseText;
                resolve();
            } else {
                container.innerHTML = '<p>Error al cargar el formulario. Intenta de nuevo.</p>';
                reject();
            }
        };
        request.onerror = () => { // Manejar errores de red
            container.innerHTML = '<p>Error de red al cargar el formulario.</p>';
            reject();
        };
        request.send();
    });
};

// Función para manejar respuestas del servidor
const ServerResponse = async (response, container) => {
    try {
        const text = await response.text();

        // Verificar si la respuesta es JSON válido
        if (!text.startsWith('{') && !text.startsWith('[')) {
            console.error('Respuesta del servidor:', text); // Para depuración
            throw new Error('La respuesta del servidor no es un JSON válido.');
        }

        const data = JSON.parse(text);

        if (data.success) {
            if (container) {
                loginFormContainer.remove();
                // Quitar las observaciones para optimizar
                if (typeof createobserver !== 'undefined' && createobserver !== null) {
                    createobserver.disconnect();
                }
                if (typeof loginobserver !== 'undefined' && loginobserver !== null) {
                    loginobserver.disconnect();
                }
                // Eliminar valores
                formData = new FormData();
                if (typeof passwordInput !== 'undefined' && passwordInput !== null) {
                    passwordInput.value = '';
                }
            }
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Hubo un problema con la respuesta del servidor');
    }
};
// delete login
const deletlogin = () => {
    const delet = loginFormContainer.querySelector('br#borrar');
    if (delet) {
        loginFormContainer.remove();
        // activar la pantalla de carga
        if (load === 0){
            loadDiv.style.display = "block";
            load = 1;
        }
    }
}; 

// variable de control
let load = 0;
// const divlogin and divcreate
const loginFormContainer = document.getElementById('formulario-login');
    if (loginFormContainer) {
        //cargar el form login
        loadForm(loginFormContainer, './php/create/login/login_form.php');
            // observer login 
            const loginobserver = new MutationObserver(() => {
                deletlogin();
                // formulario de login
                const form_log = document.querySelector('.loginForm');
                if (form_log) {
                    // a create -> create user
                    const createA = document.getElementById('Create_a');
                    createA.addEventListener('click', function(event) {
                        event.preventDefault();
                        form_log.remove()
                        // cargar formulario
                        loadForm(loginFormContainer, './php/create/login/create_form.php');
                        // observer create
                        createobserver.observe(document.body, { childList: true, subtree: true });
                    });
                    // submit login
                    form_log.addEventListener('submit', async (event) => {
                        event.preventDefault(); // Prevenir la recarga de la página
                    
                        // Obtener los valores del formulario
                        const formData = new FormData(form_log);
                    
                        // Verificar que los campos requeridos no estén vacíos
                        const username = formData.get('username');
                        const email = formData.get('email');
                        const password = formData.get('password');
                    
                        if (!username || !email || !password) {
                            alert('Por favor, completa todos los campos.');
                            return;
                        }
                    
                        // Enviar los datos al archivo PHP usando fetch
                        try {
                            const response = await fetch('./php/logic/login/login.php', {
                                method: 'POST',
                                body: formData
                            });
                    
                            // Verificar si la respuesta es válida
                            if (!response.ok) {
                                throw new Error(`Error en la solicitud: ${response.statusText}`);
                            }
                    
                            // Procesar la respuesta del servidor
                            await ServerResponse(response, loginFormContainer);
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Hubo un problema al enviar el formulario. Por favor, intenta de nuevo.');
                        }
                    });
                    loginobserver.disconnect();
                }
            });
            // iniciar observer
            loginobserver.observe(document.body, { childList: true, subtree: true });

            // observer create
            const createobserver = new MutationObserver(() => {
                deletlogin();
                const form_cre = document.querySelector('.createForm');
                if (form_cre) {
                    // a login -> login user
                    const loginA = document.getElementById('login_a');
                    loginA.addEventListener('click', function(event) {
                    event.preventDefault();
                        form_cre.remove()
                        // cargar formulario
                        loadForm(loginFormContainer, './php/create/login/login_form.php');
                        // observer login
                        loginobserver.observe(document.body, { childList: true, subtree: true });
                        createobserver.disconnect();
                    });
                    // submit create
                    form_cre.addEventListener('submit', async (event) => {
                        event.preventDefault(); // Prevenir la recarga de la página
                    
                        // Obtener los valores del formulario
                        const formData = new FormData(form_cre);
                        const passwordInput = document.getElementById("re_password");
                    
                        const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
                    
                        // Verificar que los campos requeridos no estén vacíos
                        if (!formData.get('username') || !formData.get('email') || !formData.get('password') || !passwordInput.value) {
                            alert('Por favor, completa todos los campos.');
                            return;
                        }
                    
                        if (!regex.test(formData.get('password'))) {
                            alert('La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una letra minúscula, un número y un carácter especial.');
                            return;
                        }
                    
                        if (formData.get('password') !== passwordInput.value) {
                            alert('Las contraseñas no coinciden.');
                            return;
                        }
                    
                        // Enviar los datos al archivo PHP usando fetch
                        try {
                            const response = await fetch('./php/logic/login/create.php', {
                                method: 'POST',
                                body: formData
                            });
                            await ServerResponse(response, loginFormContainer);
                        } catch (error) {
                            console.error('Error:', error);
                            alert('No se pudo enviar el formulario. Por favor, intenta de nuevo.');
                        }
                    });
                    createobserver.disconnect(); 
                }
            });
            // create observer
            createobserver.observe(document.body, { childList: true, subtree: true });
    }