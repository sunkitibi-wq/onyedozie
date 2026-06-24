<div class="space-y-6">
    <!-- Header Title -->
    <div class="border-b border-zinc-200 dark:border-zinc-800 pb-5">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Door-to-Door Canvassing Tracker</h2>
        <p class="text-xs text-zinc-500 mt-1">Real-time stats and mapping of campaign field visits across Anambra Central.</p>
    </div>

    <!-- Stats grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="p-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Total Knocks</span>
            <span class="text-2xl font-extrabold text-zinc-800 dark:text-zinc-150 mt-2">{{ number_format($totalVisits) }}</span>
        </div>
        <div class="p-4 bg-green-50/50 dark:bg-green-950/10 border border-green-200 dark:border-green-900/40 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">Supportive</span>
            <span class="text-2xl font-extrabold text-green-600 dark:text-green-400 mt-2">{{ number_format($supportiveCount) }}</span>
        </div>
        <div class="p-4 bg-amber-50/50 dark:bg-amber-950/10 border border-amber-200 dark:border-amber-900/40 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Neutral</span>
            <span class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-2">{{ number_format($neutralCount) }}</span>
        </div>
        <div class="p-4 bg-red-50/50 dark:bg-red-950/10 border border-red-200 dark:border-red-900/40 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">Opposed</span>
            <span class="text-2xl font-extrabold text-red-600 dark:text-red-400 mt-2">{{ number_format($opposedCount) }}</span>
        </div>
        <div class="p-4 bg-zinc-50 dark:bg-zinc-850/50 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm flex flex-col justify-between col-span-2 md:col-span-1">
            <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Not Home</span>
            <span class="text-2xl font-extrabold text-zinc-600 dark:text-zinc-400 mt-2">{{ number_format($notHomeCount) }}</span>
        </div>
    </div>

    <!-- Map & Table Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Coverage Map -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm space-y-4">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Canvassing Map Coverage</h3>
            <!-- Map Container -->
            <div id="canvass-map" class="w-full h-[450px] rounded-lg border border-zinc-200 dark:border-zinc-800 z-0" wire:ignore></div>
        </div>

        <!-- Listing Table & Filters -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Filter Visits</h3>
                <!-- Search -->
                <input type="text" wire:model.live="search" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Search Agent, Voter or Address...">

                <div class="grid grid-cols-2 gap-2">
                    <select wire:model.live="lgaFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                        <option value="">All LGAs</option>
                        @foreach($lgas as $lga)
                            <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="outcomeFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                        <option value="">All Outcomes</option>
                        <option value="supportive">Supportive</option>
                        <option value="neutral">Neutral</option>
                        <option value="opposed">Opposed</option>
                        <option value="not home">Not Home</option>
                    </select>
                </div>

                <!-- Recent list -->
                <div class="space-y-3 overflow-y-auto max-h-[280px] pr-1">
                    @forelse($visits as $visit)
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200 dark:border-zinc-800 rounded-lg space-y-1 text-xs">
                            <div class="flex justify-between items-center font-bold">
                                <span class="text-zinc-900 dark:text-white">{{ $visit->voter_name ?: 'Unknown Occupant' }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold
                                    @if($visit->outcome === 'supportive') bg-green-100 text-green-800 dark:bg-green-950/20 dark:text-green-400 border border-green-200 dark:border-green-800
                                    @elseif($visit->outcome === 'neutral') bg-amber-100 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-200 dark:border-amber-800
                                    @elseif($visit->outcome === 'opposed') bg-red-100 text-red-800 dark:bg-red-950/20 dark:text-red-400 border border-red-200 dark:border-red-800
                                    @else bg-zinc-100 text-zinc-800 dark:bg-zinc-850 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700
                                    @endif">
                                    {{ $visit->outcome }}
                                </span>
                            </div>
                            <p class="text-zinc-450 truncate">{{ $visit->address_description ?: 'No address specified' }}</p>
                            @if($visit->notes)
                                <p class="text-zinc-500 italic mt-1 font-serif">"{{ $visit->notes }}"</p>
                            @endif
                            <div class="flex justify-between items-center text-[10px] text-zinc-400 pt-1 border-t border-zinc-200 dark:border-zinc-800">
                                <span>Agent: {{ $visit->user->name }}</span>
                                <span>{{ $visit->visited_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-zinc-450 dark:text-zinc-500">
                            No visits logged yet.
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="pt-2 border-t border-zinc-200 dark:border-zinc-800">
                {{ $visits->links() }}
            </div>
        </div>
    </div>

    <!-- Leaflet JS Map Scripting -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            const defaultLat = 6.22;
            const defaultLng = 7.07;
            
            const map = L.map('canvass-map').setView([defaultLat, defaultLng], 11);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let markerLayer = L.layerGroup().addTo(map);

            const outcomesColorMap = {
                'supportive': '#006B3F',
                'neutral': '#795900',
                'opposed': '#DC2626',
                'not home': '#4B5563'
            };

            function updateMarkers(markers) {
                markerLayer.clearLayers();
                if (markers.length === 0) return;

                markers.forEach(m => {
                    const color = outcomesColorMap[m.outcome] || '#006B3F';
                    const circle = L.circleMarker([m.lat, m.lng], {
                        radius: 8,
                        fillColor: color,
                        color: '#FFFFFF',
                        weight: 1.5,
                        opacity: 1,
                        fillOpacity: 0.85
                    }).addTo(markerLayer);

                    circle.bindPopup(`
                        <div class="font-sans space-y-1 text-xs">
                            <div class="font-bold text-sm text-zinc-955">${m.voter_name}</div>
                            <div class="text-zinc-500"><strong>Address:</strong> ${m.address}</div>
                            <div class="text-zinc-500"><strong>Outcome:</strong> <span class="capitalize" style="color:${color};font-weight:bold">${m.outcome}</span></div>
                            <div class="text-zinc-500"><strong>Notes:</strong> ${m.notes}</div>
                            <div class="text-[10px] text-zinc-400 border-t border-zinc-200 pt-1 mt-1">Logged by ${m.agent_name}</div>
                        </div>
                    `);
                });

                // Auto-center map on first marker
                if (markers.length > 0) {
                    map.panTo([markers[0].lat, markers[0].lng]);
                }
            }

            // Initial load
            updateMarkers(@json($markers));

            // Listen for Livewire updates
            @this.on('visitsUpdated', (event) => {
                updateMarkers(event.markers);
            });
        });
    </script>
</div>
