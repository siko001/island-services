document.addEventListener('inertia:navigate', () => {
	const configs = [
		{
			name: "Delivery Note",
			path: '/admin/resources/delivery-note-products',
			createContainer: 'create-delivery-note-product-panel',
		},
		{
			name: "Direct Sale",
			path: '/admin/resources/direct-sale-products',
			createContainer: 'create-direct-sale-product-panel',
		},
		{
			name: "Collection Note",
			path: '/admin/resources/collection-note-products',
			createContainer: 'create-collection-note-product-panel',
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
		const createContainer = document.querySelector(`[dusk="${config.createContainer}"]`);

		if(createContainer) {
			const heading = document.querySelector(`[dusk="${config.createContainer}-heading"]`);
			const createNdAdd = document.querySelector('[dusk="create-and-add-another-button"]')
			const createButton = document.querySelector('[dusk="create-button"]')

			heading.textContent = `Add a Product to ${config.name}`
			createNdAdd.children[0].textContent = `Add This & Add Another`;
			createButton.children[0].textContent = `Add Product`;
			clearInterval(resourceButtonChange);
		}

		if(attempts >= maxAttempts) {
			clearInterval(resourceButtonChange);
			console.warn("Timed out waiting for order elements.");
		}
	}, 200);
});
