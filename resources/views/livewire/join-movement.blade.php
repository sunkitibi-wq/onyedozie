<div>
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-primary/10 border border-primary/30 text-primary rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined mt-0.5">check_circle</span>
            <p class="font-body-md">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <!-- Name -->
        <div class="space-y-2">
            <label class="text-label-md text-on-surface font-bold" for="name">Full Name</label>
            <input wire:model.blur="name" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="name" placeholder="John Doe" type="text" required/>
            @error('name') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Phone & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="phone">Phone Number</label>
                <input wire:model.blur="phone" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="phone" placeholder="+234 80 0000 0000" type="tel" required/>
                @error('phone') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="email">Email Address</label>
                <input wire:model.blur="email" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="email" placeholder="john@example.com" type="email"/>
                @error('email') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Password & Selected Role -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="password">Password</label>
                <input wire:model.blur="password" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="password" placeholder="Min. 8 characters" type="password" required/>
                @error('password') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="selectedRole">Campaign Role</label>
                <select wire:model.live="selectedRole" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="selectedRole" required>
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('selectedRole') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- LGA / Ward / Polling Unit Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- LGA select -->
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="lgaId">LGA</label>
                <select wire:model.live="lgaId" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="lgaId">
                    <option value="">Select LGA</option>
                    @foreach ($lgas as $lga)
                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                    @endforeach
                </select>
                @error('lgaId') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Ward select -->
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="wardId">Ward</label>
                <select wire:model.live="wardId" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="wardId" {{ empty($wards) ? 'disabled' : '' }}>
                    <option value="">Select Ward</option>
                    @foreach ($wards as $ward)
                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                    @endforeach
                </select>
                @error('wardId') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Polling Unit select -->
            <div class="space-y-2">
                <label class="text-label-md text-on-surface font-bold" for="pollingUnitId">Polling Unit</label>
                <select wire:model.live="pollingUnitId" class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="pollingUnitId" {{ empty($pollingUnits) ? 'disabled' : '' }}>
                    <option value="">Select Polling Unit</option>
                    @foreach ($pollingUnits as $pu)
                        <option value="{{ $pu->id }}">{{ $pu->name }} ({{ $pu->code }})</option>
                    @endforeach
                </select>
                @error('pollingUnitId') <span class="text-error font-label-sm text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button class="w-full bg-primary text-on-primary py-4 font-headline-md text-headline-md rounded-lg hover:shadow-lg transition-all active:scale-[0.98] cursor-pointer mt-6 font-bold flex items-center justify-center gap-2" type="submit" wire:loading.attr="disabled">
            <span wire:loading class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            Join the Movement
        </button>
        <p class="text-center text-label-sm text-on-surface-variant leading-relaxed">By joining, you agree to receive campaign updates and communications. You can opt-out at any time.</p>
    </form>
</div>
