const burgerMenu = document.getElementById('burger-menu');
const mainMenu = document.getElementById('main-menu');

const openMenu = () => {
	mainMenu.classList.remove('hidden', 'h-0');
	mainMenu.classList.add('flex', 'h-auto');
};

const closeMenu = () => {
	mainMenu.classList.add('hidden', 'h-0');
	mainMenu.classList.remove('flex', 'h-auto');
};

const mainMenuClick = (e) => {
	if(burgerMenu.contains(e.target)) {
		// Toggle menu on burger button click
		if(mainMenu.classList.contains('hidden')) {
			openMenu();
		} else {
			closeMenu();
		}
	}
};

const handleOutsideClick = (event) => {
	const isClickInside = burgerMenu.contains(event.target) || mainMenu.contains(event.target);
	if(!isClickInside && !mainMenu.classList.contains('hidden')) {
		closeMenu();
	}
};

// listeners
burgerMenu.addEventListener('click', mainMenuClick);
window.addEventListener('click', handleOutsideClick);
