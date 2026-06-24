<x-layouts::app :title="__('Role-Based Access Control')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Role-Based Access Control (RBAC)</h1>
        <livewire:rbac-management />
    </div>
</x-layouts::app>
