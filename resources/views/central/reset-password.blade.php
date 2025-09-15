<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

@if(Auth::user() || !request()->query('token') || !request()->query('email'))
    @php
        header("Location: " . route('central.index'));
        exit();
    @endphp
@endif

<div class="p-8 md:p-16 xl:p-24  py-24 min-w-site text-sm font-medium min-h-full text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 relative">
    
    <a href="{{ route('central.login') }}" class="text-white cursor-pointer hover:border-white border hover:text-blue-600 transition-all duration-300 border-blue-600 px-3 py-1.5
     rounded-sm absolute top-8 right-8 "> Back to Login </a>
    
    <div class="mx-auto w-full max-w-2xl  grid place-items-center py-4">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-8">
            Reset Password </h2>
        
        <form action="{{ route('password.reset') }}" method="POST" class="w-full bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            @csrf
            
            <input type="hidden" name="token" value="{{ request()->query('token') }}"> <input type="hidden" name="email" value="{{ request()->query('email') }}">
            <div class="mb-6">
                <label for="password" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 cursor-pointer duration-200 transition-all hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Reset Password
                </button>
            </div>
        
        </form>
    
    </div>
    
    <x-notification/>

</div>
