<x-layouts::app :title="__('Task Manager')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Task Manager</h1>
        <livewire:task-manager />
    </div>
</x-layouts::app>
