<x-layouts::app :title="__('Activity Logs')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Audit Trail & Action Logs</h1>
        <livewire:activity-logs />
    </div>
</x-layouts::app>
