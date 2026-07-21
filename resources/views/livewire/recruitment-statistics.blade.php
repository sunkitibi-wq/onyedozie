<div class="space-y-6">
    <!-- Header & Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Total Recruits</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['total']) }}</h3>
                <span class="text-xs text-purple-600 font-medium">All Time</span>
            </div>
            <div class="p-3 bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Recruits This Month</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['monthly']) }}</h3>
                <span class="text-xs text-green-600 font-medium">{{ now()->format('F Y') }}</span>
            </div>
            <div class="p-3 bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Recruits Today</p>
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ number_format($stats['today']) }}</h3>
                <span class="text-xs text-blue-600 font-medium">Last 24 Hours</span>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-base font-bold text-zinc-900 dark:text-white">Recruitment Leaderboard</h3>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-9 pr-3 py-1.5 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-800 text-sm focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="Search user...">
                </div>

                <select wire:model.live="timeframe" class="block w-full sm:w-auto py-1.5 px-3 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-800 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 text-zinc-700 dark:text-white">
                    <option value="all">All Time</option>
                    <option value="month">This Month</option>
                    <option value="today">Today</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium">
                        <th class="py-2 pl-2">Rank</th>
                        <th class="py-2">Recruiter Name</th>
                        <th class="py-2">Phone</th>
                        <th class="py-2">Role(s)</th>
                        <th class="py-2 text-right">Recruits Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recruiters as $index => $recruiter)
                        <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                            <td class="py-3 pl-2 text-zinc-500 font-medium">
                                #{{ $recruiters->firstItem() + $index }}
                            </td>
                            <td class="py-3 font-semibold text-zinc-900 dark:text-white">
                                {{ $recruiter->name }}
                            </td>
                            <td class="py-3 text-zinc-600 dark:text-zinc-400">
                                {{ $recruiter->phone }}
                            </td>
                            <td class="py-3 text-zinc-600 dark:text-zinc-400">
                                <span class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 px-2 py-0.5 rounded text-xs">
                                    {{ $recruiter->roles->pluck('name')->implode(', ') ?: 'Volunteer' }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <span class="font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-950/30 px-2.5 py-1 rounded-lg">
                                    {{ $recruiter->recruits_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-500">
                                No recruitment data found for this timeframe/search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recruiters->links() }}
        </div>
    </div>
</div>
