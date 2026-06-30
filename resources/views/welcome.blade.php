@extends('layouts.frontend')

@section('content')
<!-- Hero Section with Alpine.js Sliding Carousel -->
<section class="relative min-h-[80vh] flex items-center overflow-hidden bg-surface border-b border-outline-variant/30"
         x-data="{ 
             activeSlide: 1, 
             slidesCount: 3, 
             autoPlayInterval: null,
             startAutoPlay() {
                 this.autoPlayInterval = setInterval(() => {
                     this.activeSlide = this.activeSlide === this.slidesCount ? 1 : this.activeSlide + 1;
                 }, 7000);
             },
             stopAutoPlay() {
                 clearInterval(this.autoPlayInterval);
             }
         }"
         x-init="startAutoPlay()"
         @mouseenter="stopAutoPlay()"
         @mouseleave="startAutoPlay()">
    
    <!-- Pattern Background -->
    <div class="absolute inset-0 pattern-bg opacity-40 pointer-events-none"></div>

    <!-- Slides Container -->
    <div class="w-full relative z-10 py-12 md:py-20">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            
            <!-- Slide 1: Official Senatorial Campaign welcome -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center" 
                 x-show="activeSlide === 1" 
                 x-transition:enter="transition ease-out duration-1000 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-500 transform absolute inset-x-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="font-label-md uppercase tracking-wider">Campaign 2027</span>
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-primary leading-tight font-bold">
                        ONYENDOZI CONNECT: The Official Campaign of Hon. Ferdinand Dozie Nwankwo
                    </h1>
                    <p class="font-headline-md text-headline-md text-on-surface-variant border-l-4 border-secondary-container pl-4">
                        APGA Candidate for Anambra Central Senatorial District – 2027. <br class="hidden md:block"/>
                        <span class="text-secondary font-bold">ONE LEADER. ONE PEOPLE. ONE VISION.</span>
                    </p>
                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Welcome to Onyendozi Connect—the official digital platform established to unite the people of Anambra Central under a common vision of purposeful leadership, sustainable development, inclusive governance, and shared prosperity.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('join') }}" class="bg-primary text-on-primary px-8 py-4 font-label-md hover:bg-primary-container transition-all flex items-center gap-3 shadow-lg rounded-xl">
                            Join the Movement
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                        <a href="{{ route('vision') }}" class="bg-white text-primary border-2 border-primary px-8 py-4 font-label-md hover:bg-primary/5 transition-all rounded-xl">
                            View Vision Document
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative mt-12 lg:mt-0">
                    <div class="aspect-square bg-surface-container-high relative overflow-hidden shadow-2xl rounded-2xl border-4 border-white">
                        <img class="object-cover w-full h-full" alt="Hon. Dozie Nwankwo campaign portrait" src="{{ asset('images/dozie.jpg') }}"/>
                        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-primary/95 via-primary/50 to-transparent text-on-primary">
                            <p class="font-headline-md text-headline-md italic">"Leadership is Service."</p>
                            <p class="font-label-md">— Hon. Dozie Nwankwo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Healthcare Outreach -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center" 
                 x-show="activeSlide === 2" 
                 x-transition:enter="transition ease-out duration-1000 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-500 transform absolute inset-x-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 style="display: none;">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="font-label-md uppercase tracking-wider">Healthcare outreach</span>
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-primary leading-tight font-bold">
                        Empowering Communities: Accessible Healthcare for All Wards
                    </h1>
                    <p class="font-headline-md text-headline-md text-on-surface-variant border-l-4 border-secondary-container pl-4">
                        Over <span class="text-primary font-bold">50,000 citizens reached</span> through our free mobile medical clinics and healthcare outreach initiatives.
                    </p>
                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        We believe that a healthy population is the foundation of economic progress. Our mobile clinics provide essential surgeries, checkups, and drugs directly to remote villages and vulnerable groups.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('vision') }}#healthcare" class="bg-primary text-on-primary px-8 py-4 font-label-md hover:bg-primary-container transition-all flex items-center gap-3 shadow-lg rounded-xl">
                            Our Health Agenda
                            <span class="material-symbols-outlined">health_and_safety</span>
                        </a>
                        <a href="{{ route('join') }}" class="bg-white text-primary border-2 border-primary px-8 py-4 font-label-md hover:bg-primary/5 transition-all rounded-xl">
                            Volunteer as Medical Staff
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative mt-12 lg:mt-0">
                    <div class="aspect-square bg-surface-container-high relative overflow-hidden shadow-2xl rounded-2xl border-4 border-white">
                        <img class="object-cover w-full h-full" alt="Medical mission in rural community" src="{{ asset('images/gallery/medical.jpeg') }}"/>
                        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-primary/95 via-primary/50 to-transparent text-on-primary">
                            <p class="font-headline-md text-headline-md italic">"Every constituent deserves standard care."</p>
                        </div>
                    </div>
                </div>
            </div>
         

            <!-- Slide 4: Youth Empowerment & Education -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center" 
                 x-show="activeSlide === 4" 
                 x-transition:enter="transition ease-out duration-1000 transform"
                 x-transition:enter-start="opacity-0 translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-500 transform absolute inset-x-0"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-12"
                 style="display: none;">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="font-label-md uppercase tracking-wider">Education & Youth</span>
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-primary leading-tight font-bold">
                        Securing the Future: Investing in Quality Education
                    </h1>
                    <p class="font-headline-md text-headline-md text-on-surface-variant border-l-4 border-secondary-container pl-4">
                        Empowering the next generation through <span class="text-primary font-bold">scholarships, school supplies, and vocational training</span>.
                    </p>
                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Under the Ferdinand Dozie Nwankwo Foundation, over 2,000 students receive support annually. We are building computer laboratories and providing digital learning tools to secondary schools.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('about') }}#foundation" class="bg-primary text-on-primary px-8 py-4 font-label-md hover:bg-primary-container transition-all flex items-center gap-3 shadow-lg rounded-xl">
                            Our Legacy Projects
                            <span class="material-symbols-outlined">school</span>
                        </a>
                        <a href="{{ route('join') }}" class="bg-white text-primary border-2 border-primary px-8 py-4 font-label-md hover:bg-primary/5 transition-all rounded-xl">
                            Join Youth Wing
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative mt-12 lg:mt-0">
                    <div class="aspect-square bg-surface-container-high relative overflow-hidden shadow-2xl rounded-2xl border-4 border-white">
                        <img class="object-cover w-full h-full" alt="Students with scholarships and notebooks" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQDyq6rDLkK0k3MJnUuYhDVXHbq6eOjuLiIkvMmqJ2kRz7NBL3aDneLPe8KVCbt-WgDQqKXGBQTwZb3KlQmsBTQ0P-LYsD5wTqT2LXi0AfPal4jP9t7wqEnCJhXuG04mudWwZA-sMiBVORaB8yGdFGdVheDsET-Cu6vwla66mQ63kA3yGMQpDyBZHbigF1YxR7rOaBY6r1e9SNtDV7c0iNXXWoYCE30qdqJ6ASJKkebWE3u50zRl5xnkd_C-rGbT6-0UMtvjpzeWP_"/>
                        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-primary/95 via-primary/50 to-transparent text-on-primary">
                            <p class="font-headline-md text-headline-md italic">"Education is the ticket to global competitiveness."</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide Navigation Indicators (dots) -->
            <div class="flex justify-center gap-3 mt-12 relative z-30">
                <template x-for="i in slidesCount" :key="i">
                    <button class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="activeSlide === i ? 'bg-primary scale-125' : 'bg-outline-variant hover:bg-primary/60'"
                            @click="activeSlide = i"></button>
                </template>
            </div>

        </div>
    </div>
</section>

 
<!-- Bento Grid Stats/Features -->
<section class="py-16 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="p-8 bg-white border-t-4 border-secondary-container shadow-sm flex flex-col justify-between rounded-xl bento-card">
                <div class="space-y-4">
                    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">groups</span>
                    <h3 class="font-headline-md text-headline-md text-primary font-bold">Community Driven</h3>
                    <p class="text-on-surface-variant font-body-md leading-relaxed">Our strength lies in our collective voice. Every ward, every LGA, working together towards a single vision.</p>
                </div>
            </div>
            
            <div class="p-8 bg-primary text-on-primary shadow-md flex flex-col justify-between rounded-xl bento-card">
                <div class="space-y-4">
                    <span class="material-symbols-outlined text-secondary-container text-4xl" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <h3 class="font-headline-md text-headline-md font-bold">Proven Track Record</h3>
                    <p class="opacity-90 font-body-md leading-relaxed">Over a decade of dedicated public service, active bill sponsors, and concrete development projects.</p>
                </div>
            </div>
            
            <div class="p-8 bg-white border border-outline-variant shadow-sm flex flex-col justify-between rounded-xl bento-card">
                <div class="space-y-4">
                    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">query_stats</span>
                    <h3 class="font-headline-md text-headline-md text-primary font-bold">Transparency</h3>
                    <p class="text-on-surface-variant font-body-md leading-relaxed">Open communications, digital project tracking, and constant consultation at the grassroots level.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meet Hon. Ferdinand Dozie Nwankwo Teaser -->
<section class="py-24 bg-surface-container-lowest">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <h2 class="font-headline-lg text-headline-lg text-primary font-bold">Meet Hon. Ferdinand Dozie Nwankwo</h2>
            <div class="accent-bar"></div>
            <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                Hon. Ferdinand Dozie Nwankwo, affectionately known as "Onyendozi," is a seasoned legislator with a heart for the people. His journey in public service has been defined by a relentless commitment to human capital development and infrastructure transformation.
            </p>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                As the APGA candidate for the 2027 Anambra Central Senatorial District election, he brings a wealth of experience, a network of influence, and a deep-seated passion for the advancement of our people. From healthcare initiatives to youth scholarship programs, his impact is felt across the seven local governments of the district.
            </p>
            <ul class="space-y-4 pt-4 font-body-md">
                <li class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Experienced Legislator</h4>
                        <p class="text-label-sm text-on-surface-variant">Former member of the House of Representatives with key bills passed.</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Grassroots Empowerment</h4>
                        <p class="text-label-sm text-on-surface-variant">Direct impact on thousands of lives through the Ferdinand Dozie Nwankwo Foundation.</p>
                    </div>
                </li>
            </ul>
            <div class="pt-4">
                <a href="{{ route('about') }}" class="text-primary font-bold font-label-md inline-flex items-center gap-2 hover:translate-x-2 transition-transform duration-300">
                    Read Full Biography <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 relative mt-12 lg:mt-0">
            <div class="pt-12">
                <img class="w-full aspect-[3/4] object-cover shadow-xl rounded-xl border-4 border-white" alt="Dozie Nwankwo engaging elders" src="{{ asset('images/gallery/medical.jpeg') }}"/>
            </div>
            <div>
                <img class="w-full aspect-[3/4] object-cover shadow-xl rounded-xl border-4 border-white" alt="Dozie Nwankwo addressing youth" src="{{ asset('images/do2.jpg')}}"/>
            </div>
            <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-secondary-container/10 -z-10 pattern-bg rounded-xl"></div>
        </div>
    </div>
</section>

<!-- Our Shared Vision Section -->
<section class="py-24 bg-primary text-on-primary relative overflow-hidden">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center mb-16 space-y-4">
        <h2 class="font-headline-lg text-headline-lg font-bold">Our Shared Vision</h2>
        <p class="font-body-lg text-body-lg text-on-primary/80 max-w-3xl mx-auto leading-relaxed">
            A multi-pillar approach to transforming Anambra Central into a hub of innovation, security, and wealth creation.
        </p>
    </div>
    
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="p-8 border border-on-primary/20 hover:bg-white/5 transition-all rounded-xl shadow-xs space-y-6">
            <div class="w-12 h-12 bg-secondary-container text-on-secondary-container flex items-center justify-center rounded-lg">
                <span class="material-symbols-outlined text-2xl">health_and_safety</span>
            </div>
            <h3 class="font-headline-md text-headline-md font-bold">Inclusive Governance</h3>
            <p class="font-body-md text-on-primary/80 leading-relaxed">Ensuring every voice in the seven LGAs is heard and represented in the hallowed chambers of the Senate.</p>
        </div>
        
        <div class="p-8 border border-on-primary/20 hover:bg-white/5 transition-all rounded-xl shadow-xs space-y-6">
            <div class="w-12 h-12 bg-secondary-container text-on-secondary-container flex items-center justify-center rounded-lg">
                <span class="material-symbols-outlined text-2xl">engineering</span>
            </div>
            <h3 class="font-headline-md text-headline-md font-bold">Sustainable Infrastructure</h3>
            <p class="font-body-md text-on-primary/80 leading-relaxed">Lobbying for federal projects that will revitalize local transport networks, water supplies, and solar power distribution.</p>
        </div>
        
        <div class="p-8 border border-on-primary/20 hover:bg-white/5 transition-all rounded-xl shadow-xs space-y-6">
            <div class="w-12 h-12 bg-secondary-container text-on-secondary-container flex items-center justify-center rounded-lg">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <h3 class="font-headline-md text-headline-md font-bold">Human Capital</h3>
            <p class="font-body-md text-on-primary/80 leading-relaxed">Aggressive investment in education, ICT training, entrepreneurship grants, and skills acquisition for the youth.</p>
        </div>
    </div>
    
    <div class="text-center mt-12">
        <a href="{{ route('vision') }}" class="inline-flex items-center gap-2 bg-secondary text-on-secondary px-8 py-3 rounded-xl font-label-md hover:bg-secondary-container hover:text-on-secondary-container transition-colors shadow-md">
            Explore Full Vision Documents
            <span class="material-symbols-outlined">menu_book</span>
        </a>
    </div>
</section>

<!-- App Promotion Section -->
<section id="app-download-section" class="py-24 bg-surface-container-low border-t border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
        <!-- Text Column -->
        <div class="lg:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full">
                <span class="material-symbols-outlined text-sm">cell_tower</span>
                <span class="font-label-md uppercase tracking-wider font-semibold">Join the Movement. Stay Connected. Get Involved.</span>
            </div>
            
            <h2 class="font-headline-lg text-headline-lg text-primary font-bold leading-tight">
                Download the Onyendozi Connect App Today
            </h2>
            
            <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                Download the Onyendozi Connect App today and become part of a growing community connecting the people of Anambra Central to Federal Government development opportunities.
            </p>
            
            <div class="space-y-4 py-2">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                    </span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Receive the latest updates and announcements</h4>
                        <p class="text-label-sm text-on-surface-variant font-medium">Get instant push notifications and alerts on critical constituency news directly on your mobile device.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                    </span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Register for empowerment and intervention programmes</h4>
                        <p class="text-label-sm text-on-surface-variant font-medium">Apply for training grants, student scholarships, and business support programs easily.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                    </span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Stay informed about community development initiatives</h4>
                        <p class="text-label-sm text-on-surface-variant font-medium">Track local projects, clean water stations, and school infrastructure improvements in real-time.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-sm font-bold">check</span>
                    </span>
                    <div>
                        <h4 class="font-label-md text-on-surface font-bold">Connect directly with the Onyendozi Connect network</h4>
                        <p class="text-label-sm text-on-surface-variant font-medium">Engage with team coordinators, participate in surveys, and make your voice heard.</p>
                    </div>
                </div>
            </div>
            
            <p class="font-body-md text-body-md text-on-surface font-semibold leading-relaxed">
                Download the Onyendozi Connect App today and be part of the journey toward a better Anambra Central!
            </p>
            
            <p class="font-body-sm text-body-sm text-on-surface-variant italic">
                Onyendozi Connect – Connecting Anambra Central Citizens to Federal Government Developmental Projects.
            </p>

            <div class="flex flex-wrap gap-4 pt-4">
                <a href="{{ asset('downloads/onyendozi.apk') }}" class="bg-primary text-on-primary px-8 py-4 font-label-md hover:bg-primary-container transition-all flex items-center gap-3 shadow-lg rounded-xl" download>
                    <span class="material-symbols-outlined text-2xl">android</span>
                    <div>
                        <span class="block text-left text-[10px] uppercase font-bold tracking-wider opacity-85 leading-none">Download for Android</span>
                        <span class="block font-bold text-base leading-tight mt-0.5">Onyendozi Connect APK</span>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Mockup Column -->
        <div class="lg:col-span-5 flex justify-center">
            <!-- Sleek CSS Phone Mockup -->
            <div class="relative border-slate-900 bg-slate-900 border-[12px] rounded-[2.5rem] h-[560px] w-[280px] shadow-2xl overflow-hidden flex flex-col">
                <!-- Speaker and Camera notch -->
                <div class="absolute top-0 inset-x-0 h-6 bg-slate-900 flex justify-center items-center z-40">
                    <div class="w-16 h-4 bg-black rounded-b-xl flex justify-center items-center">
                        <div class="w-2 h-2 bg-slate-800 rounded-full mr-2"></div>
                        <div class="w-6 h-1 bg-slate-800 rounded-full"></div>
                    </div>
                </div>
                
                <!-- Simulated Phone Screen content -->
                <div class="flex-1 bg-surface flex flex-col pt-6 overflow-hidden">
                    <!-- App Top Nav -->
                    <div class="bg-primary text-on-primary p-4 flex items-center gap-2.5 shadow-sm shrink-0">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-6 w-6 rounded-full object-cover">
                        <span class="text-xs font-bold font-headline-md tracking-wider">Onyendozi Connect</span>
                    </div>
                    
                    <!-- Dashboard Scrollable list -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 scrollbar-none text-[10px] text-on-surface">
                        <!-- Welcome User Card -->
                        <div class="bg-primary/5 p-3 rounded-lg border border-primary/10">
                            <p class="font-bold text-primary">Welcome to Onyendozi Connect</p>
                            <p class="text-[9px] text-on-surface-variant mt-0.5">Connecting Anambra Central to development opportunities.</p>
                        </div>
                        
                        <!-- Mini News Item -->
                        <div class="bg-white p-2.5 rounded-lg border border-outline-variant/30 space-y-1.5 shadow-2xs">
                            <span class="px-1.5 py-0.5 bg-secondary-container/20 text-on-secondary-container rounded-sm font-bold text-[8px] uppercase tracking-wider">ANNOUNCEMENT</span>
                            <p class="font-bold">Ferdinand Dozie Nwankwo Foundation Scholarship Scheme</p>
                            <p class="text-[9px] text-on-surface-variant">Annual scholarship for over 4,500 students. Application forms are now available.</p>
                            <a href="#" class="text-primary font-bold text-[8px] block">APPLY NOW &rarr;</a>
                        </div>
                        
                        <!-- Project Update Card -->
                        <div class="bg-white p-2.5 rounded-lg border border-outline-variant/30 space-y-1.5 shadow-2xs">
                            <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded-sm font-bold text-[8px] uppercase tracking-wider">INFRASTRUCTURE</span>
                            <p class="font-bold">New Motorized Borehole Project</p>
                            <p class="text-[9px] text-on-surface-variant">Completed solar-powered clean water borehole at Aguluzigbo, Anaocha LGA.</p>
                        </div>

                        <!-- Quick Action Button -->
                        <div class="bg-secondary text-on-secondary text-center py-2.5 rounded-lg font-bold shadow-xs cursor-pointer hover:bg-secondary-container">
                            Register as a Volunteer
                        </div>
                    </div>

                    <!-- Bottom Nav Bar mockup -->
                    <div class="bg-white border-t border-outline-variant/30 p-2 flex justify-around items-center shrink-0">
                        <div class="flex flex-col items-center text-primary">
                            <span class="material-symbols-outlined text-lg">home</span>
                            <span class="text-[8px] font-bold">Home</span>
                        </div>
                        <div class="flex flex-col items-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">campaign</span>
                            <span class="text-[8px]">Updates</span>
                        </div>
                        <div class="flex flex-col items-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">volunteer_activism</span>
                            <span class="text-[8px]">Empower</span>
                        </div>
                        <div class="flex flex-col items-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">person</span>
                            <span class="text-[8px]">Profile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join the Movement CTA -->
<section class="py-24 bg-surface relative overflow-hidden">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop bg-white shadow-2xl relative z-10 grid grid-cols-1 lg:grid-cols-2 rounded-2xl overflow-hidden border border-outline-variant/30">
        <div class="p-8 md:p-16 space-y-6">
            <h2 class="font-headline-xl text-headline-xl text-primary font-bold">Join the Movement</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                Become a part of the change we want to see. Sign up today to receive campaign updates, volunteer for events, or contribute to our mission.
            </p>
            
            <livewire:join-movement />
        </div>
        
        <div class="hidden lg:block relative">
            <img class="w-full h-full object-cover" alt="Anambra Central community collage" src="{{ asset('images/hero.jpeg') }}"/>
            <div class="absolute inset-0 bg-primary/20 flex items-center justify-center">
                <div class="text-white text-center p-12 bg-primary/80 backdrop-blur-md max-w-md rounded-lg">
                    <h3 class="font-headline-lg text-headline-lg font-bold mb-2">Together, We Can.</h3>
                    <p class="font-body-md opacity-90 leading-relaxed">Your participation matters in the journey of 2027.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/5 -skew-x-12 transform translate-x-20"></div>
</section>
@endsection
