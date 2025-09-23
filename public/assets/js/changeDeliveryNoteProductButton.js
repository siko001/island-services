// Modify the button to attach product to delivery not (original: create)
document.addEventListener('inertia:navigate', (event) => {
	if(window.location.pathname.startsWith('/admin/resources/delivery-notes') || window.location.pathname.startsWith('/admin/resources/direct-sales')) {
		let attempts = 0;
		const maxAttempts = 50; // stop after ~5 seconds

		const resourceButtonChange = setInterval(() => {
			attempts++;

			let container;
			const deliveryNoteContainer = document.querySelector('[data-relationship="deliveryNoteProducts"]');
			const directSaleContainer = document.querySelector('[data-relationship="directSaleProducts"]');

			deliveryNoteContainer ? container = deliveryNoteContainer : null;
			directSaleContainer ? container = directSaleContainer : null;

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
