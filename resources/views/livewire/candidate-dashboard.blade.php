<div class="space-y-6">
    <!-- Header Title & Share Link -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-zinc-200 dark:border-zinc-800 pb-5">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Hon. Dozie Nwankwo Senatorial Campaign 2027</h2>
            <p class="text-xs text-zinc-500 mt-1">Anambra Central Senatorial District — Campaign Executive Summary Dashboard</p>
        </div>
        @if(!$isPublic)
            <div x-data="{ 
                copied: false,
                copyText(text) {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        });
                    } else {
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        textArea.style.position = 'fixed';
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        try {
                            document.execCommand('copy');
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        } catch (err) {
                            console.error('Fallback copy failed', err);
                        }
                        document.body.removeChild(textArea);
                    }
                }
            }" class="flex items-center gap-2 bg-zinc-50 dark:bg-zinc-900 p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-850">
                <span class="text-xs font-mono text-zinc-550 truncate max-w-xs">{{ $shareableUrl }}</span>
                <button 
                    @click="copyText('{{ $shareableUrl }}')"
                    class="px-3.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5"
                >
                    <span x-text="copied ? 'Copied!' : 'Copy Share Link'"></span>
                </button>
            </div>
        @endif
    </div>

    <!-- Metrics Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Supporters Count Card -->
        <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm space-y-4">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Campaign Supporters Mobilized</span>
                <span class="text-3xl font-extrabold text-green-600 dark:text-green-400">{{ number_format($totalMembers) }}</span>
            </div>
            <div class="space-y-1">
                <div class="flex justify-between text-xs text-zinc-550 dark:text-zinc-400">
                    <span>Target Goal: 10,000 Voters</span>
                    <span>{{ $progressPercentage }}%</span>
                </div>
                <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                    <div class="bg-green-600 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>
        </div>

        <!-- Polling Unit Coverage Card -->
        <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm space-y-4">
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Polling Unit Coverage</span>
                <span class="text-3xl font-extrabold text-zinc-900 dark:text-white">{{ $coveragePercentage }}%</span>
            </div>
            <div class="text-xs text-zinc-550 dark:text-zinc-400 flex items-center gap-1">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                <span>Visually tracked across INEC Anambra Central registers</span>
            </div>
        </div>

        <!-- Executive Dashboard Mode -->
        <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Dashboard View Status</span>
            <div class="flex items-center gap-2 mt-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $isPublic ? 'bg-zinc-100 text-zinc-800 dark:bg-zinc-850 dark:text-zinc-350' : 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' }}">
                    {{ $isPublic ? 'Public Shared Access' : 'Internal Secure access' }}
                </span>
            </div>
            <span class="text-[10px] text-zinc-400 mt-2 block">Data refreshed in real-time</span>
        </div>
    </div>

    <!-- Map & LGA Performance Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Coverage Map -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Active Polling Unit Coverage Map</h3>
            <p class="text-xs text-zinc-500">Geographic markers highlighting polling units with active mobilized supporters.</p>
            
            <!-- Map Container -->
            <div id="coverage-map" class="w-full h-96 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-inner z-0" wire:ignore></div>
        </div>

        <!-- LGA Leaderboard list -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm space-y-4">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">LGA Mobilization Leaderboard</h3>
            <div class="space-y-3 overflow-y-auto max-h-96 pr-2">
                @foreach($lgaData as $lga)
                    <div class="space-y-1">
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-zinc-850 dark:text-zinc-200">{{ $lga->name }}</span>
                            <span class="text-green-600 dark:text-green-400">{{ number_format($lga->users_count) }} Supporters</span>
                        </div>
                        <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                            @php
                                $maxCount = $lgaData->first()->users_count ?: 1;
                                $lgaWidth = ($lga->users_count / $maxCount) * 100;
                            @endphp
                            <div class="bg-green-600 h-full rounded-full" style="width: {{ $lgaWidth }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Events & Task list summary -->
    <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm">
        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Upcoming Campaign Events</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($upcomingEvents as $event)
                <div class="p-4 bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-2">
                    <span class="inline-block px-2.5 py-0.5 bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400 text-[10px] font-bold rounded">
                        {{ $event->date->format('M d, Y') }}
                    </span>
                    <h4 class="font-bold text-zinc-900 dark:text-white text-sm truncate">{{ $event->title }}</h4>
                    <p class="text-xs text-zinc-500 line-clamp-2">{{ $event->description }}</p>
                    <div class="text-[10px] font-semibold text-zinc-650 dark:text-zinc-400 flex items-center gap-1 pt-1 border-t border-zinc-200 dark:border-zinc-850">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <span class="truncate">{{ $event->venue }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-zinc-450 dark:text-zinc-500">
                    No upcoming campaign events scheduled yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Leaflet JS Map Scripting -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Standard location centering: Awka, Anambra State
            const defaultLat = 6.22;
            const defaultLng = 7.07;
            
            const map = L.map('coverage-map').setView([defaultLat, defaultLng], 11);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const activePUs = @json($activePUs);
            
            activePUs.forEach(pu => {
                if (pu.lat && pu.lng) {
                    const marker = L.circleMarker([pu.lat, pu.lng], {
                        radius: Math.min(25, 6 + (pu.count * 1.5)),
                        fillColor: '#006B3F',
                        color: '#9DF5BD',
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.6
                    }).addTo(map);

                    marker.bindPopup(`
                        <div class="font-sans space-y-1">
                            <div class="font-bold text-sm text-zinc-900">${pu.name}</div>
                            <div class="text-xs text-zinc-550 font-mono">Code: ${pu.code}</div>
                            <div class="text-xs font-semibold text-green-700">${pu.count} Supporter(s)</div>
                        </div>
                    `);
                }
            });
        });
    </script>
</div>
