<div class="mt-16 mb-4">
    @if(!$tenants)
        @if(!Auth::user())
             @if(config('app.env') == "local")
                <h1 class="font-bold text-2xl sm:text-3xl dark:text-gray-200 text-gray-700">Run "<span class="text-blue-500">php artisan custom-tenant:create</span>" To create your first tenant </h1>
            @else 
                 <h1 class="font-bold text-2xl sm:text-3xl dark:text-gray-200 text-gray-700">Please Login to Create your First Application</h1>
            @endif
        @else
            <h1 class="font-bold text-2xl sm:text-3xl dark:text-gray-200 text-gray-700">Add Your First Application</h1>
        @endif
    @else
        @if(Auth::user())
            <h1 class="font-bold text-2xl sm:text-3xl  dark:text-gray-200 text-gray-700">Add Another Application</h1>
        @endif
    @endif
</div>
