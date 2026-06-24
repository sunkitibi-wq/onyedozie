<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Dashboard Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <span class="block text-xs font-semibold text-zinc-550 dark:text-zinc-400 uppercase">Total Incidents</span>
            <span class="block text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $totalIncidents }}</span>
        </div>
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <span class="block text-xs font-semibold text-zinc-550 dark:text-zinc-400 uppercase">Pending Incident Reports</span>
            <span class="block text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $pendingIncidents }}</span>
        </div>
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <span class="block text-xs font-semibold text-zinc-550 dark:text-zinc-400 uppercase">Total Voice Reports</span>
            <span class="block text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $totalVoiceReports }}</span>
        </div>
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <span class="block text-xs font-semibold text-zinc-550 dark:text-zinc-400 uppercase">Quick Actions Section</span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-full mt-2 {{ $quickActionsEnabled ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $quickActionsEnabled ? 'bg-green-600' : 'bg-zinc-550' }}"></span>
                {{ $quickActionsEnabled ? 'Active' : 'Deactivated' }}
            </span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button wire:click="selectTab('incidents')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'incidents' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Incident Reports
        </button>
        <button wire:click="selectTab('voice_reports')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'voice_reports' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Voice Reports
        </button>
        <button wire:click="selectTab('settings')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'settings' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Mobile App Settings
        </button>
    </div>

    <!-- INCIDENTS TAB -->
    @if($activeTab === 'incidents')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Mobile Incident Reports</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                <th class="py-2">Type</th>
                                <th class="py-2">Reported By</th>
                                <th class="py-2">Urgency</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Date</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $incident)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white capitalize">{{ $incident->type }}</td>
                                    <td class="py-3 text-zinc-650 dark:text-zinc-350">
                                        <div class="text-sm font-medium">{{ $incident->user?->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $incident->user?->lga?->name }} / {{ $incident->user?->ward?->name }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold capitalize {{ $incident->urgency === 'critical' || $incident->urgency === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900/35 dark:text-red-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/35 dark:text-amber-400' }}">
                                            {{ $incident->urgency }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize {{ $incident->status === 'resolved' ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : ($incident->status === 'escalated' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/35 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/35 dark:text-red-400') }}">
                                            {{ $incident->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-xs text-zinc-500">{{ $incident->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-3 text-right">
                                        <button wire:click="viewIncident({{ $incident->id }})" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-500 text-sm font-semibold">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-zinc-550 dark:text-zinc-400">No incident reports uploaded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $incidents->links() }}</div>
                </div>
            </div>

            <!-- Incident Detail View Panel -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                @if($selectedIncident)
                    <div class="flex justify-between items-center mb-4 border-b border-zinc-200 dark:border-zinc-800 pb-3">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white">Incident Detail</h3>
                        <button wire:click="closeIncidentModal" class="text-zinc-450 hover:text-zinc-650">&times; Close</button>
                    </div>
                    <div class="space-y-4 text-sm text-zinc-700 dark:text-zinc-300">
                        <div>
                            <span class="block text-xs font-semibold text-zinc-500 uppercase">Reporter</span>
                            <span class="block font-medium text-zinc-900 dark:text-white">{{ $selectedIncident->user?->name }} ({{ $selectedIncident->user?->phone }})</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-zinc-500 uppercase">Location Hierarchy</span>
                            <span class="block text-zinc-600 dark:text-zinc-400">{{ $selectedIncident->user?->lga?->name }} / {{ $selectedIncident->user?->ward?->name }}</span>
                            @if($selectedIncident->lat && $selectedIncident->lng)
                                <span class="block text-xs text-zinc-500 font-mono mt-0.5">GPS: {{ $selectedIncident->lat }}, {{ $selectedIncident->lng }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-zinc-500 uppercase">Type / Urgency</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-xs font-semibold capitalize bg-zinc-100 dark:bg-zinc-800">{{ $selectedIncident->type }}</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-xs font-semibold capitalize bg-red-100 text-red-800 dark:bg-red-900/35 dark:text-red-400 ml-1">{{ $selectedIncident->urgency }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-zinc-500 uppercase">Report Description</span>
                            <p class="mt-1 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg whitespace-pre-line border border-zinc-200 dark:border-zinc-750">{{ $selectedIncident->description }}</p>
                        </div>
                        
                        <!-- Media attachments if any -->
                        @if($selectedIncident->media->isNotEmpty())
                            <div>
                                <span class="block text-xs font-semibold text-zinc-500 uppercase mb-2">Attachments</span>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($selectedIncident->media as $m)
                                        @if($m->type === 'photo')
                                            <a href="{{ asset($m->path) }}" target="_blank" class="block border border-zinc-200 dark:border-zinc-850 rounded-lg overflow-hidden">
                                                <img src="{{ asset($m->path) }}" class="object-cover h-24 w-full" />
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <span class="block text-xs font-semibold text-zinc-500 uppercase">Update Status</span>
                            <div class="mt-2 flex gap-1">
                                <button wire:click="updateIncidentStatus({{ $selectedIncident->id }}, 'reported')" class="px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $incidentStatusUpdate === 'reported' ? 'bg-red-600 text-white border-transparent' : 'bg-white text-zinc-700 dark:bg-zinc-800 dark:text-white border-zinc-200 dark:border-zinc-700' }}">
                                    Reported
                                </button>
                                <button wire:click="updateIncidentStatus({{ $selectedIncident->id }}, 'escalated')" class="px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $incidentStatusUpdate === 'escalated' ? 'bg-amber-500 text-white border-transparent' : 'bg-white text-zinc-700 dark:bg-zinc-800 dark:text-white border-zinc-200 dark:border-zinc-700' }}">
                                    Escalated
                                </button>
                                <button wire:click="updateIncidentStatus({{ $selectedIncident->id }}, 'resolved')" class="px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $incidentStatusUpdate === 'resolved' ? 'bg-green-600 text-white border-transparent' : 'bg-white text-zinc-700 dark:bg-zinc-800 dark:text-white border-zinc-200 dark:border-zinc-700' }}">
                                    Resolved
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12 text-zinc-450 dark:text-zinc-500">
                        <span class="block mb-2">💡 Info Panel</span>
                        Select an incident report from the list to view its complete details and update its status.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- VOICE REPORTS TAB -->
    @if($activeTab === 'voice_reports')
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Voice Audio Reports</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                            <th class="py-2">Reported By</th>
                            <th class="py-2">Audio Update</th>
                            <th class="py-2">Transcript</th>
                            <th class="py-2">Duration</th>
                            <th class="py-2 text-right">Uploaded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voiceReports as $voice)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                <td class="py-3 text-zinc-900 dark:text-white font-medium">
                                    {{ $voice->user?->name }}
                                    <span class="block text-xs text-zinc-500">{{ $voice->user?->lga?->name }} / {{ $voice->user?->ward?->name }}</span>
                                </td>
                                <td class="py-3">
                                    <audio controls class="h-8 max-w-xs focus:outline-none">
                                        <source src="{{ asset($voice->audio_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </td>
                                <td class="py-3 text-zinc-700 dark:text-zinc-350 italic max-w-md truncate">
                                    "{{ $voice->transcript ?: 'No transcript available' }}"
                                </td>
                                <td class="py-3 font-mono text-xs text-zinc-650">
                                    {{ $voice->duration ? gmdate('i:s', $voice->duration) : 'N/A' }}
                                </td>
                                <td class="py-3 text-right text-xs text-zinc-500">
                                    {{ $voice->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-zinc-550 dark:text-zinc-400">No voice reports uploaded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $voiceReports->links() }}</div>
            </div>
        </div>
    @endif

    <!-- MOBILE APP SETTINGS TAB -->
    @if($activeTab === 'settings')
        <div class="max-w-2xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">Campaign Mobile App Controls</h3>
            <p class="text-xs text-zinc-500 mb-6">Manage global campaign configurations and toggle active modules inside the mobile application.</p>

            <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-750">
                <div class="space-y-0.5">
                    <span class="block text-sm font-bold text-zinc-900 dark:text-white">Quick Actions Dashboard Grid</span>
                    <span class="block text-xs text-zinc-550 dark:text-zinc-400">Toggle this switch to show or hide the election day quick actions buttons grid on the mobile homepage.</span>
                </div>
                
                <!-- Toggle Button -->
                <button wire:click="toggleQuickActions" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionsEnabled ? 'bg-green-600' : 'bg-zinc-350 dark:bg-zinc-700' }}">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionsEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </div>
        </div>
    @endif
</div>
