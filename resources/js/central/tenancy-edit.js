const itemToCopy = document.getElementById('api-token');

itemToCopy.addEventListener('click', () => {
	const text = document.getElementById('api_token')?.value || '';

	if(navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(text).then(() => {
			showNotification();
		}).catch(err => {
			console.error('Could not copy text:', err);
		});
	} else {
		// fallback for unsupported browsers
		const tempInput = document.createElement('input');
		tempInput.value = text;
		document.body.appendChild(tempInput);
		tempInput.select();
		document.execCommand('copy');
		document.body.removeChild(tempInput);
		showNotification();
	}
});

let notificationTimeout;

function showNotification() {
	let notification = document.getElementById('api-notification');

	if(!notification) {
		// create it once
		notification = document.createElement('div');
		notification.id = 'api-notification';
		notification.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-300 transform translate-y-20 opacity-0';
		notification.textContent = 'Copied to clipboard!';
		document.body.appendChild(notification);
	}

	// clear any previous timeout so spamming doesn't stack
	clearTimeout(notificationTimeout);

	// reset animation
	requestAnimationFrame(() => {
		notification.classList.remove('translate-y-20', 'opacity-0');
		notification.classList.add('translate-y-0', 'opacity-100');
	});

	// schedule fade out
	notificationTimeout = setTimeout(() => {
		notification.classList.remove('translate-y-0', 'opacity-100');
		notification.classList.add('translate-y-20', 'opacity-0');
	}, 2000);
}


const askRegen = () => {
	if(confirm('Are you sure you want to regenerate the API Token?\nPlease be sure to insert the new token in the corresponding website')) {
		document.getElementById('regenForm').submit();
	}
}
