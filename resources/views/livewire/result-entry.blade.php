<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button wire:click="selectTab('scoreboard')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'scoreboard' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Election Day Scoreboard
        </button>
        <button wire:click="selectTab('verification')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'verification' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            EC8A Verification Queue
        </button>
        <button wire:click="selectTab('incidents')" class="px-4 py-2 text-sm font-semibold border-b-2 {{ $activeTab === 'incidents' ? 'border-green-600 text-green-600 dark:text-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Incidents Control
        </button>
    </div>

    <!-- SCOREBOARD TAB -->
    @if($activeTab === 'scoreboard')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Scoreboard Standings -->
            <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Anambra Central Live Scoreboard (Verified Votes)</h3>
                    <div class="text-xs text-zinc-500 font-semibold bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full border border-zinc-200 dark:border-zinc-750">
                        Reporting PUs: {{ $reportingStats['reporting'] }} / {{ $reportingStats['total'] }} ({{ $reportingStats['percent'] }}%)
                    </div>
                </div>

                @if($scoreboard->isEmpty())
                    <div class="text-center py-16 text-zinc-500 text-sm">
                        Waiting for verified result uploads...
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($scoreboard as $score)
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $score->party }}</span>
                                    <span class="font-bold text-zinc-900 dark:text-white">{{ number_format($score->total_votes) }} votes</span>
                                </div>
                                <div class="w-full bg-zinc-100 dark:bg-zinc-800 h-3.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-600 dark:bg-green-500 rounded-full" style="width: {{ $scoreboard->first()->total_votes > 0 ? min(100, ($score->total_votes / $scoreboard->first()->total_votes) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Coverage summary -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Reporting Progress</h3>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 dark:bg-green-950 dark:text-green-400">
                                PU Reporting Rate
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold inline-block text-green-600 dark:text-green-400">
                                {{ $reportingStats['percent'] }}%
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2.5 text-xs flex rounded-full bg-green-50 dark:bg-green-950/20">
                        <div style="width:{{ $reportingStats['percent'] }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-600"></div>
                    </div>
                </div>
                <div class="mt-6 space-y-3 text-xs text-zinc-550 dark:text-zinc-400">
                    <div class="flex justify-between">
                        <span>Total Polling Units:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $reportingStats['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Reporting Polling Units:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $reportingStats['reporting'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pending Submissions:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $reportingStats['total'] - $reportingStats['reporting'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified PU list -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm mt-6">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Verified PU Results Log</h3>
            @if($allResults->isEmpty())
                <div class="text-center py-6 text-zinc-500 text-sm">
                    No verified results yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                <th class="py-2">Polling Unit</th>
                                <th class="py-2">Ward & LGA</th>
                                <th class="py-2">Result Summary</th>
                                <th class="py-2">Uploaded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allResults as $res)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white">{{ $res->pollingUnit?->name }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400">{{ $res->pollingUnit?->ward?->name }} ({{ $res->pollingUnit?->ward?->lga?->name }})</td>
                                    <td class="py-3">
                                        <div class="flex gap-2">
                                            @foreach($res->entries as $entry)
                                                <span class="text-xs bg-zinc-50 dark:bg-zinc-800 border border-zinc-250 dark:border-zinc-700 px-2 py-0.5 rounded">
                                                    {{ $entry->party }}: {{ $entry->votes }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 text-xs text-zinc-500">{{ $res->user?->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $allResults->links() }}</div>
                </div>
            @endif
        </div>
    @endif

    <!-- VERIFICATION QUEUE TAB -->
    @if($activeTab === 'verification')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Queue List -->
            <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Pending Result Sheets Verification Queue</h3>
                @if($pendingVerification->isEmpty())
                    <div class="text-center py-16 text-zinc-500 text-sm">
                        No results pending verification. All uploads processed!
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingVerification as $res)
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-150 dark:border-zinc-850 rounded-xl flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $res->pollingUnit?->name }}</h4>
                                    <p class="text-xs text-zinc-500">Code: {{ $res->pollingUnit?->code }} | Submitted by {{ $res->user?->name }}</p>
                                    <div class="mt-2 flex gap-2 text-xs">
                                        @foreach($res->entries as $entry)
                                            <span class="font-semibold text-zinc-650 dark:text-zinc-350 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-750 px-2 py-0.5 rounded">
                                                {{ $entry->party }}: {{ $entry->votes }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <button wire:click="selectResultForVerification({{ $res->id }})" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                                    Verify Result
                                </button>
                            </div>
                        @endforeach
                        <div class="mt-4">{{ $pendingVerification->links() }}</div>
                    </div>
                @endif
            </div>

            <!-- Verification Panel -->
            <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                @if($selectedResultId)
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Verify EC8A Sheet</h3>
                    <form wire:submit.prevent="verifyResult" class="space-y-4">
                        <!-- Image placeholder/viewer -->
                        <div class="h-44 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex flex-col items-center justify-center border border-zinc-200 dark:border-zinc-750 p-2 text-center">
                            <span class="text-xs text-zinc-500 font-mono break-all mb-2">EC8A Form Sheet</span>
                            <a href="#" class="text-xs text-green-600 dark:text-green-400 hover:underline font-bold">View full uploaded image document</a>
                        </div>

                        <!-- Edit Vote Counts -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-500">Validate Entered Votes</h4>
                            @foreach($votesData as $entryId => $votes)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xs font-bold text-zinc-700 dark:text-zinc-350">Party #{{ $loop->iteration }}:</span>
                                    <input type="number" wire:model="votesData.{{ $entryId }}" class="w-24 px-2.5 py-1 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-right text-xs text-zinc-900 dark:text-white">
                                </div>
                            @endforeach
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                Verify & Approve
                            </button>
                            <button type="button" wire:click="closeVerification" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded-lg text-sm font-semibold">
                                Cancel
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-12 text-zinc-500 text-sm">
                        Select a pending submission from the queue list to perform EC8A image document validation.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- INCIDENTS TAB -->
    @if($activeTab === 'incidents')
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Election Day Critical Incidents Feed</h3>
                
                <!-- Filters -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <select wire:model.live="filterUrgency" class="px-2.5 py-1 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-xs">
                        <option value="">All Urgencies</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>

                    <select wire:model.live="filterIncidentStatus" class="px-2.5 py-1 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-xs">
                        <option value="">All Statuses</option>
                        <option value="reported">Reported</option>
                        <option value="escalated">Escalated</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
            </div>

            @if($incidents->isEmpty())
                <div class="text-center py-12 text-zinc-500 text-sm">
                    No incidents matching selected filter. All clear.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($incidents as $inc)
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-150 dark:border-zinc-850 flex flex-col md:flex-row justify-between gap-4">
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $inc->urgency === 'critical' ? 'bg-red-600 text-white' : ($inc->urgency === 'high' ? 'bg-orange-500 text-white' : 'bg-zinc-200 text-zinc-700') }}">{{ $inc->urgency }}</span>
                                    <span class="text-xs font-semibold text-zinc-500">Type: {{ $inc->type }}</span>
                                    <span class="text-xs text-zinc-400">| Reported by {{ $inc->user?->name }} | {{ $inc->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-zinc-800 dark:text-zinc-200">{{ $inc->description }}</p>
                            </div>
                            <div class="flex items-center gap-2 h-fit md:mt-2">
                                @if($inc->status !== 'resolved')
                                    <button wire:click="updateIncidentStatus({{ $inc->id }}, 'resolved')" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                                        Resolve
                                    </button>
                                @endif
                                @if($inc->status === 'reported')
                                    <button wire:click="updateIncidentStatus({{ $inc->id }}, 'escalated')" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded text-xs font-semibold">
                                        Escalate
                                    </button>
                                @endif
                                <span class="px-2.5 py-1 border border-zinc-200 dark:border-zinc-700 rounded text-xs text-zinc-600 dark:text-zinc-400 font-semibold bg-white dark:bg-zinc-900">
                                    Status: {{ ucfirst($inc->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">{{ $incidents->links() }}</div>
                </div>
            @endif
        </div>
    @endif
</div>
