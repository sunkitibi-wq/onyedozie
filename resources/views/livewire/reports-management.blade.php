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

            <!-- Configure Individual Quick Links -->
            <div class="mt-8 pt-6 border-t border-zinc-200 dark:border-zinc-800 {{ !$quickActionsEnabled ? 'opacity-50 pointer-events-none select-none' : '' }} transition-opacity duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white">Individual Quick Link Visibility</h4>
                        <p class="text-xs text-zinc-550 dark:text-zinc-400 mt-0.5">Toggle visibility for specific actions inside the mobile dashboard grid.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Card: Recruit -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Recruit</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionRecruitVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionRecruitVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Canvassers register new campaign volunteers and members.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('recruit')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionRecruitVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionRecruitVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    <!-- Card: Results -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Results</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionResultsVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionResultsVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Tapping this opens the election results upload screen.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('results')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionResultsVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionResultsVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    <!-- Card: Incident -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Incident</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionIncidentVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionIncidentVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Tapping this opens the incident reporting screen to log issues.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('incident')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionIncidentVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionIncidentVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    <!-- Card: Voice -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Voice</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionVoiceVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionVoiceVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Tapping this opens the voice report screen to record audio updates.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('voice')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionVoiceVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionVoiceVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    <!-- Card: Members -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-650 dark:text-teal-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Members</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionMembersVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionMembersVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Tapping this redirects the user to the members list tab.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('members')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionMembersVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionMembersVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    <!-- Card: Mobilize -->
                    <div class="flex items-start justify-between p-4 rounded-xl border border-zinc-200 dark:border-zinc-750 bg-white dark:bg-zinc-900 hover:shadow-md hover:scale-[1.01] transition-all duration-200">
                        <div class="flex gap-3">
                            <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">Mobilize</span>
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full {{ $quickActionMobilizeVisible ? 'bg-green-100 text-green-800 dark:bg-green-900/35 dark:text-green-400' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                        {{ $quickActionMobilizeVisible ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-[11px] leading-normal text-zinc-550 dark:text-zinc-400">Tapping this opens the group mobilization messaging tool.</p>
                            </div>
                        </div>
                        <button wire:click="toggleQuickActionVisibility('mobilize')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $quickActionMobilizeVisible ? 'bg-green-600' : 'bg-zinc-300 dark:bg-zinc-700' }}">
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $quickActionMobilizeVisible ? 'translate-x-4' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
