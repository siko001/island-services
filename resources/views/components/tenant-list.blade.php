@if($tenants)
    <h1 class="font-bold text-2xl sm:text-3xl">Select an Application</h1>
    <ul id="tenant-list" class="mt-4 space-y-2 max-w-[350px] w-full">
        @foreach($tenants as $tenant)
            <li class="w-full relative tenant-item" data-id="{{ $tenant['id'] }}" tabindex="0" role="button">
                <div class="p-4 flex items-center gap-4 justify-between bg-white rounded-lg relative z-10 shadow hover:shadow-lg transition-all duration-200 border border-blue-900
                    cursor-pointer">
                    <p>{{ $tenant['id'] }}</p>
                    
                    <form id="delete-form-{{ $tenant['id'] }}" action="{{ route('tenancy.delete', ['id' => $tenant['id']]) }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                    
                    <button type="button" class="delete-btn text-red-500 cursor-pointer relative z-20  rounded px-2 py-1" title="Delete Application {{ $tenant['id'] }}" aria-label="Delete Application {{ $tenant['id'] }}" data-id="{{ $tenant['id'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </li>
        @endforeach
    </ul>
    
    <script>
		document.addEventListener('DOMContentLoaded', function() {
			setupTenantClickHandlers();
			setupDeleteButtonHandlers();
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

		// Attach click handlers to delete buttons with stopPropagation
		function setupDeleteButtonHandlers() {
			const deleteButtons = document.querySelectorAll('.delete-btn');
			deleteButtons.forEach(button => {
				const tenantId = button.dataset.id;

				button.addEventListener('click', event => {
					event.stopPropagation();
					confirmDeleteApp(tenantId);
				});
			});
		}

		// Confirmation and form submission for delete
		function confirmDeleteApp(appId) {
			if(confirm('You are about to delete Application ' + appId + ', Are you sure about that?')) {
				const form = document.getElementById(`delete-form-${appId}`);
				if(form) {
					form.submit();
				}
			}
		}
    </script>
@endif
