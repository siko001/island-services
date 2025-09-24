document.addEventListener('inertia:navigate', () => {
	const configs = [
		{
			path: '/admin/resources/delivery-notes',
			relationship: 'deliveryNoteProducts',
			emptyDialogDusk: 'delivery-note-products-empty-dialog',
		},
		{
			path: '/admin/resources/direct-sales',
			relationship: 'directSaleProducts',
			emptyDialogDusk: 'direct-sale-products-empty-dialog',
		},
		{
			path: '/admin/resources/collection-notes',
			relationship: 'collectionNoteProducts',
			emptyDialogDusk: 'collection-note-products-empty-dialog',
		},
		// Add new configs here
	];

	// Find the matching config for current path
	const config = configs.find(cfg => window.location.pathname.startsWith(cfg.path));
	if(!config) return;

	let attempts = 0;
	const maxAttempts = 20;

	const resourceButtonChange = setInterval(() => {
		attempts++;

		const container = document.querySelector(`[data-relationship="${config.relationship}"]`);
		const buttonParent = container?.children[1]?.children[1]?.children[0]?.children[0];
		const desktopText = buttonParent?.children[0];
		const mobileText = buttonParent?.children[1];

		if(buttonParent && desktopText && mobileText) {
			desktopText.textContent = "Add Product";
			mobileText.textContent = "Add";

			const emptyDialog = document.querySelector(`[dusk="${config.emptyDialogDusk}"]`);
			if(emptyDialog) {
				const elementOne = emptyDialog.children[0].children[1];
				const elementTwo = emptyDialog.children[1];
				const desktopModalButton = elementTwo.children[0];
				const mobileModalButton = elementTwo.children[1];

				elementOne.textContent = "No Products Attached";
				desktopModalButton.textContent = "Add Product";
				mobileModalButton.textContent = "Add";

				clearInterval(resourceButtonChange);
			}

		}

		if(attempts >= maxAttempts) {
			clearInterval(resourceButtonChange);
			console.warn("Timed out waiting for order elements.");
		}
	}, 200);
});
