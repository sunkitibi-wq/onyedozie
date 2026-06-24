<div class="space-y-6" x-data="memberMap(@js($members))" x-init="initMap()">
    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar filters & Active list -->
        <div class="lg:col-span-1 flex flex-col gap-4">
            <!-- Filter Panel -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm space-y-4">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Live Member Tracking</h3>
                    <p class="text-xs text-zinc-500">Track volunteers and field team locations in real-time.</p>
                </div>

                <div class="space-y-3">
                    <!-- Search -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">Search Agent</label>
                        <input type="text" wire:model.live="search" class="w-full px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Name or phone...">
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">Filter by Role</label>
                        <select wire:model.live="roleFilter" class="w-full px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- LGA Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">LGA Jurisdiction</label>
                        <select wire:model.live="lgaFilter" class="w-full px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                            <option value="">All LGAs</option>
                            @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">User Status</label>
                        <select wire:model.live="statusFilter" class="w-full px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- List of members on map -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm flex-1 max-h-[500px] overflow-y-auto">
                <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3">Agents on Map ({{ count($members) }})</h4>
                <div class="space-y-2.5">
                    @forelse($members as $m)
                        <div @click="focusMember({{ $m['id'] }})" class="p-3 bg-zinc-50 dark:bg-zinc-850 hover:bg-zinc-100 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800/80 rounded-lg cursor-pointer transition flex items-start gap-3">
                            <div class="relative flex-shrink-0">
                                @if($m['passport'])
                                    <img src="{{ $m['passport'] }}" class="w-9 h-9 rounded-full object-cover border border-zinc-200 dark:border-zinc-700">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300">
                                        {{ $m['initials'] }}
                                    </div>
                                @endif
                                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white dark:ring-zinc-900 {{ $m['is_online'] ? 'bg-green-500' : 'bg-zinc-400' }}"></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-1">
                                    <h5 class="text-xs font-bold text-zinc-900 dark:text-white truncate">{{ $m['name'] }}</h5>
                                    @if($m['battery'] !== null)
                                        <span class="text-[10px] flex items-center gap-0.5 {{ $m['battery'] > 20 ? 'text-zinc-500' : 'text-red-500 font-bold' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-1.242-1.008-2.25-2.25-2.25H5.25A2.25 2.25 0 0 0 3 8.25v7.5A2.25 2.25 0 0 0 5.25 18h13.5A2.25 2.25 0 0 0 21 15.75v-7.5Z" />
                                            </svg>
                                            {{ $m['battery'] }}%
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-zinc-400 truncate">{{ $m['role'] }} • {{ $m['lga'] }}</p>
                                <p class="text-[9px] text-zinc-500 mt-0.5 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-2.5 h-2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ $m['last_ping_human'] }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-zinc-500">
                            No located members match current filters.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Map Container -->
        <div class="lg:col-span-3 p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-[650px] flex flex-col">
            <div id="map" class="w-full h-full rounded-lg z-0" style="min-height: 500px;"></div>
        </div>
    </div>

    <!-- Alpine.js Map Component Handling -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('memberMap', (initialMembers) => ({
                map: null,
                markers: {},
                circles: {},
                members: initialMembers,

                initMap() {
                    this.$watch('members', (newVal) => {
                        this.updateMarkers(newVal);
                    });

                    // Wait a brief moment to ensure container element is rendered
                    setTimeout(() => {
                        this.setupLeaflet();
                    }, 200);
                },

                setupLeaflet() {
                    // Destroy if already exists
                    if (this.map) {
                        this.map.remove();
                    }

                    // Default coordinates (approx center of Anambra State, Nigeria, since Dozie Nwankwo is from Anambra)
                    const defaultLat = 6.2209;
                    const defaultLng = 7.0674;

                    this.map = L.map('map').setView([defaultLat, defaultLng], 10);

                    // Add standard OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.updateMarkers(this.members);
                },

                updateMarkers(memberList) {
                    if (!this.map) return;

                    // Remove old markers and circles
                    Object.values(this.markers).forEach(m => this.map.removeLayer(m));
                    Object.values(this.circles).forEach(c => this.map.removeLayer(c));
                    this.markers = {};
                    this.circles = {};

                    if (memberList.length === 0) return;

                    const bounds = [];

                    memberList.forEach(m => {
                        if (!m.lat || !m.lng) return;

                        const latLng = [m.lat, m.lng];
                        bounds.push(latLng);

                        // Dynamic marker icon color based on online status and role
                        let markerColor = '#94a3b8'; // default offline grey
                        if (m.is_online) {
                            if (m.role.toLowerCase().includes('admin')) markerColor = '#ef4444'; // Red
                            else if (m.role.toLowerCase().includes('coordinator')) markerColor = '#3b82f6'; // Blue
                            else if (m.role.toLowerCase().includes('volunteer')) markerColor = '#10b981'; // Green
                            else markerColor = '#f59e0b'; // Yellow
                        }

                        // Create custom divicon
                        const customIcon = L.divIcon({
                            className: 'custom-leaflet-marker',
                            html: `
                                <div class="relative flex items-center justify-center w-8 h-8 rounded-full shadow-lg border-2 border-white dark:border-zinc-800" style="background-color: ${markerColor};">
                                    <span class="text-[10px] font-bold text-white">${m.initials}</span>
                                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white dark:ring-zinc-900 ${m.is_online ? 'bg-green-500' : 'bg-zinc-400'}"></span>
                                </div>
                            `,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -16]
                        });

                        // Create Marker
                        const marker = L.marker(latLng, { icon: customIcon }).addTo(this.map);
                        
                        // Create Popup
                        const popupContent = `
                            <div class="p-2 space-y-1.5 font-sans min-w-[200px] text-zinc-900 dark:text-zinc-100">
                                <div class="flex items-center gap-2">
                                    <div class="font-bold text-sm text-zinc-800">${m.name}</div>
                                    <span class="px-1.5 py-0.5 text-[9px] rounded font-medium bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                                        ${m.role}
                                    </span>
                                </div>
                                <div class="text-[11px] text-zinc-500">
                                    <strong>Phone:</strong> ${m.phone}<br>
                                    <strong>LGA:</strong> ${m.lga}<br>
                                    <strong>Last Active:</strong> ${m.last_ping_human}
                                </div>
                                <div class="flex items-center justify-between text-[10px] border-t border-zinc-100 dark:border-zinc-800 pt-1.5 mt-1.5">
                                    <span><strong>Battery:</strong> ${m.battery !== null ? m.battery + '%' : 'N/A'}</span>
                                    <span><strong>Accuracy:</strong> ${m.accuracy !== null ? m.accuracy + 'm' : 'N/A'}</span>
                                </div>
                            </div>
                        `;
                        marker.bindPopup(popupContent);

                        this.markers[m.id] = marker;

                        // Add accuracy circle if accuracy is available
                        if (m.accuracy) {
                            const circle = L.circle(latLng, {
                                radius: m.accuracy,
                                color: markerColor,
                                fillColor: markerColor,
                                fillOpacity: 0.1,
                                weight: 1
                            }).addTo(this.map);
                            this.circles[m.id] = circle;
                        }
                    });

                    // Fit map bounds to show all markers
                    if (bounds.length > 0) {
                        this.map.fitBounds(bounds, { padding: [50, 50] });
                    }
                },

                focusMember(id) {
                    const marker = this.markers[id];
                    if (marker) {
                        this.map.setView(marker.getLatLng(), 15, { animate: true });
                        marker.openPopup();
                    }
                }
            }));
        });
    </script>
</div>
