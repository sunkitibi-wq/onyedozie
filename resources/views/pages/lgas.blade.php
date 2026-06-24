<x-layouts::app :title="__('LGA Management')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Geographic Infrastructure (LGAs, Wards & PUs)</h1>
        <livewire:lga-management />
    </div>
</x-layouts::app>
