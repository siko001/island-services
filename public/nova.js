document.addEventListener("DOMContentLoaded", function() {
	const dynamicBrandingToNova = () => {
		const sideBar = document.querySelector('[dusk="sidebar-menu"]');
		const firstItem = sideBar.children[0].children[0].children[0];
		const secondItem = sideBar.children[1].children[0].children[0];


		const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
					   <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
					 </svg>
					`

		if(firstItem) {
			const href = sideBar.children[0].children[0].getAttribute('href');
			firstItem.classList.remove('w-6', "h-6")
			firstItem.classList.add('w-20');
			const urlObj = new URL(href, window.location.origin);
			const logoPathEncoded = urlObj.searchParams.get('logopath');
			const logoPath = decodeURIComponent(logoPathEncoded);

			const img = document.createElement('img');
			img.src = logoPath;
			img.alt = 'Tenant Logo';
			img.style.width = '100px';
			img.style.height = '28px';
			img.style.objectFit = 'contain';
			firstItem.prepend(img);
		}


		if(secondItem) {
			const template = document.createElement('template');
			template.innerHTML = svg.trim();
			const svgElement = template.content.firstChild;
			secondItem.prepend(svgElement);
		}
	}
	setTimeout(() => {
		dynamicBrandingToNova();
	}, 1)
});

//
// // Customer billing address conditional display
// const conditional = () => {
// 	const checkbox = document.querySelector('[dusk="different_billing_details-update-customer-boolean-field"]')
// 	const billingDetailsContainer = document.querySelector('[aria-label="Billing Details"]')
// 	if(checkbox && billingDetailsContainer) {
// 		checkbox.addEventListener('click', function(e) {
// 			isShowing()
// 		});
// 		// Trigger the change event to set the initial state
// 		checkbox.dispatchEvent(new Event('change'));
//
//
// 		const isShowing = () => {
//
// 			if(checkbox.getAttribute('checkedvalue') === 'true') {
// 				billingDetailsContainer.parentElement.parentElement.style.display = 'block';
// 				checkbox.classList.add('custom-js-loaded')
// 			} else {
// 				billingDetailsContainer.parentElement.parentElement.style.display = 'none';
// 				checkbox.classList.add('custom-js-loaded')
// 			}
//
// 		}
//
// 		isShowing();
// 	}
// }
// const checkboxConditionalInterval = setInterval(() => {
// 	conditional();
// 	if(document.querySelector('[dusk="different_billing_details-update-customer-boolean-field"]')?.classList.contains('custom-js-loaded')) {
// 		clearInterval(checkboxConditionalInterval);
// 		console.log('Custom JS for billing details loaded successfully.');
// 	}
// }, 10);
