Nova.$on('resource-mounted', function(resource) {
	if(resource.resourceName === 'vehicles') {
		const vehicleId = resource.resourceId;
		Nova.request().get(`/nova-api/vehicles/${vehicleId}`).then(response => {
			const driverCount = response.data.drivers_count || 0;
			if(driverCount >= 2) {
				document.querySelector('.attach-driver-button')?.remove();
			}
		});
	}
})
