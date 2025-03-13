
// Verifica que el div con id 'formulario-login' esté disponible
const loginFormContainer = document.getElementById('formulario-login');

if (loginFormContainer) {
    const loginFormRequest = new XMLHttpRequest();
    loginFormRequest.open('GET', './php/create/login_form.php', true);
    loginFormRequest.onload = function() {
        if (loginFormRequest.status === 200) {
            loginFormContainer.innerHTML = loginFormRequest.responseText;
            

        } else {
            loginFormContainer.innerHTML = '<p>Error al cargar el formulario. Intenta de nuevo.</p>';
        }
    };
    loginFormRequest.send();

} else {
    console.error("No se encontró el div con id 'formulario-login'");
}



// Mutacion login
const observer = new MutationObserver(() => {
    const delet = loginFormContainer.querySelector('br#borrar');
        if (delet) {
            loginFormContainer.remove()
        }
    const form_log = document.querySelector('.loginForm');
        if (form_log) {
            // a create -> create user
            const createA = document.getElementById('Create_a');

            createA.addEventListener('click', function(event) {
                event.preventDefault();
                form_log.remove()

                if (loginFormContainer) {
                    const CreateFormRequest = new XMLHttpRequest();
                    CreateFormRequest.open('GET', './php/create/create_form.php', true);
                    CreateFormRequest.onload = function() {
                        if (CreateFormRequest.status === 200) {
                            loginFormContainer.innerHTML = CreateFormRequest.responseText;
                
                        } else {
                            loginFormContainer.innerHTML = '<p>Error al cargar el formulario. Intenta de nuevo.</p>';
                        }
                    };
                    CreateFormRequest.send();
                } else {
                    console.error("No se encontró el div con id 'formulario-login'");
                }
            });

            // submit login
            form_log.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevenir la recarga de la página

                    // Obtener los valores de usuario y contraseña
                    const formData = new FormData(form_log); // Recolecta automáticamente los datos del formulario
                    const username = formData.get('username'); // Asegúrate que el input tenga el atributo `name="username"`
                    const password = formData.get('password'); // Asegúrate que el input tenga el atributo `name="password"`

                    // Verificar que los campos requeridos no estén vacíos (opcional)
                    if (!username || !password) {
                        alert('Por favor, completa todos los campos.');
                        return;
                    }

                    // Enviar los datos al archivo PHP usando fetch
                    fetch('./php/logic/login.php', {
                        method: 'POST',
                        body: formData,
                    })
                        .then(response => response.text()) // Cambiar .json() por .text() para ver el contenido bruto
                        .then(text => {
                            console.log('Respuesta del servidor:', text); // Imprimir la respuesta del servidor en la consola
                    
                            try {
                                const data = JSON.parse(text); // Intentar convertir la respuesta en JSON
                                console.log('Datos JSON:', data);
                    
                                if (data.success) {
                                    alert('Inicio de sesión exitoso');
                                    
                                    if (loginFormContainer) {
                                        loginFormContainer.remove(); // Elimina el div con id="formulario-login"
                                        // quitar las opservaciones para optimizar
                                        observer.disconnect(); 
                                        DOMobserver.disconnect();


                                    }
                                } else {
                                    alert('Error en inicio de sesión: ' + data.message);
                                }
                            } catch (error) {
                                console.error('Error al parsear el JSON:', error);
                            }
                        })
                        .catch(error => {
                            console.error('Error al enviar los datos:', error);
                        });
            });
            DOMobserver.observe(document.body, { childList: true, subtree: true });
            observer.disconnect(); 
            
        } else {    }  
});

// Inicia la observación de cambios en el cuerpo del documento
observer.observe(document.body, { childList: true, subtree: true });


// Mutacion create
const DOMobserver = new MutationObserver(() => {
    const delet = loginFormContainer.querySelector('br#borrar');
        if (delet) {
            loginFormContainer.remove()
        }
    const form_cre = document.querySelector('.createForm');
        if (form_cre) {
            // a login -> login user
            const loginA = document.getElementById('login_a');

            loginA.addEventListener('click', function(event) {
                event.preventDefault();
                form_cre.remove()

                if (loginFormContainer) {
                    const loginFormRequest = new XMLHttpRequest();
                    loginFormRequest.open('GET', './php/create/login_form.php', true);
                    loginFormRequest.onload = function() {
                        if (loginFormRequest.status === 200) {
                            loginFormContainer.innerHTML = loginFormRequest.responseText;
                
                        } else {
                            loginFormContainer.innerHTML = '<p>Error al cargar el formulario. Intenta de nuevo.</p>';
                        }
                    };
                    loginFormRequest.send();
                } else {
                    console.error("No se encontró el div con id 'formulario-login'");
                }
            });

            // submit create
            
            form_cre.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevenir la recarga de la página

                const passwordInput = document.getElementById("re_password");
                const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
                    
                    // Obtener los valores de usuario y contraseña
                    const formData = new FormData(form_cre); // Recolecta automáticamente los datos del formulario
                    const username = formData.get('username'); 
                    const email = formData.get('email');
                    const password = formData.get('password'); 

                    // Verificar que los campos requeridos no estén vacíos (opcional)
                    if (!username || !email || !password) {
                        alert('Por favor, completa todos los campos.');
                        return;
                    }

                    if (!regex.test(password) || password !== passwordInput.value) {
                        alert('La contraseña no cumple los requisitos o las contraseñas no coinciden.');
                        return; // Salir del evento

                    } else {
                        
                        // Enviar los datos al archivo PHP usando fetch
                        fetch('./php/logic/create.php', {
                            method: 'POST',
                            body: formData,
                        })
                            .then(response => response.text()) // Cambiar a .json() si el servidor devuelve JSON
                            .then(text => {
                                console.log('Respuesta del servidor:', text); // Imprimir la respuesta del servidor en la consola
                            
                                try {
                                    const data = JSON.parse(text); // Intentar convertir la respuesta en JSON
                                    console.log('Datos JSON:', data);
                                
                                    if (data.success) {
                                        alert('Creación de sesión exitosa');
                                            loginFormContainer.remove();
                                            observer.disconnect();
                                            DOMobserver.disconnect();
                                    } else {
                                        alert('Error en la creación de sesión: ' + (data.message || 'Error desconocido.'));
                                    }
                                } catch (error) {
                                    console.error('Error al parsear el JSON:', error);
                                    alert('Hubo un problema con la respuesta del servidor.');
                                }
                            })
                            .catch(error => {
                                console.error('Error al enviar los datos:', error);
                                alert('No se pudo enviar el formulario. Por favor, intenta de nuevo.');
                            });
                    }      
            });
            observer.observe(document.body, { childList: true, subtree: true });
            DOMobserver.disconnect();
        } else {   }  
});







