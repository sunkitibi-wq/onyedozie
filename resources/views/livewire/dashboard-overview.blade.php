<div class="space-y-6">
    <!-- Notification messages -->
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Total Supporters</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['total_members']) }}</h3>
                <span class="text-xs text-green-600 font-medium">Active Campaign</span>
            </div>
            <div class="p-3 bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Field Operations</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['total_doors']) }}</h3>
                <span class="text-xs text-blue-600 font-medium">Doors Canvassed</span>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Active Agents</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['active_agents']) }}</h3>
                <span class="text-xs text-amber-600 font-medium">Live (Last 30m)</span>
            </div>
            <div class="p-3 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Election HQ</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['total_results']) }}</h3>
                <span class="text-xs text-red-600 font-medium">PUs Reporting</span>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Approvals and Results -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Pending Coordinator Approvals -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Pending Coordinator Approvals</h3>
                
                @if($pendingUsers->isEmpty())
                    <div class="text-center py-8 text-zinc-500 text-sm">
                        No pending coordinator applications.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Phone</th>
                                    <th class="py-2">Jurisdiction</th>
                                    <th class="py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $user)
                                    <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                        <td class="py-3 font-medium text-zinc-900 dark:text-white">{{ $user->name }}</td>
                                        <td class="py-3 text-zinc-600 dark:text-zinc-300">{{ $user->phone }}</td>
                                        <td class="py-3 text-xs text-zinc-500">
                                            {{ $user->lga?->name ?? 'No LGA' }} / {{ $user->ward?->name ?? 'No Ward' }}
                                        </td>
                                        <td class="py-3 flex gap-2">
                                            <button wire:click="approveUser({{ $user->id }})" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                                                Approve
                                            </button>
                                            <button wire:click="rejectUser({{ $user->id }})" class="px-2.5 py-1 bg-zinc-200 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded text-xs font-semibold">
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Election Day Live Result Feeds -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Live Polling Unit Results Feed</h3>

                @if($recentResults->isEmpty())
                    <div class="text-center py-8 text-zinc-500 text-sm">
                        No results uploaded yet.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentResults as $res)
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-100 dark:border-zinc-800/50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $res->pollingUnit?->name }}</h4>
                                        <p class="text-xs text-zinc-500">Code: {{ $res->pollingUnit?->code }} | Submitted by {{ $res->user?->name }}</p>
                                    </div>
                                    <span class="text-[10px] bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-400 px-2 py-0.5 rounded-full font-medium">EC8A Submitted</span>
                                </div>
                                <div class="mt-3 flex gap-3 text-xs">
                                    @foreach($res->entries as $entry)
                                        <div class="px-3 py-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded">
                                            <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $entry->party }}:</span>
                                            <span class="text-zinc-600 dark:text-zinc-400">{{ $entry->votes }} votes</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Column: Incidents and Map Snapshot -->
        <div class="space-y-6">
            <!-- Incidents & Alerts -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Critical Incidents & Alerts</h3>

                @if($recentIncidents->isEmpty())
                    <div class="text-center py-8 text-zinc-500 text-sm">
                        No incidents reported. All clear.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentIncidents as $inc)
                            <div class="p-3.5 bg-red-50/50 dark:bg-red-950/10 border border-red-200/50 dark:border-red-900/30 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold uppercase tracking-wide text-red-600 dark:text-red-400">{{ $inc->type }}</span>
                                    <span class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded-full font-bold uppercase">{{ $inc->urgency }}</span>
                                </div>
                                <p class="text-xs text-zinc-700 dark:text-zinc-300 mt-1.5">{{ $inc->description }}</p>
                                <div class="mt-2 flex justify-between items-center text-[10px] text-zinc-500">
                                    <span>By: {{ $inc->user?->name }}</span>
                                    <span>{{ $inc->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Geographic Coverage Map -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">Campaign Geographic Coverage</h3>
                <p class="text-xs text-zinc-500 mb-4">Representation of mobilization in 7 LGAs of Anambra Central Senatorial District.</p>
                
                <div class="h-44 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex flex-col items-center justify-center border border-zinc-200 dark:border-zinc-700 p-4">
                    <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span class="text-xs font-bold text-zinc-800 dark:text-zinc-200">7 / 7 LGAs Fully Covered</span>
                    <span class="text-[10px] text-zinc-500 mt-1">Anaocha · Awka N. · Awka S. · Dunukofia · Idemili N. · Idemili S. · Njikoka</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recruitment Statistics Section -->
    <div class="mt-6">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Recruitment Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Overall Stats Summary -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex flex-col justify-center">
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-2">Total Recruited Members</p>
                <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total_recruits']) }}</h3>
                
                <div class="mt-6 border-t border-zinc-100 dark:border-zinc-800 pt-4">
                    <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-2">Recruited This Month</p>
                    <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['monthly_recruits']) }}</h3>
                </div>
            </div>

            <!-- Top Recruiters (Overall) -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-4 flex items-center justify-between">
                    Top Recruiters (Overall)
                    <span class="text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 px-2 py-0.5 rounded-full">All Time</span>
                </h3>
                @if($topRecruiters->isEmpty())
                    <p class="text-xs text-zinc-500 text-center py-4">No recruitment data available.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($topRecruiters as $index => $recruiter)
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-xs font-bold text-zinc-500">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $recruiter->name }}</p>
                                        <p class="text-[10px] text-zinc-500">{{ $recruiter->roles->pluck('name')->implode(', ') ?: 'Volunteer' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $recruiter->recruits_count }}</span>
                                    <span class="text-[10px] text-zinc-500 block -mt-1">members</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Top Recruiters (This Month) -->
            <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-4 flex items-center justify-between">
                    Highest Recruiters
                    <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-0.5 rounded-full">{{ now()->format('M Y') }}</span>
                </h3>
                @if($monthlyTopRecruiters->isEmpty())
                    <p class="text-xs text-zinc-500 text-center py-4">No recruits yet this month.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($monthlyTopRecruiters as $index => $recruiter)
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-xs font-bold text-zinc-500">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $recruiter->name }}</p>
                                        <p class="text-[10px] text-zinc-500">{{ $recruiter->roles->pluck('name')->implode(', ') ?: 'Volunteer' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $recruiter->recruits_count }}</span>
                                    <span class="text-[10px] text-zinc-500 block -mt-1">members</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
