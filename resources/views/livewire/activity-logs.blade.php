<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Logs List Pane -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">System Activity Logs</h3>
                    <p class="text-xs text-zinc-500">Monitor all actions, database updates, and administrator security audits.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Search -->
                    <input type="text" wire:model.live="search" class="px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Search logs or causer name...">
                </div>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                <select wire:model.live="logNameFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All Categories/Log Names</option>
                    @foreach($logNames as $name)
                        <option value="{{ $name }}">{{ $name ?: 'Uncategorized' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium text-xs uppercase tracking-wider">
                            <th class="py-2.5">Time</th>
                            <th class="py-2.5">Category</th>
                            <th class="py-2.5">Causer</th>
                            <th class="py-2.5">Description</th>
                            <th class="py-2.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                <td class="py-3 text-xs text-zinc-500 whitespace-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="py-3 text-xs">
                                    <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-zinc-800 dark:text-zinc-300">
                                        {{ $log->log_name ?: 'General' }}
                                    </span>
                                </td>
                                <td class="py-3 text-xs font-semibold text-zinc-900 dark:text-white">
                                    @if($log->causer)
                                        {{ $log->causer->name }}
                                        <span class="block text-[10px] text-zinc-500 font-normal">{{ class_basename($log->causer_type) }} (ID: {{ $log->causer_id }})</span>
                                    @else
                                        <span class="text-zinc-400 italic">System / Guest</span>
                                    @endif
                                </td>
                                <td class="py-3 text-xs text-zinc-750 dark:text-zinc-350">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3">
                                    <button wire:click="selectLog({{ $log->id }})" class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 text-zinc-750 dark:text-zinc-200 rounded text-xs font-semibold">
                                        Inspect
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-zinc-500 text-sm">
                                    No activity logs found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </div>

        <!-- Details Pane -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
            @if($selectedLog)
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Log Details</h3>
                    <button wire:click="closeDetails" class="text-xs text-zinc-500 hover:text-zinc-750 dark:hover:text-white font-semibold">
                        Close
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Description</label>
                        <p class="text-sm text-zinc-800 dark:text-zinc-200 mt-1">{{ $selectedLog->description }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Category</label>
                        <p class="text-sm text-zinc-800 dark:text-zinc-200 mt-1">{{ $selectedLog->log_name ?: 'General' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Timestamp</label>
                        <p class="text-sm text-zinc-800 dark:text-zinc-200 mt-1">{{ $selectedLog->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Causer (User performing action)</label>
                        <p class="text-sm text-zinc-800 dark:text-zinc-200 mt-1">
                            @if($selectedLog->causer)
                                <strong>{{ $selectedLog->causer->name }}</strong> ({{ $selectedLog->causer->email ?: $selectedLog->causer->phone }})
                            @else
                                <span class="text-zinc-400 italic">System / Guest</span>
                            @endif
                        </p>
                    </div>

                    @if($selectedLog->subject)
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 uppercase">Subject (Target resource)</label>
                            <p class="text-sm text-zinc-800 dark:text-zinc-200 mt-1">
                                <strong>{{ class_basename($selectedLog->subject_type) }}</strong> (ID: {{ $selectedLog->subject_id }})
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Properties / Metadata</label>
                        @if($selectedLog->properties)
                            <pre class="w-full p-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs font-mono text-zinc-800 dark:text-zinc-200 overflow-x-auto whitespace-pre-wrap mt-1">{{ json_encode($selectedLog->properties, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <p class="text-xs text-zinc-500 italic mt-1">No additional metadata properties recorded.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-zinc-500 text-sm">
                    Select a log entry from the list to view its complete properties, causer, and metadata details.
                </div>
            @endif
        </div>
    </div>
</div>
