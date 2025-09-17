document.addEventListener('DOMContentLoaded', function() {
	setupDeleteButtonHandlers();
});


function setupDeleteButtonHandlers() {
	const deleteButtons = document.querySelectorAll('.delete-btn');
	deleteButtons.forEach(button => {
		const tenantId = button.dataset.id;


		button.addEventListener('click', event => {
			event.stopPropagation();
			event.preventDefault();

			const confirmed = confirm(`You are about to delete Application ${tenantId}. Are you sure?`);
			if(confirmed) {
				const form = document.getElementById(`delete-form-${tenantId}`);
				if(form) {
					form.submit();
				}
			}
		});
	});
}
