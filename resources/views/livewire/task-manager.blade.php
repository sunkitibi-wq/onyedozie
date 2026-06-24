<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tasks Main Directory -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Campaign Tasks Directory</h3>
                    <p class="text-xs text-zinc-500">Create, assign, and verify tasks for volunteers and coordinators.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Search -->
                    <input type="text" wire:model.live="search" class="px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white w-full md:w-48 focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Search tasks...">

                    <!-- Create Button -->
                    <button wire:click="startCreate" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold whitespace-nowrap flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Task
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex gap-2">
                <select wire:model.live="statusFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none w-36">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed (Pending Verification)</option>
                    <option value="verified">Verified (Closed)</option>
                </select>
            </div>

            <!-- Tasks Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium text-xs uppercase tracking-wider">
                            <th class="py-2.5 pr-4">Task Info</th>
                            <th class="py-2.5 pr-4">Assignment Scope</th>
                            <th class="py-2.5 pr-4">Deadline</th>
                            <th class="py-2.5 pr-4">Status</th>
                            <th class="py-2.5 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                <td class="py-3 pr-4 space-y-1">
                                    <span class="font-semibold text-zinc-900 dark:text-white block">{{ $task->title }}</span>
                                    <span class="text-xs text-zinc-500 block line-clamp-2 max-w-sm">{{ $task->description }}</span>
                                </td>
                                <td class="py-3 pr-4 text-xs space-y-1">
                                    @if($task->assignedUserId)
                                        <span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded font-medium block w-fit">
                                            User: {{ $task->assignee->name }}
                                        </span>
                                    @endif
                                    @if($task->assigned_to_role)
                                        <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 rounded font-medium block w-fit">
                                            Role: {{ $task->assigned_to_role }}
                                        </span>
                                    @endif
                                    @if($task->lga)
                                        <span class="text-[10px] text-zinc-400 block">
                                            LGA: {{ $task->lga->name }} @if($task->ward) / Ward: {{ $task->ward->name }} @endif
                                        </span>
                                    @endif
                                    @if(!$task->assignedUserId && !$task->assigned_to_role)
                                        <span class="text-zinc-400">General broadcast</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-550">
                                    {{ $task->deadline->format('M d, Y') }}
                                    @if($task->deadline->isPast() && $task->status !== 'verified')
                                        <span class="text-red-500 font-bold block text-[10px]">Overdue</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-xs">
                                    @if($task->status === 'pending')
                                        <span class="text-amber-600 bg-amber-50 dark:bg-amber-950/20 px-2 py-0.5 rounded font-medium border border-amber-200 dark:border-amber-800">Pending</span>
                                    @elseif($task->status === 'completed')
                                        <span class="text-blue-600 bg-blue-50 dark:bg-blue-950/20 px-2 py-0.5 rounded font-medium border border-blue-200 dark:border-blue-800">Completed</span>
                                    @else
                                        <span class="text-green-600 bg-green-50 dark:bg-green-950/20 px-2 py-0.5 rounded font-medium border border-green-200 dark:border-green-800">Verified</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 flex gap-2">
                                    @if($task->status === 'completed')
                                        <button wire:click="viewCompletions({{ $task->id }})" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                                            Verify Work
                                        </button>
                                    @else
                                        <button wire:click="viewCompletions({{ $task->id }})" class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 text-zinc-750 dark:text-zinc-200 rounded text-xs font-semibold">
                                            Details
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-zinc-450 dark:text-zinc-500">
                                    No tasks registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $tasks->links() }}</div>
            </div>
        </div>

        <!-- Task Create/Details Panel -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
            @if($isCreating)
                <!-- Create Form -->
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Create Campaign Task</h3>
                <form wire:submit.prevent="createTask" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Task Title</label>
                        <input type="text" wire:model="title" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g. Recruit 5 members in Ward 2">
                        @error('title') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Description</label>
                        <textarea wire:model="description" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" rows="3" placeholder="Provide step-by-step instructions..."></textarea>
                        @error('description') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Deadline Date</label>
                        <input type="date" wire:model="deadline" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('deadline') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Assign to Role (Optional)</label>
                        <select wire:model="assignedToRole" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Assign to Specific User (Optional)</label>
                        <select wire:model="assignedUserId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">None (Broadcast)</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA Target Scope</label>
                        <select wire:model.live="lgaId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">All LGAs</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward Target Scope</label>
                        <select wire:model="wardId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">All Wards</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            Create Task
                        </button>
                        <button type="button" wire:click="closeDetails" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded-lg text-sm font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            @elseif($selectedTaskId)
                <!-- Completion Details -->
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Task Activity</h3>
                        <button wire:click="closeDetails" class="text-zinc-400 hover:text-zinc-650">✕</button>
                    </div>
                    
                    @php
                        $t = \App\Models\Task::find($selectedTaskId);
                    @endphp

                    <div class="p-3.5 bg-zinc-50 dark:bg-zinc-800/40 rounded-lg border border-zinc-200 dark:border-zinc-800 space-y-2">
                        <h4 class="font-bold text-zinc-900 dark:text-white text-sm">{{ $t->title }}</h4>
                        <p class="text-xs text-zinc-500">{{ $t->description }}</p>
                        <div class="text-[10px] text-zinc-400">Created by: {{ $t->creator->name }}</div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Completion Reports</h4>
                        @forelse($selectedTaskCompletions as $comp)
                            <div class="p-3 bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-1 text-xs">
                                <div class="flex justify-between text-[10px] text-zinc-400">
                                    <strong>{{ $comp->user->name }}</strong>
                                    <span>{{ $comp->completed_at->format('M d, H:i') }}</span>
                                </div>
                                <p class="text-zinc-650 dark:text-zinc-350 italic">"{{ $comp->notes ?: 'No implementation notes provided' }}"</p>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-450 text-center py-6">No completions logged for this task yet.</p>
                        @endforelse
                    </div>

                    @if($t->status === 'completed')
                        <button wire:click="verifyTask({{ $t->id }})" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            Approve & Close Task
                        </button>
                    @elseif($t->status === 'verified')
                        <div class="p-3 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-800 text-xs rounded text-center">
                            Verified and Closed by <strong>{{ $t->verifier->name }}</strong>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 text-zinc-500 text-sm">
                    Select a task from the list to view completions or click <strong>New Task</strong> to assign operations.
                </div>
            @endif
        </div>
    </div>
</div>
