document.addEventListener('DOMContentLoaded', () => {
	const notification = document.getElementById('notification');
	if(notification) {
		requestAnimationFrame(() => {
			notification.classList.remove('translate-y-20', 'opacity-0');
			notification.classList.add('translate-y-0', 'opacity-100');
		});
		setTimeout(() => {
			notification.classList.remove('translate-y-0', 'opacity-100');
			notification.classList.add('translate-y-20', 'opacity-0');
			notification.addEventListener('transitionend', () => {
				notification.remove();
			}, {once: true});
		}, 3000);
	}
});
