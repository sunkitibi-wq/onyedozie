<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-zinc-900 dark:text-red-400 border border-red-200 dark:border-red-800 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Member List -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Campaign Members Directory</h3>
                    <p class="text-xs text-zinc-500">Manage campaign administrators, coordinators, and field agents.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Search -->
                    <input type="text" wire:model.live="search" class="px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Search by name or phone...">

                    <!-- Create Button -->
                    <button wire:click="startCreate" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold whitespace-nowrap flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New User
                    </button>
                </div>
            </div>

            <!-- Bulk Upload Section -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 mb-6" x-data="{ showUpload: false }">
                <div class="flex-1 w-full md:w-auto">
                    <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Bulk Supporter Upload</h4>
                    <p class="text-xs text-zinc-500">Import campaign members from a CSV template.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button wire:click="downloadCsvTemplate" class="px-3 py-1.5 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-zinc-800 dark:text-zinc-200 rounded-lg text-xs font-semibold whitespace-nowrap">
                        Download Template
                    </button>
                    
                    <button @click="showUpload = !showUpload" class="px-3 py-1.5 bg-zinc-800 hover:bg-zinc-900 dark:bg-zinc-100 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 rounded-lg text-xs font-semibold whitespace-nowrap">
                        Import CSV
                    </button>
                </div>

                <div x-show="showUpload" style="display: none;" class="w-full basis-full mt-2 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <form wire:submit.prevent="processBulkUpload" class="flex flex-col sm:flex-row items-start sm:items-end gap-3">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">Select CSV File</label>
                            <input type="file" wire:model="bulkUploadFile" accept=".csv" class="w-full px-3 py-1.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                            @error('bulkUploadFile') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold whitespace-nowrap" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="processBulkUpload">Upload & Process</span>
                            <span wire:loading wire:target="processBulkUpload">Processing...</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 mb-4">
                <select wire:model.live="roleFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="lgaFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All LGAs</option>
                    @foreach($lgas as $lga)
                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="occupationFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All Occupations</option>
                    @foreach($occupations as $occ)
                        <option value="{{ $occ }}">{{ $occ }}</option>
                    @endforeach
                </select>

                <select wire:model.live="statusFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending_approval">Pending Approval</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium text-xs uppercase tracking-wider">
                            <th class="py-2.5 pr-4">Name</th>
                            <th class="py-2.5 pr-4">Role</th>
                            <th class="py-2.5 pr-4">Occupation</th>
                            <th class="py-2.5 pr-4">Jurisdiction</th>
                            <th class="py-2.5 pr-4">Status</th>
                            <th class="py-2.5 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                <td class="py-3 pr-4 font-semibold text-zinc-900 dark:text-white flex items-center gap-3">
                                    @if($user->passport_path)
                                        <img src="{{ asset('storage/' . $user->passport_path) }}" class="w-8 h-8 rounded-full object-cover border border-zinc-200 dark:border-zinc-700" alt="Passport">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-zinc-150 dark:bg-zinc-850 flex items-center justify-center text-xs font-bold text-zinc-500">
                                            {{ $user->initials() }}
                                        </div>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-600 dark:text-zinc-400">
                                    <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-zinc-800 dark:text-zinc-300">
                                        {{ $user->roles->first()?->name ?? 'No Role' }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-600 dark:text-zinc-400">
                                    {{ $user->occupation ?? 'Not Specified' }}
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-500">
                                    {{ $user->lga?->name ?? 'None' }}
                                    @if($user->ward) / {{ $user->ward->name }} @endif
                                    @if($user->pollingUnit) / PU ({{ $user->pollingUnit->code }}) @endif
                                </td>
                                <td class="py-3 pr-4 text-xs">
                                    @if($user->status === 'active')
                                        <span class="text-green-600 bg-green-50 dark:bg-green-950/20 px-2 py-0.5 rounded font-medium border border-green-200 dark:border-green-800">Active</span>
                                    @elseif($user->status === 'pending_approval')
                                        <span class="text-amber-600 bg-amber-50 dark:bg-amber-950/20 px-2 py-0.5 rounded font-medium border border-amber-200 dark:border-amber-800">Pending</span>
                                    @else
                                        <span class="text-red-600 bg-red-50 dark:bg-red-950/20 px-2 py-0.5 rounded font-medium border border-red-200 dark:border-red-800">Suspended</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 flex gap-2">
                                    <button wire:click="selectUser({{ $user->id }})" class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 text-zinc-750 dark:text-zinc-200 rounded text-xs font-semibold">
                                        Manage
                                    </button>
                                    <button wire:click="toggleUserStatus({{ $user->id }})" class="px-2 py-1 {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200' : 'bg-green-50 text-green-600 hover:bg-green-100 border border-green-200' }} rounded text-xs font-semibold">
                                        {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>

        <!-- Detail/Create Sidebar Panel -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
            @if($isCreating)
                <!-- Create Form -->
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Create New Supporter/User</h3>
                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Full Name</label>
                        <input type="text" wire:model="name" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Passport Photograph</label>
                        <input type="file" wire:model="passport" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('passport') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Phone Number</label>
                        <input type="text" wire:model="phone" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('phone') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Email (Optional)</label>
                        <input type="email" wire:model="email" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Occupation</label>
                        <input type="text" wire:model="occupation" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g. Trader, Student, Engineer">
                        @error('occupation') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Password</label>
                        <input type="password" wire:model="password" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('password') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">System Role</label>
                        <select wire:model="selectedRole" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA Jurisdiction</label>
                        <select wire:model.live="lgaId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select LGA</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                            @endforeach
                        </select>
                        @error('lgaId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward Jurisdiction</label>
                        <select wire:model.live="wardId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                            @endforeach
                        </select>
                        @error('wardId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Polling Unit Assignment</label>
                        <select wire:model="pollingUnitId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Polling Unit</option>
                            @foreach($pollingUnits as $pu)
                                <option value="{{ $pu->id }}">{{ $pu->name }} ({{ $pu->code }})</option>
                            @endforeach
                        </select>
                        @error('pollingUnitId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Status</label>
                        <select wire:model="status" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="active">Active</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        @error('status') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            Create User
                        </button>
                        <button type="button" wire:click="closeDetails" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded-lg text-sm font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            @elseif($selectedUserId)
                <!-- Edit Form -->
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Edit User Profile</h3>
                <form wire:submit.prevent="saveUser" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Full Name</label>
                        <input type="text" wire:model="name" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Passport Photograph</label>
                        @if($passportPath)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $passportPath) }}" class="w-16 h-16 rounded-full object-cover border border-zinc-200 dark:border-zinc-700" alt="Current Passport">
                            </div>
                        @endif
                        <input type="file" wire:model="passport" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('passport') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Phone Number</label>
                        <input type="text" wire:model="phone" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('phone') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Email (Optional)</label>
                        <input type="email" wire:model="email" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Occupation</label>
                        <input type="text" wire:model="occupation" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g. Trader, Student, Engineer">
                        @error('occupation') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">System Role</label>
                        <select wire:model="selectedRole" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA Jurisdiction</label>
                        <select wire:model.live="lgaId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select LGA</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                            @endforeach
                        </select>
                        @error('lgaId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward Jurisdiction</label>
                        <select wire:model.live="wardId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                            @endforeach
                        </select>
                        @error('wardId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Polling Unit Assignment</label>
                        <select wire:model="pollingUnitId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="">Select Polling Unit</option>
                            @foreach($pollingUnits as $pu)
                                <option value="{{ $pu->id }}">{{ $pu->name }} ({{ $pu->code }})</option>
                            @endforeach
                        </select>
                        @error('pollingUnitId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Status</label>
                        <select wire:model="status" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                            <option value="active">Active</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        @error('status') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            Save Changes
                        </button>
                        <button type="button" wire:click="closeDetails" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded-lg text-sm font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-12 text-zinc-500 text-sm">
                    Select a member from the directory list or click <strong>New User</strong> to configure campaign accounts.
                </div>
            @endif
        </div>
    </div>
</div>
