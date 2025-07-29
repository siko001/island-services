<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

<div class="p-8 md:p-16 xl:p-24  min-w-site text-sm font-medium min-h-full text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 ">
    
    <div class="mx-auto w-full max-w-2xl  grid place-items-center py-4">
        
        <x-tenant-list :tenants="$tenants"/>
        
        <x-form-heading :tenants="$tenants"/>
        
        <x-creation-form :tenants="$tenants"/>
    
    </div>
    
    <x-notification/>

</div>
