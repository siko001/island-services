@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

<div class="p-8 md:p-16 py-20  min-w-site text-sm font-medium min-h-full text-gray-500 relative dark:text-gray-400 bg-gray-100 dark:bg-gray-900 ">
    
    <div class="mx-auto w-full max-w-2xl grid place-items-center    gap-4  py-12">
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
                
                <div class="text-blue-700 text-lg pb-6">Tenancy Info</div>
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
                
                <div class="text-blue-700 text-lg py-6">Website Connection Info</div>
                <div class="mb-4 ">
                    <label for="sage_api_username" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Sage Username</label>
                    <input type="text" name="sage_api_username" id="sage_api_username" value="{{ $tenant->sage_api_username }}" class="mt-1 p-3 block w-full border
                    border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                
                <div class="mb-4 ">
                    <label for="sage_api_password" class="block text-sm font-medium dark:text-gray-200 text-gray-700">Sage Password</label>
                    <input type="password" name="sage_api_password" id="sage_api_password" value="{{ $tenant->sage_api_password }}" class="mt-1 p-3 block w-full border
                    border-gray-300
                    rounded-md
                    shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                
                <div id="api-token" class="mb-4 relative w-full hover:cursor-copy group">
                    <label for="api_token" class="block hover:cursor-copy text-sm font-medium dark:text-gray-200 text-gray-700">Api Token</label>
                    <input readonly name="api_token" class="mt-1
                     p-3 block w-full border border-gray-300 hover:cursor-copy rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500
                    sm:text-sm
                    " value="{{$tenant->api_token}}" id="api_token">
                    <div class="absolute left-[98%] top-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20 bg-green-500 rounded-sm border border-green-500
                    p-0.5
                    text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                        </svg>
                    
                    </div>
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
                    <a onClick="askRegen()" class="cursor-pointer whitespace-nowrap bg-yellow-500 text-black py-1.5 px-3 rounded-md hover:bg-yellow-600
                    transition duration-200"> Regenerate API Token </a>
                    <button type="submit" class="cursor-pointer bg-blue-600 text-white py-1.5 px-3 rounded-md hover:bg-blue-700 transition duration-200"> Save</button>
                    <a class="py-1.5 px-3 border rounded-sm text-white hover:bg-gray-800 duration-200 transition-colors " href="{{route("central.index")}}"> Back </a>
                </div>
            </form>
            
            <form id="regenForm" action="{{ route('token.regen', ['id' => $tenant->id]) }}" method="POST" style="display:none;">
                @csrf
            </form>
            
            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    
    </div>
    
    <x-notification/>
    
    <div class="absolute top-8 right-8">
        <x-delete-tenancy :tenant="$tenant"/>
    </div>
    
    <div id="api-notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg transform translate-y-20 opacity-0 transition-all duration-500
    ease-in-out" style="will-change: transform, opacity;">
        Api Token Copied
    </div>

</div>

<script>
	const itemToCopy = document.getElementById('api-token');

	itemToCopy.addEventListener('click', () => {
		const text = document.getElementById('api_token')?.value || '';

		if(navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(() => {
				showNotification();
			}).catch(err => {
				console.error('Could not copy text:', err);
			});
		} else {
			// fallback for unsupported browsers
			const tempInput = document.createElement('input');
			tempInput.value = text;
			document.body.appendChild(tempInput);
			tempInput.select();
			document.execCommand('copy');
			document.body.removeChild(tempInput);
			showNotification();
		}
	});

	let notificationTimeout;

	function showNotification() {
		let notification = document.getElementById('api-notification');

		if(!notification) {
			// create it once
			notification = document.createElement('div');
			notification.id = 'api-notification';
			notification.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-300 transform translate-y-20 opacity-0';
			notification.textContent = 'Copied to clipboard!';
			document.body.appendChild(notification);
		}

		// clear any previous timeout so spamming doesn't stack
		clearTimeout(notificationTimeout);

		// reset animation
		requestAnimationFrame(() => {
			notification.classList.remove('translate-y-20', 'opacity-0');
			notification.classList.add('translate-y-0', 'opacity-100');
		});

		// schedule fade out
		notificationTimeout = setTimeout(() => {
			notification.classList.remove('translate-y-0', 'opacity-100');
			notification.classList.add('translate-y-20', 'opacity-0');
		}, 2000);
	}


	const askRegen = () => {
		if(confirm('Are you sure you want to regenerate the API Token?\nPlease be sure to insert the new token in the corresponding website')) {
			document.getElementById('regenForm').submit();
		}
	}
</script>
