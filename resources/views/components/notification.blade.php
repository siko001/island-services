@if (session('message'))
    @php
        $message = strtolower(session('message'));
        $isError = str_contains($message, 'invalid') || str_contains($message, 'error');
        $bgClass = $isError ? 'bg-red-500' : 'bg-green-500';
    @endphp
    
    <div id="notification" class="fixed bottom-4 right-4 {{ $bgClass }} text-white px-4 py-2 rounded-md shadow-lg transform translate-y-20 opacity-0 transition-all duration-500 ease-in-out" style="will-change: transform, opacity;">
        {{ session('message') }}
    </div>
    
    @vite('resources/js/central/notification.js')
@endif
