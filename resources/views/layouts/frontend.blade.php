<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Onyendozi Connect | Hon. Ferdinand Dozie Nwankwo' }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Material Symbols Outlined -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
            }
        </style>
    </head>
    <body class="bg-surface text-on-surface font-body-md text-label-sm overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
        
        <!-- Top Navigation Bar -->
        <nav class="bg-surface shadow-sm sticky top-0 z-50 h-20 flex items-center border-b border-outline-variant/30">
            <div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto h-full">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Onyendozi Connect Logo" class="h-12 w-12 rounded-full object-cover shadow-sm">
                    <span class="text-headline-md font-headline-md font-bold text-primary hidden sm:block">Onyendozi Connect</span>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center gap-8">
                    <a class="font-label-md {{ Route::is('home') ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all" href="{{ route('home') }}">Home</a>
                    
                    <!-- Desktop Dropdown: About -->
                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" class="flex items-center gap-1 font-label-md {{ (Route::is('about') || Route::is('founder')) ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all cursor-pointer">
                            <span>About</span>
                            <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-64 bg-surface rounded-lg shadow-lg border border-outline-variant/30 py-2 z-50"
                             style="display: none;">
                            <a class="block px-4 py-2 text-body-md text-on-surface hover:bg-surface-container hover:text-primary transition-all {{ Route::is('about') ? 'font-bold text-primary' : '' }}" href="{{ route('about') }}">Meet Onyendozi</a>
                            <a class="block px-4 py-2 text-body-md text-on-surface hover:bg-surface-container hover:text-primary transition-all {{ Route::is('founder') ? 'font-bold text-primary' : '' }}" href="{{ route('founder') }}">About the President & Founder</a>
                        </div>
                    </div>

                    <a class="font-label-md {{ Route::is('vision') ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all" href="{{ route('vision') }}">Our Vision</a>
                    <a class="font-label-md {{ Route::is('achievements') ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all" href="{{ route('achievements') }}">Achievements</a>
                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" class="flex items-center gap-1 font-label-md {{ (Route::is('gallery') || Route::is('video-gallery') || Route::is('image-gallery')) ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all cursor-pointer">
                            <span>Gallery</span>
                            <span class="material-symbols-outlined text-sm transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-64 bg-surface rounded-lg shadow-lg border border-outline-variant/30 py-2 z-50"
                             style="display: none;">
                            <a class="block px-4 py-2 text-body-md text-on-surface hover:bg-surface-container hover:text-primary transition-all {{ Route::is('gallery') ? 'font-bold text-primary' : '' }}" href="{{ route('gallery') }}">All Galleries</a>
                            <a class="block px-4 py-2 text-body-md text-on-surface hover:bg-surface-container hover:text-primary transition-all {{ Route::is('video-gallery') ? 'font-bold text-primary' : '' }}" href="{{ route('video-gallery') }}">Video Gallery</a>
                            <a class="block px-4 py-2 text-body-md text-on-surface hover:bg-surface-container hover:text-primary transition-all {{ Route::is('image-gallery') ? 'font-bold text-primary' : '' }}" href="{{ route('image-gallery') }}">Image Gallery</a>
                        </div>
                    </div>
                    <a class="font-label-md {{ Route::is('updates') || Route::is('updates.detail') ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all" href="{{ route('updates') }}">News & Updates</a>
                    <a class="font-label-md {{ Route::is('join') ? 'text-primary font-bold' : 'text-on-surface hover:text-primary' }} transition-all" href="{{ route('join') }}">Join the Movement</a>
                </div>

                <!-- CTA & Mobile Trigger -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('join') }}" class="bg-secondary text-on-secondary px-6 py-2.5 font-label-md hover:bg-secondary-container hover:text-on-secondary-container transition-all shadow-sm">
                        Join Now
                    </a>
                    <button class="lg:hidden text-primary flex items-center" @click="mobileMenuOpen = !mobileMenuOpen">
                        <span class="material-symbols-outlined text-3xl" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Nav Menu -->
        <div class="lg:hidden fixed inset-0 z-40 bg-surface/95 backdrop-blur-md pt-24 px-6 space-y-6 flex flex-col border-b border-outline-variant/30" 
             x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             style="display: none;">
            <a class="font-headline-md text-headline-md {{ Route::is('home') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('home') }}">Home</a>
            
            <!-- Mobile Accordion: About -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex justify-between items-center w-full font-headline-md text-headline-md {{ (Route::is('about') || Route::is('founder')) ? 'text-primary font-bold' : 'text-on-surface' }}">
                    <span>About</span>
                    <span class="material-symbols-outlined transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
                </button>
                <div x-show="open" class="pl-4 mt-3 space-y-3 flex flex-col border-l border-outline-variant/30" style="display: none;">
                    <a class="font-headline-md text-headline-md {{ Route::is('about') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('about') }}">Meet Onyendozi</a>
                    <a class="font-headline-md text-headline-md {{ Route::is('founder') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('founder') }}">About the President & Founder</a>
                </div>
            </div>

            <a class="font-headline-md text-headline-md {{ Route::is('vision') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('vision') }}">Our Vision</a>
            <a class="font-headline-md text-headline-md {{ Route::is('achievements') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('achievements') }}">Achievements</a>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex justify-between items-center w-full font-headline-md text-headline-md {{ (Route::is('gallery') || Route::is('video-gallery') || Route::is('image-gallery')) ? 'text-primary font-bold' : 'text-on-surface' }}">
                    <span>Gallery</span>
                    <span class="material-symbols-outlined transition-transform duration-200" :class="open ? 'rotate-180' : ''">keyboard_arrow_down</span>
                </button>
                <div x-show="open" class="pl-4 mt-3 space-y-3 flex flex-col border-l border-outline-variant/30" style="display: none;">
                    <a class="font-headline-md text-headline-md {{ Route::is('gallery') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('gallery') }}">All Galleries</a>
                    <a class="font-headline-md text-headline-md {{ Route::is('video-gallery') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('video-gallery') }}">Video Gallery</a>
                    <a class="font-headline-md text-headline-md {{ Route::is('image-gallery') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('image-gallery') }}">Image Gallery</a>
                </div>
            </div>
            <a class="font-headline-md text-headline-md {{ Route::is('updates') || Route::is('updates.detail') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('updates') }}">News & Updates</a>
            <a class="font-headline-md text-headline-md {{ Route::is('join') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('join') }}">Join the Movement</a>
            <hr class="border-outline-variant/30">
            <a href="{{ route('login') }}" class="text-center bg-primary text-on-primary py-3 font-label-md hover:bg-primary-container transition-all">
                Portal Login
            </a>
        </div>

        <!-- Main slot -->
        <main>
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer -->
        <footer class="bg-surface-container-highest border-t border-outline-variant py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-mobile md:px-margin-desktop py-stack-lg max-w-container-max mx-auto">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover shadow-sm">
                        <span class="font-headline-md text-headline-md font-bold text-primary">Onyendozi Connect</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md leading-relaxed">
                        Uniting Anambra Central through purposeful leadership, grassroots development, and shared prosperity.
                    </p>
                    <div class="flex gap-3 pt-2">
                        <a href="https://www.facebook.com/onyendoziconnect" target="_blank" rel="noreferrer" class="group inline-flex items-center justify-center rounded-full bg-surface p-3 text-on-surface hover:bg-primary hover:text-on-primary transition-colors shadow-sm">
                            <span class="sr-only">Facebook</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                <path d="M22 12.073C22 6.506 17.523 2 12 2S2 6.506 2 12.073c0 5.028 3.657 9.204 8.438 9.93v-7.03H7.898v-2.9h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.244 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.9h-2.33v7.03C18.343 21.277 22 17.1 22 12.073Z"/>
                            </svg>
                        </a>
                        <a href="https://x.com/onyendoziconnect" target="_blank" rel="noreferrer" class="group inline-flex items-center justify-center rounded-full bg-surface p-3 text-on-surface hover:bg-primary hover:text-on-primary transition-colors shadow-sm">
                            <span class="sr-only">X</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                <path d="M23.643 4.937a9.73 9.73 0 0 1-2.828.784 4.94 4.94 0 0 0 2.165-2.724 9.86 9.86 0 0 1-3.127 1.195 4.916 4.916 0 0 0-8.38 4.482A13.962 13.962 0 0 1 1.671 3.149 4.822 4.822 0 0 0 3.195 9.72a4.904 4.904 0 0 1-2.228-.616v.06a4.92 4.92 0 0 0 3.946 4.827 4.996 4.996 0 0 1-2.224.084 4.928 4.928 0 0 0 4.6 3.424A9.867 9.867 0 0 1 .96 19.54a13.94 13.94 0 0 0 7.548 2.209c9.142 0 14.307-7.675 14.307-14.326 0-.218-.005-.435-.014-.65A10.243 10.243 0 0 0 24 4.59a9.816 9.816 0 0 1-2.357.647Z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/onyendoziconnect" target="_blank" rel="noreferrer" class="group inline-flex items-center justify-center rounded-full bg-surface p-3 text-on-surface hover:bg-primary hover:text-on-primary transition-colors shadow-sm">
                            <span class="sr-only">Instagram</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.34 3.608 1.315.975.975 1.253 2.242 1.315 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.34 2.633-1.315 3.608-.975.975-2.242 1.253-3.608 1.315-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.34-3.608-1.315-.975-.975-1.253-2.242-1.315-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.849c.062-1.366.34-2.633 1.315-3.608S5.79 2.225 7.156 2.163C8.421 2.105 8.801 2.093 12 2.093Zm0 1.838c-3.17 0-3.543.012-4.789.069-1.14.054-1.76.24-2.172.402a3.321 3.321 0 0 0-1.205.765 3.321 3.321 0 0 0-.765 1.205c-.162.412-.348 1.032-.402 2.172-.057 1.246-.069 1.619-.069 4.789s.012 3.543.069 4.789c.054 1.14.24 1.76.402 2.172.18.46.437.854.765 1.205.35.35.745.626 1.205.765.412.162 1.032.348 2.172.402 1.246.057 1.619.069 4.789.069s3.543-.012 4.789-.069c1.14-.054 1.76-.24 2.172-.402a3.361 3.361 0 0 0 1.97-1.97c.162-.412.348-1.032.402-2.172.057-1.246.069-1.619.069-4.789s-.012-3.543-.069-4.789c-.054-1.14-.24-1.76-.402-2.172a3.321 3.321 0 0 0-.765-1.205 3.321 3.321 0 0 0-1.205-.765c-.412-.162-1.032-.348-2.172-.402-1.246-.057-1.619-.069-4.789-.069Zm0 4.557a5.266 5.266 0 1 1 0 10.532 5.266 5.266 0 0 1 0-10.532Zm0 1.838a3.428 3.428 0 1 0 0 6.856 3.428 3.428 0 0 0 0-6.856Zm5.406-1.973a1.23 1.23 0 1 1-2.459 0 1.23 1.23 0 0 1 2.459 0Z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="pt-2">
                        <a href="{{ asset('downloads/onyendozi.apk') }}" class="inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md hover:bg-primary-container transition-all shadow-xs" download>
                            <span class="material-symbols-outlined text-xl">android</span>
                            <span>Onyendozi Connect App</span>
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-headline-md text-headline-md text-primary font-bold">Quick Links</h4>
                    <ul class="space-y-2 font-body-md">
                        <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Meet Hon. Dozie</a></li>
                        <li><a href="{{ route('founder') }}" class="hover:text-primary transition-colors">About the President & Founder</a></li>
                        <li><a href="{{ route('vision') }}" class="hover:text-primary transition-colors">Our Vision Document</a></li>
                        <li><a href="{{ route('achievements') }}" class="hover:text-primary transition-colors">Achievements</a></li>
                        <li><a href="{{ route('updates') }}" class="hover:text-primary transition-colors">News & Updates</a></li>
                        <li><a href="{{ route('video-gallery') }}" class="hover:text-primary transition-colors">Video Gallery</a></li>
                        <li><a href="{{ route('image-gallery') }}" class="hover:text-primary transition-colors">Image Gallery</a></li>
                        <li><a href="{{ route('join') }}" class="hover:text-primary transition-colors">Volunteer & Join</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="font-headline-md text-headline-md text-primary font-bold">Contact Info</h4>
                    <ul class="space-y-2 font-body-md text-on-surface-variant">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <span> Hon. Ferdinand Dozie Nwankwo ICT Centre Enugwu Ukwu Njikoka LGA Anambra State.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">mail</span>
                            <span>info@onyendoziconnect.org</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">call</span>
                            <span>+234(0)8036052303 .</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="font-headline-md text-headline-md text-primary font-bold">Stay Updated</h4>
                    <p class="text-on-surface-variant font-body-md">Subscribe to get the latest legislative updates, news, and events.</p>
                    <form class="flex gap-2" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                        <input type="email" placeholder="Your email address" class="bg-surface border border-outline px-4 py-2.5 flex-1 focus:outline-hidden focus:border-primary font-body-md" required>
                        <button type="submit" class="bg-primary text-on-primary px-4 hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined">send</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop mt-12 pt-6 border-t border-outline-variant/30 flex flex-col md:flex-row justify-between items-center gap-4 text-on-surface-variant font-label-sm">
                <p>&copy; {{ date('Y') }} Onyendozi Connect. All rights reserved.</p>
                <p>Powered By: <a href="https://www.henmarkltd.com" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors font-bold">Henmark Info-Tech Ltd.</a></p>
                <div class="flex gap-6">
                    <a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
                    <a href="{{ route('login') }}" class="hover:text-primary transition-colors">Staff Portal</a>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
