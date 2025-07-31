<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif


<div class="p-4 sm:p-8 flex flex-col gap-4 sm:gap-8 ">
    @if($logs->isEmpty())
        <div class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200">
            <p class="text-gray-800 text-2xl font-bold">No logs found.</p>
        </div>
    @endif
    @foreach($logs as $log)
        <div class="bg-gray-100 sm:w-full p-4 py-8 sm:p-8 rounded-xl max-w-[850px] w-full mx-auto shadow">
            
            <div class="flex flex-col-reverse sm:flex-row items-start justify-between sm:items-center gap-2 sm:gap-8">
                <div>
                    <p>
                       <span class="font-semibold">
                           @if(!$log->success)
                               Attempted Email:
                           @else
                               Logged in Email:
                           @endif
                       </span>
                        {{$log->email}}
                    </p>
                </div>
                
                <div class="p-1 text-xs rounded-sm relative -top-2 sm:-right-2 {{$log->success ? 'bg-green-700' : 'bg-red-700'}} text-white">
                    <p>
                    {{$log->success ? 'Success' : 'Failed'}}
                    <p>
                </div>
            </div>
            
            <p>
                <span class="font-bold">IP Address: </span>
                {{$log->ip_address}}
                <br>
            </p>
            
            <p>
                <span class="font-bold">
                    Date:
                </span>
                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}
            </p>
            
            <p>
                
                <span class="font-bold">
                    Time:
                </span>
                {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
            </p>
            
            <p class="relative max-sm:left-0.5 -bottom-4 text-xs text-gray-500">
                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
            </p>
        </div>
    @endforeach
    
    
    {{-- Pagination Links --}}
    @if(!$logs->isEmpty())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>
