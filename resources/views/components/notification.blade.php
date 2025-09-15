@if (session('message'))
    @php
        $message = strtolower(session('message'));
        $isError = str_contains($message, 'invalid') || str_contains($message, 'error');
        $bgClass = $isError ? 'bg-red-500' : 'bg-green-500';
    @endphp
    
    <div id="notification" class="fixed bottom-4 right-4 {{ $bgClass }} text-white px-4 py-2 rounded-md shadow-lg transform translate-y-20 opacity-0 transition-all duration-500 ease-in-out" style="will-change: transform, opacity;">
        {{ session('message') }}
    </div>
    
    <script>
		const notification = document.getElementById('notification');
		requestAnimationFrame(() => {
			notification.classList.remove('translate-y-20', 'opacity-0');
			notification.classList.add('translate-y-0', 'opacity-100');
		});
		setTimeout(() => {
			notification.classList.remove('translate-y-0', 'opacity-100');
			notification.classList.add('translate-y-20', 'opacity-0');
			notification.addEventListener('transitionend', () => {
				notification.remove();
			});
		}, 3000);
    </script>
@endif
