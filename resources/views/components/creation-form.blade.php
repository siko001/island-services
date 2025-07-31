<form action="{{ route('tenancy.store') }}" enctype="multipart/form-data" method="POST" class="mt-4 w-full max-w-[350px]">
    @csrf
    <div class="mb-4">
        <label for="tenant_id" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Application Name</label>
        <input type="text" name="tenant_id" id="tenant_id" required class="mt-1 p-3 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500
        focus:ring-blue-500
                sm:text-sm">
    </div>
    
    <div class="mb-4">
        <label for="admin_email" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Super Admin Email</label>
        <input type="text" name="admin_email" id="admin_email" required class="mt-1 p-3 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500
        focus:ring-blue-500
                sm:text-sm">
    </div>
    
    <div class="mb-4">
        <label for="admin_password" class="block text-sm font-medium dark:text-gray-200 text-gray-700"> Super Admin Password</label>
        <input type="text" name="admin_password" id="admin_password" required class="mt-1 p-3 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500
        focus:ring-blue-500
                sm:text-sm">
    </div>
    
    <div class="mb-4">
        <label for="logo_path" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Company Logo</label>
        <input type="file" name="logo_path" id="logo_path" required accept="image/png, image/jpeg" class="mt-1 p-3 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
    </div>
    
    <button type="submit" class="w-full cursor-pointer bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">Add Application</button>
    
    @if ($errors->any())
        <div class="mt-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
