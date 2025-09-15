<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

<div class="p-8 md:p-16 xl:p-24  py-24 min-w-site text-sm font-medium min-h-full text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 relative">
    
    <a href="{{ route('central.index') }}" class="text-white cursor-pointer hover:border-white border hover:text-blue-600 transition-all duration-300 border-blue-600 px-3 py-1.5
     rounded-sm absolute top-8 right-8 "> Back to Home </a>
    
    <div class="mx-auto w-full max-w-2xl  grid place-items-center py-4">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-8">
            Account Settings </h2>
        
        <form action="{{ route('user.update', ["id" => Auth::id()]) }}" method="POST" class="w-full bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Name:</label>
                <input type="name" id="name" name="name" value="{{$user->name}}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none
                focus:ring
                focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{$user->email}}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none
                focus:ring
                focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="mb-6">
                <label for="old_password" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Current Password:</label>
                <input type="old_password" id="old_password" name="old_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring
                focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">New Password:</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring
                focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none
                focus:ring focus:border-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            
            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 cursor-pointer duration-200 transition-all hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Account Settings
                </button>
            </div>
        
        </form>
    
    </div>
    
    <x-notification/>
</div>
