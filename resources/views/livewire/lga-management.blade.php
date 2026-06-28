<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button wire:click="selectTab('lgas')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'lgas' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            LGAs
        </button>
        <button wire:click="selectTab('wards')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'wards' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Wards
        </button>
        <button wire:click="selectTab('pus')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'pus' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Polling Units (PUs)
        </button>
    </div>

    <!-- LGA TAB -->
    @if($activeTab === 'lgas')
        <div class="grid grid-cols-1 {{ auth()->user()->hasRole('Super Admin') ? 'lg:grid-cols-3' : '' }} gap-6">
            <div class="{{ auth()->user()->hasRole('Super Admin') ? 'lg:col-span-2' : '' }} p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">LGAs in Anambra Central</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                <th class="py-2">LGA Name</th>
                                <th class="py-2">State</th>
                                <th class="py-2">Wards Count</th>
                                <th class="py-2">Volunteers Count</th>
                                @if(auth()->user()->hasRole('Super Admin'))
                                    <th class="py-2 text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lgas as $lga)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white">{{ $lga->name }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $lga->state }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $lga->wards_count }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $lga->users_count }}</td>
                                    @if(auth()->user()->hasRole('Super Admin'))
                                        <td class="py-3 text-right">
                                            <button 
                                                wire:click="editLga({{ $lga->id }})" 
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-semibold text-xs border border-green-200 dark:border-green-800/60 bg-green-50/50 dark:bg-green-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm mr-1"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                wire:click="deleteLga({{ $lga->id }})" 
                                                wire:confirm="Are you sure you want to delete the LGA '{{ $lga->name }}'? This will permanently delete all of its associated Wards and Polling Units."
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold text-xs border border-red-200 dark:border-red-800/60 bg-red-50/50 dark:bg-red-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $lgas->links() }}</div>
                </div>
            </div>

            @if(auth()->user()->hasRole('Super Admin'))
                <!-- LGA Form (Create or Edit) -->
                <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                    @if($editingLgaId)
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Edit LGA</h3>
                        <form wire:submit.prevent="updateLga" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA Name</label>
                                <input type="text" wire:model="editingLgaName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Anaocha">
                                @error('editingLgaName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                    Update LGA
                                </button>
                                <button type="button" wire:click="cancelEditLga" class="px-4 py-2 bg-zinc-150 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-800 dark:text-white rounded-lg text-sm font-semibold border border-zinc-200 dark:border-zinc-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @else
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Add New LGA</h3>
                        <form wire:submit.prevent="createLga" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA Name</label>
                                <input type="text" wire:model="lgaName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Anaocha">
                                @error('lgaName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                Add LGA
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- WARD TAB -->
    @if($activeTab === 'wards')
        <div class="grid grid-cols-1 {{ auth()->user()->hasRole('Super Admin') ? 'lg:grid-cols-3' : '' }} gap-6">
            <div class="{{ auth()->user()->hasRole('Super Admin') ? 'lg:col-span-2' : '' }} p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Wards Hierarchy</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                <th class="py-2">Ward Name</th>
                                <th class="py-2">LGA</th>
                                <th class="py-2">Polling Units</th>
                                @if(auth()->user()->hasRole('Super Admin'))
                                    <th class="py-2 text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wards as $ward)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white">{{ $ward->name }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $ward->lga?->name }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $ward->polling_units_count }} PUs</td>
                                    @if(auth()->user()->hasRole('Super Admin'))
                                        <td class="py-3 text-right">
                                            <button 
                                                wire:click="editWard({{ $ward->id }})" 
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-semibold text-xs border border-green-200 dark:border-green-800/60 bg-green-50/50 dark:bg-green-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm mr-1"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                wire:click="deleteWard({{ $ward->id }})" 
                                                wire:confirm="Are you sure you want to delete the Ward '{{ $ward->name }}'? This will permanently delete all of its associated Polling Units."
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold text-xs border border-red-200 dark:border-red-800/60 bg-red-50/50 dark:bg-red-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $wards->links() }}</div>
                </div>
            </div>

            @if(auth()->user()->hasRole('Super Admin'))
                <!-- Ward Form (Create or Edit) -->
                <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                    @if($editingWardId)
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Edit Ward</h3>
                        <form wire:submit.prevent="updateWard" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA</label>
                                <select wire:model="editingWardLgaId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select LGA</option>
                                    @foreach($allLgas as $lga)
                                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                                @error('editingWardLgaId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward Name</label>
                                <input type="text" wire:model="editingWardName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Agulu I">
                                @error('editingWardName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                    Update Ward
                                </button>
                                <button type="button" wire:click="cancelEditWard" class="px-4 py-2 bg-zinc-150 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-800 dark:text-white rounded-lg text-sm font-semibold border border-zinc-200 dark:border-zinc-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @else
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Add New Ward</h3>
                        <form wire:submit.prevent="createWard" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">LGA</label>
                                <select wire:model="wardLgaId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select LGA</option>
                                    @foreach($allLgas as $lga)
                                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                                @error('wardLgaId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward Name</label>
                                <input type="text" wire:model="wardName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Agulu I">
                                @error('wardName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                Add Ward
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- PU TAB -->
    @if($activeTab === 'pus')
        <div class="grid grid-cols-1 {{ auth()->user()->hasRole('Super Admin') ? 'lg:grid-cols-3' : '' }} gap-6">
            <div class="{{ auth()->user()->hasRole('Super Admin') ? 'lg:col-span-2' : '' }} p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Polling Units</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                <th class="py-2">PU Name</th>
                                <th class="py-2">Code</th>
                                <th class="py-2">Ward & LGA</th>
                                <th class="py-2">Registered Voters</th>
                                @if(auth()->user()->hasRole('Super Admin'))
                                    <th class="py-2 text-right">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pus as $pu)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white">{{ $pu->name }}</td>
                                    <td class="py-3 font-mono text-zinc-700 dark:text-zinc-300">{{ $pu->code }}</td>
                                    <td class="py-3 text-xs text-zinc-500">
                                        {{ $pu->ward?->name }} ({{ $pu->ward?->lga?->name }})
                                    </td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ number_format($pu->registered_voters) }}</td>
                                    @if(auth()->user()->hasRole('Super Admin'))
                                        <td class="py-3 text-right">
                                            <button 
                                                wire:click="editPu({{ $pu->id }})" 
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-semibold text-xs border border-green-200 dark:border-green-800/60 bg-green-50/50 dark:bg-green-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm mr-1"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                wire:click="deletePu({{ $pu->id }})" 
                                                wire:confirm="Are you sure you want to delete the Polling Unit '{{ $pu->name }}'?"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold text-xs border border-red-200 dark:border-red-800/60 bg-red-50/50 dark:bg-red-950/20 px-2.5 py-1 rounded-md transition-colors shadow-sm"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pus->links() }}</div>
                </div>
            </div>

            @if(auth()->user()->hasRole('Super Admin'))
                <!-- Polling Unit Form (Create or Edit) -->
                <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                    @if($editingPuId)
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Edit Polling Unit</h3>
                        <form wire:submit.prevent="updatePu" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward</label>
                                <select wire:model="editingPuWardId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Ward</option>
                                    @foreach($allWards as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->lga?->name }})</option>
                                    @endforeach
                                </select>
                                @error('editingPuWardId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">PU Name</label>
                                <input type="text" wire:model="editingPuName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Customary Court Square">
                                @error('editingPuName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">PU Code</label>
                                <input type="text" wire:model="editingPuCode" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 font-mono" placeholder="e.g. 04-01-01-001">
                                @error('editingPuCode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-500 uppercase">Lat</label>
                                    <input type="text" wire:model="editingPuLat" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="6.2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-500 uppercase">Lng</label>
                                    <input type="text" wire:model="editingPuLng" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="7.0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Registered Voters</label>
                                <input type="number" wire:model="editingPuVoters" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="500">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                    Update Polling Unit
                                </button>
                                <button type="button" wire:click="cancelEditPu" class="px-4 py-2 bg-zinc-150 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-800 dark:text-white rounded-lg text-sm font-semibold border border-zinc-200 dark:border-zinc-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @else
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Add Polling Unit</h3>
                        <form wire:submit.prevent="createPu" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Ward</label>
                                <select wire:model="puWardId" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Ward</option>
                                    @foreach($allWards as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->lga?->name }})</option>
                                    @endforeach
                                </select>
                                @error('puWardId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">PU Name</label>
                                <input type="text" wire:model="puName" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g. Customary Court Square">
                                @error('puName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">PU Code</label>
                                <input type="text" wire:model="puCode" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 font-mono" placeholder="e.g. 04-01-01-001">
                                @error('puCode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-500 uppercase">Lat</label>
                                    <input type="text" wire:model="puLat" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="6.2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-500 uppercase">Lng</label>
                                    <input type="text" wire:model="puLng" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="7.0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 uppercase">Registered Voters</label>
                                <input type="number" wire:model="puVoters" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="500">
                            </div>
                            <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                Add Polling Unit
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
