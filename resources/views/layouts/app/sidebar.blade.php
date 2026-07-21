<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:spacer />
                <livewire:notification-bell />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('users')" :current="request()->routeIs('users')" wire:navigate>
                        {{ __('Campaign Members') }}
                    </flux:sidebar.item>
                    @if(auth()->user()->hasRole('Super Admin'))
                        <flux:sidebar.item icon="chart-bar" :href="route('recruitment-statistics')" :current="request()->routeIs('recruitment-statistics')" wire:navigate>
                            {{ __('Recruitment Stats') }}
                        </flux:sidebar.item>
                    @endif
                    <flux:sidebar.item icon="map-pin" :href="route('lgas')" :current="request()->routeIs('lgas')" wire:navigate>
                        {{ __('Geographic Hierarchy') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="map" :href="route('location-tracking')" :current="request()->routeIs('location-tracking')" wire:navigate>
                        {{ __('Live Tracking') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="map" :href="route('canvassing-tracker')" :current="request()->routeIs('canvassing-tracker')" wire:navigate>
                        {{ __('Canvassing Tracker') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" :href="route('tasks')" :current="request()->routeIs('tasks')" wire:navigate>
                        {{ __('Task Manager') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('communications')" :current="request()->routeIs('communications')" wire:navigate>
                        {{ __('Communications') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="newspaper" :href="route('news')" :current="request()->routeIs('news')" wire:navigate>
                        {{ __('News & Updates') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="key" :href="route('rbac')" :current="request()->routeIs('rbac')" wire:navigate>
                        {{ __('Roles & Permissions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="trophy" :href="route('results')" :current="request()->routeIs('results')" wire:navigate>
                        {{ __('Election Day HQ') }}
                    </flux:sidebar.item>
                    @if(auth()->user()->hasRole('Candidate Dashboard') || auth()->user()->hasRole('Super Admin'))
                        <flux:sidebar.item icon="layout-grid" :href="route('candidate-dashboard')" :current="request()->routeIs('candidate-dashboard')" wire:navigate>
                            {{ __('Candidate View') }}
                        </flux:sidebar.item>
                    @endif
                    @can('manage users')
                        <flux:sidebar.item icon="document-text" :href="route('activity-logs')" :current="request()->routeIs('activity-logs')" wire:navigate>
                            {{ __('Activity Logs') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="bell" :href="route('notifications')" :current="request()->routeIs('notifications')" wire:navigate>
                            {{ __('Notifications') }}
                        </flux:sidebar.item>
                        @if(auth()->user()->hasRole('Super Admin'))
                            <flux:sidebar.item icon="flag" :href="route('reports')" :current="request()->routeIs('reports')" wire:navigate>
                                {{ __('Reports Management') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="folder-open" :href="route('artisan-console')" :current="request()->routeIs('artisan-console')" wire:navigate>
                                {{ __('Artisan Console') }}
                            </flux:sidebar.item>
                        @endif
                    @endcan
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
