@if($tenants)
    <h1 class="font-bold text-2xl sm:text-3xl dark:text-gray-200 text-gray-700">Select an Application</h1>
    <ul id="tenant-list" class="mt-4 space-y-2 max-w-[350px] w-full">
        @foreach($tenants as $tenant)
            <li class="w-full relative tenant-item" data-id="{{ $tenant['id'] }}" tabindex="0">
                <div class="p-4 flex items-center gap-4 justify-between bg-white dark:bg-gray-900 rounded-lg relative z-10 shadow hover:shadow-lg transition-all duration-200
                border border-blue-900
                    cursor-pointer">
                    <div class="flex items-center gap-4">
                        <img width="32" src="{{$tenant['logo_path']}}" alt="Company Logo"/>
                        <p class="text-2xl dark:text-gray-200 text-gray-700">
                            {{ $tenant['id'] }}
                        </p>
                    </div>
                    
                    <a class="text-white" href="{{route('tenancy.edit',['id' => $tenant['id']]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                        </svg>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
    
    
    <script>
		document.addEventListener('DOMContentLoaded', function() {
			setupTenantClickHandlers();
		});

		// Attach click and keyboard (Enter) handlers to tenant items for navigation
		function setupTenantClickHandlers() {
			const tenantItems = document.querySelectorAll('#tenant-list .tenant-item');
			tenantItems.forEach(item => {
				const tenantId = item.dataset.id;
				const selectUrl = `{{ url('/tenancy/select') }}/${tenantId}`;

				item.addEventListener('click', () => {
					window.top.location.href = selectUrl;
				});

				// Also handle keyboard Enter key for accessibility
				item.addEventListener('keypress', event => {
					if(event.key === 'Enter' || event.key === ' ') {
						event.preventDefault();
						window.top.location.href = selectUrl;
					}
				});
			});
		}
    </script>
@endif
