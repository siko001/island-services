@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
<div class="p-8 md:p-16 xl:p-24  min-w-site text-sm font-medium min-h-full text-gray-500 relative dark:text-gray-400 bg-gray-100 dark:bg-gray-900 ">
    
    <div class="mx-auto w-full max-w-2xl grid place-items-center    gap-4  py-4">
        <div class="w-full max-w-2xl grid place-items-center  gap-4 ">
            <div class="flex items-center gap-2  w-1/2  ">
                <span class="font-bold">Name:</span>
                <p class="text-2xl">{{ $tenant->id }}</p>
            </div>
            <div class="flex items-center gap-2  w-1/2">
                <span class="font-bold"> Logo: </span><img width=" 124" alt="logo_path" src="{{ $tenant->logo_path }}" class="font-bold"></img>
            </div>
            
            <form action="{{route("tenancy.update", ["id" => $tenant->id])}}" enctype="multipart/form-data" method="POST" class="mt-10 ">
                @csrf
                @method('PUT')
                <div class="mb-4 ">
                    <label for="tenant_id" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Application Name</label>
                    <input type="text" name="tenant_id" id="tenant_id" value="{{ $tenant->id }}" required class="mt-1 p-3 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                
                <div class="mb-4">
                    <label for="logo_path" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Company Logo</label>
                    <input type="file" name="logo_path" id="logo_path" accept="image/png, image/jpeg" value="{{ $tenant->logo_path }}" class="mt-1 p-3 block w-full border
                    border-gray-300 rounded-md shadow-sm
                    focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                
                @if($errors->any())
                    <div class="mt-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="w-1/4 flex items-center gap-4 mt-10 ">
                    <a class="py-1.5 px-3 border rounded-sm" href="{{route("central.index")}}"> Back </a>
                    <button type="submit" class="cursor-pointer bg-blue-600 text-white py-1.5 px-3 rounded-md hover:bg-blue-700 transition duration-200"> Save</button>
                </div>
            </form>
        </div>
    
    </div>
    
    <x-notification/>
    
    <div class="absolute top-8 right-8">
        <x-delete-tenancy :tenant="$tenant"/>
    </div>

</div>
