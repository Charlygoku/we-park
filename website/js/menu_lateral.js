function displayMenu_lat_on() { 
    const div_menu_lat = document.getElementById('men_lat');
    div_menu_lat.style.display = 'block'; 
}


document.addEventListener('DOMContentLoaded', function() {
    // Tu código JavaScript aquí
    const button_menu_lat = document.getElementById('bot_men_lat');
    const div_menu_lat = document.getElementById('men_lat');
    let isMenuVisible = false;

    button_menu_lat.addEventListener('click', () => {
        isMenuVisible = !isMenuVisible;

        if (isMenuVisible) {
            displayMenu_lat_on()
            div_menu_lat.classList.add('aparicion_menu_lat');
            div_menu_lat.classList.remove('aparicion_menu_lat_inv');
            button_menu_lat.classList.add('deslizamiento_boton_lat');
            button_menu_lat.classList.remove('deslizamiento_boton_lat_inv');

        } else {
            div_menu_lat.classList.remove('aparicion_menu_lat');
            div_menu_lat.classList.add('aparicion_menu_lat_inv');
            button_menu_lat.classList.remove('deslizamiento_boton_lat');
            button_menu_lat.classList.add('deslizamiento_boton_lat_inv');

        }
    });
});
