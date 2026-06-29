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
            <a class="font-headline-md text-headline-md {{ Route::is('join') ? 'text-primary font-bold' : 'text-on-surface' }}" @click="mobileMenuOpen = false" href="{{ route('join') }}">Join the Movement</a>
            <hr class="border-outline-variant/30">
            <a href="{{ route('login') }}" class="text-center bg-primary text-on-primary py-3 font-label-md hover:bg-primary-container transition-all">
                Staff Login
            </a>
        </div>

        <!-- Main slot -->
        <main>
            @yield('content')
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
                    <div class="flex gap-4 pt-2">
                        <!-- Social Media Icon Placeholders using Material Icons -->
                        <a href="#" class="text-primary hover:text-secondary transition-colors"><span class="material-symbols-outlined">public</span></a>
                        <a href="#" class="text-primary hover:text-secondary transition-colors"><span class="material-symbols-outlined">share</span></a>
                        <a href="#" class="text-primary hover:text-secondary transition-colors"><span class="material-symbols-outlined">chat</span></a>
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
                        <li><a href="{{ route('join') }}" class="hover:text-primary transition-colors">Volunteer & Join</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="font-headline-md text-headline-md text-primary font-bold">Contact Info</h4>
                    <ul class="space-y-2 font-body-md text-on-surface-variant">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <span>Campaign Headquarters, Awka, Anambra State</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">mail</span>
                            <span>info@onyendoziconnect.org</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">call</span>
                            <span>+234 (0) 800 ONYENDOZI</span>
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
