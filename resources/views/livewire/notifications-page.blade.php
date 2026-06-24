<div class="space-y-6">

    {{-- Header row --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-xs text-zinc-500 mt-0.5">Your task assignment, completion, and verification alerts.</p>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="typeFilter"
                class="px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-700 dark:text-zinc-300 focus:outline-none">
                <option value="">All Types</option>
                <option value="task_assigned">Task Assigned</option>
                <option value="task_completed">Task Completed</option>
                <option value="task_verified">Task Verified</option>
                <option value="general">General</option>
            </select>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="px-3 py-1.5 text-xs font-semibold bg-green-600 hover:bg-green-700 text-white rounded-lg whitespace-nowrap">
                    Mark All Read ({{ $unreadCount }})
                </button>
            @endif
        </div>
    </div>

    {{-- Notifications list --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
        @forelse($notifications as $notif)
            @php $data = $notif->data; @endphp
            <div wire:key="{{ $notif->id }}"
                class="flex items-start gap-4 px-5 py-4 border-b border-zinc-50 dark:border-zinc-800/60 last:border-b-0 transition-colors
                    {{ is_null($notif->read_at) ? 'bg-green-50/40 dark:bg-green-900/10' : 'bg-white dark:bg-zinc-900 opacity-70' }}">

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @php $type = $data['type'] ?? 'general'; @endphp
                    @if($type === 'task_assigned')
                        <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                    @elseif($type === 'task_completed')
                        <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    @elseif($type === 'task_verified')
                        <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline gap-2 flex-wrap">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $data['title'] ?? 'Notification' }}</p>
                        @php
                            $typeLabels = [
                                'task_assigned' => ['label' => 'Assigned', 'classes' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                'task_completed' => ['label' => 'Completed', 'classes' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'task_verified' => ['label' => 'Verified', 'classes' => 'bg-green-50 text-green-700 border-green-200'],
                                'general' => ['label' => 'General', 'classes' => 'bg-zinc-50 text-zinc-600 border-zinc-200'],
                            ];
                            $tl = $typeLabels[$type] ?? $typeLabels['general'];
                        @endphp
                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded border {{ $tl['classes'] }}">{{ $tl['label'] }}</span>
                        @if(is_null($notif->read_at))
                            <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $data['body'] ?? '' }}</p>
                    <p class="text-[10px] text-zinc-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0 flex items-center gap-2">
                    @if(is_null($notif->read_at))
                        <button wire:click="markAsRead('{{ $notif->id }}')" title="Mark as read"
                            class="p-1 rounded text-zinc-400 hover:text-green-600 hover:bg-green-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </button>
                    @endif
                    <button wire:click="delete('{{ $notif->id }}')" title="Delete"
                        class="p-1 rounded text-zinc-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-zinc-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                <p class="text-sm font-medium text-zinc-500">No notifications found.</p>
                @if($typeFilter)
                    <button wire:click="$set('typeFilter', '')" class="mt-2 text-xs text-green-600 hover:underline">Clear filter</button>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>{{ $notifications->links() }}</div>
</div>
