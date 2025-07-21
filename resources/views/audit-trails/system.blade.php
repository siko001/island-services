<!-- Styles / Scripts -->
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

<div class="p-8">
    @if($logs->isEmpty())
        <div class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200">
            <p class="text-gray-800 text-2xl font-bold">No logs found.</p>
        </div>
    @endif
    @foreach($logs as $index => $log)
        <div class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200 ">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-800">
                    Log #{{ $log->id }}: {{ $log->name }}
                </h3>
                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-medium">
                    {{ ucfirst($log->status) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <div>
                        <span class="font-medium">
                            Batch ID:
                        </span>
                        {{ $log->batch_id }}
                    </div>
                    <div>
                        <span class="font-medium">
                            User ID:
                        </span>
                        {{ $log->user_id }}
                    </div>
                    <div>
                        <span class="font-medium">
                            User Name:
                        </span>
                        {{ $log->username }}
                    </div>
                    <div>
                        <span class="font-medium">
                            Actionable:
                        </span>
                        {{ class_basename($log->actionable_type) }} #{{ $log->actionable_id }}
                    </div>
                    <div>
                        <span class="font-medium">
                            Target:
                        </span>
                        {{ class_basename($log->target_type) }} #{{ $log->target_id }}
                    </div>
                    <div>
                        <span class="font-medium">
                            Data:
                        </span>
                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                    </div>
                </div>
                <div>
                    <div>
                        <span class="font-medium">Created:</span>
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y : H:i:s') }}</div>
                    <div>
                        <span class="font-medium">Updated:</span>
                        {{ \Carbon\Carbon::parse($log->updated_at)->format("d/m/Y : H:i:s") }}</div>
                    <div>
                        <span class="font-medium">Exception:</span>
                        @if($log->exception)
                            <span class="text-red-600">{{ $log->exception }}</span>
                        @else
                            <span class="text-gray-400">None</span>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($log->changes && $log->changes !== "[]")
                
                @php
                    $changes = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                @endphp
                <div class="mt-4">
                    <div class="font-semibold text-gray-800 mb-1">Changes:</div>
                    <div class="bg-gray-50 rounded p-3 text-xs">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($changes as $key => $value)
                                <li>
                                    <span class="font-medium">{{ ucfirst($key) }}:</span>
                                    <span class="text-gray-700">
                                    @if(is_bool($value))
                                            {{ $value ? 'true' : 'false' }}
                                        @else
                                            {{ $value }}
                                        @endif
                                </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
        
    {{-- Pagination Links --}}
    @if(!$logs->isEmpty())
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</div>
