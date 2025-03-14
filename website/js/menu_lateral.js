const buttonMenuLat = document.getElementById('bot_men_lat');
const divMenuLat = document.getElementById('men_lat');
const imgMenuLat = document.getElementById('img_men_lat');

// variable de control 
var isMenuVisible = false;

//animaciones 
buttonMenuLat.addEventListener('click', () => {
        isMenuVisible = !isMenuVisible;

        if (isMenuVisible) {
            divMenuLat.style.display = 'block';
            imgMenuLat.src = './img/menu_on.svg';
            divMenuLat.classList.add('aparicion_menu_lat');
            divMenuLat.classList.remove('aparicion_menu_lat_inv');
            buttonMenuLat.classList.add('deslizamiento_boton_lat');
            buttonMenuLat.classList.remove('deslizamiento_boton_lat_inv');
        } else {
            imgMenuLat.src = './img/menu_off.svg';
            divMenuLat.classList.remove('aparicion_menu_lat');
            divMenuLat.classList.add('aparicion_menu_lat_inv');
            buttonMenuLat.classList.remove('deslizamiento_boton_lat');
            buttonMenuLat.classList.add('deslizamiento_boton_lat_inv');
        }
});
