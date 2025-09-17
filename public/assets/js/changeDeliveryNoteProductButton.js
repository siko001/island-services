// Modify the button to attach product to delivery not (original: create)
document.addEventListener('inertia:navigate', (event) => {
	if(window.location.pathname.startsWith('/admin/resources/delivery-notes')) {
		let attempts = 0;
		const maxAttempts = 50; // stop after ~5 seconds

		const resourceButtonChange = setInterval(() => {
			attempts++;

			const container = document.querySelector('[data-relationship="deliveryNoteProducts"]');
			const buttonParent = container?.children[1]?.children[1]?.children[0]?.children[0];
			const desktopText = buttonParent?.children[0];
			const mobileText = buttonParent?.children[1];

			if(buttonParent && desktopText && mobileText) {
				clearInterval(resourceButtonChange);
				desktopText.textContent = "Add Product";
				mobileText.textContent = "Add";
			}

			if(attempts >= maxAttempts) {
				clearInterval(resourceButtonChange);
				console.warn("Timed out waiting for deliveryNoteProducts button elements.");
			}
		}, 100);
	}
});
