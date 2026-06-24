<div class="space-y-6">
    <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">Artisan Console Controls</h3>
        <p class="text-xs text-zinc-500 mb-6">Select a whitelisted artisan task shortcut below, or write custom parameters to execute.</p>

        <!-- Command Quick Actions Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            @foreach($allowedCommands as $cmd => $desc)
                <button wire:click="runCommand('{{ $cmd }}')" class="p-3 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-750 border border-zinc-200 dark:border-zinc-700 text-left rounded-xl transition-colors">
                    <span class="block text-xs font-bold font-mono text-green-600 dark:text-green-400 mb-1">php artisan {{ $cmd }}</span>
                    <span class="block text-[10px] text-zinc-500">{{ $desc }}</span>
                </button>
            @endforeach
        </div>

        <!-- Terminal Window Console Log -->
        <div class="space-y-2">
            <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-zinc-550 dark:text-zinc-400">Terminal Log Console</span>
                <button wire:click="clearConsole" class="text-green-600 dark:text-green-400 hover:underline font-semibold">
                    Clear Console
                </button>
            </div>
            
            <pre class="w-full h-80 p-4 bg-black border border-zinc-800 rounded-xl font-mono text-xs text-green-400 overflow-y-auto whitespace-pre-wrap leading-relaxed shadow-inner">{{ $outputBuffer }}</pre>
        </div>

        <!-- Custom Command input box -->
        <form wire:submit.prevent="runCommand()" class="mt-4 flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-2.5 text-zinc-400 font-mono text-sm">php artisan</span>
                <input type="text" wire:model="commandString" class="w-full pl-28 pr-4 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white font-mono focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="migrate:status">
            </div>
            <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold whitespace-nowrap">
                Run Command
            </button>
        </form>
    </div>
</div>
