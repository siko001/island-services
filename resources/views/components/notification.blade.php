@if (session('message'))
    <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg transform translate-y-20 opacity-0 transition-all duration-500 ease-in-out" style="will-change: transform, opacity;">
        {{ session('message') }}
    </div>
    
    
    
    <script>
		const notification = document.getElementById('notification');
		// Slide up & fade in
		requestAnimationFrame(() => {
			notification.classList.remove('translate-y-20', 'opacity-0');
			notification.classList.add('translate-y-0', 'opacity-100');
		});
		// Slide down & fade out after 3 seconds
		setTimeout(() => {
			notification.classList.remove('translate-y-0', 'opacity-100');
			notification.classList.add('translate-y-20', 'opacity-0');
			notification.addEventListener('transitionend', () => {
				notification.remove();
			});
		}, 3000);
    </script>

@endif
