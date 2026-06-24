<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Roles List -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Roles & Assigned Permissions</h3>
            
            <div class="space-y-4">
                @foreach($roles as $role)
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-150 dark:border-zinc-800/50 hover:border-zinc-250 transition-colors">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $role->name }}</h4>
                            <button wire:click="selectRole({{ $role->id }})" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                                Edit Permissions
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @if($role->permissions->isEmpty())
                                <span class="text-xs text-zinc-500 italic">No permissions assigned.</span>
                            @else
                                @foreach($role->permissions as $perm)
                                    <span class="px-2 py-0.5 bg-white dark:bg-zinc-900 border border-zinc-250 dark:border-zinc-850 rounded text-[10px] text-zinc-600 dark:text-zinc-400">
                                        {{ $perm->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Side: Edit / Add Role -->
        <div class="space-y-6">
            <!-- Edit Role Permissions -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                @if($selectedRoleId)
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Manage Permissions: {{ $roleName }}</h3>
                    <form wire:submit.prevent="saveRolePermissions" class="space-y-4">
                        <div class="max-h-64 overflow-y-auto space-y-2.5 pr-2">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded">
                                    <input type="checkbox" wire:model="permissionIds" value="{{ $perm->id }}" class="rounded text-green-650 border-zinc-300 dark:border-zinc-700 focus:ring-green-500">
                                    <span class="text-xs text-zinc-750 dark:text-zinc-200">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                Sync Permissions
                            </button>
                            <button type="button" wire:click="closeRole" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-750 dark:text-white rounded-lg text-sm font-semibold">
                                Cancel
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Add Role Form -->
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Add Custom Role</h3>
                    <form wire:submit.prevent="createRole" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 uppercase">Role Name</label>
                            <input type="text" wire:model="roleName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Media Coordinator">
                            @error('roleName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            Create Role
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
