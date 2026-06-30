@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 overflow-hidden pattern-bg">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center relative z-10 space-y-6">
        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-label-md rounded-full border border-primary/20">Be Informed!</span>
        <h1 class="text-headline-xl font-headline-xl text-on-surface">Dividends of Onye Ndozi's <br class="hidden md:block"/> <span class="text-primary font-bold">Journey in the Green Chamber</span></h1>
        <p class="text-body-lg font-body-lg text-on-surface-variant max-w-3xl mx-auto leading-relaxed">
            Hon. Ferdinand Dozie Nwankwo (Onyendozi) delivered responsive legislative advocacy, human capital empowerment, and robust constituency projects across Dunukofia, Njikoka, and Anaocha.
        </p>
    </div>
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[100px] -z-0"></div>
</section>

<!-- Stats Grid -->
<section class="py-12 bg-surface border-y border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">gavel</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">6</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Bills Sponsored</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">campaign</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">6</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Motions Moved</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">construction</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">22</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Zonal Projects</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">medical_services</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">4</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Health Init.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all col-span-2 lg:col-span-1">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">groups</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">4</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Empowerment Sects</p>
            </div>
        </div>
    </div>
</section>

<!-- Content Sections: Bills, Motions & Interventions -->
<section class="py-20 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop space-y-16">
        
        <!-- Tabbed Container / Spaced Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            
            <!-- Left Column: Bills & Motions -->
            <div class="lg:col-span-6 space-y-12">
                <!-- Sponsored Bills -->
                <div class="bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">menu_book</span>
                        <h2 class="text-headline-md font-headline-md font-bold text-primary">Sponsored Bills</h2>
                    </div>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">01</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">Federal College of Agriculture (Technical) Enugwu-Ukwu</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Act to establish courses, instructions, and training in agricultural technology and its due administration (2021).</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">02</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">Public Service Institute of Nigeria Establishment</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Bill for an Act to establish the Public Service Institute and for other related matters (2020).</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">03</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">Nigeria Investment Promotion Commission Act Amendment</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Bill for an Act to amend Cap. N117, Laws of the Federation of Nigeria (2020).</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">04</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">National Boundary Commission (Establishment) Amendment</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Bill for an Act to amend the Boundary Commission Act (2020).</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">05</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">Civil Forfeiture and Proceeds of Crimes Bill</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Bill for an Act to manage the proceeds of crimes (2020).</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="text-label-md text-secondary font-bold shrink-0">06</span>
                            <div>
                                <h4 class="font-headline-sm font-bold text-on-surface">Criminal Forfeiture and Management of Crimes Bill</h4>
                                <p class="text-body-sm text-on-surface-variant mt-1">Bill for an Act to manage criminal assets (2020).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motions Moved -->
                <div class="bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">record_voice_over</span>
                        <h2 class="text-headline-md font-headline-md font-bold text-primary">Motions Moved</h2>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Urgently address the ravaging gully erosion in parts of Anaocha/Njikoka/Dunukofia Federal Constituency, Anambra State.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Investigate the alleged use of Paracetamol and Bleach to tenderize meat and process cassava across the Federation.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Isolate LPG/Cooking Gas refilling locations from fuel stations and other unauthorized locations.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Encourage basic medical health checkups by Nigerians.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Urgent National Importance: Evolve a harmonized database for economic planning in Nigeria.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">campaign</span>
                            <span class="text-body-md text-on-surface-variant">Urgent National Importance: Monitor the distribution of post-Covid palliatives to cushion the effects on citizens.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Zonal Projects, Health, & Empowerment -->
            <div class="lg:col-span-6 space-y-12">
                <!-- Zonal Intervention Projects -->
                <div class="bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">home_work</span>
                        <h2 class="text-headline-md font-headline-md font-bold text-primary">Zonal Intervention Projects</h2>
                    </div>
                    <div class="max-h-[600px] overflow-y-auto pr-2 space-y-4 text-body-md text-on-surface-variant scrollbar-thin">
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">01. Nneameka Community Sec. School Hall</strong>
                            <p class="text-sm mt-0.5">Completion and furnishing of school hall in Ifitedunu, Dunukofia LGA (Attracted).</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">02. Obiechi Primary School</strong>
                            <p class="text-sm mt-0.5">Renovation of 3 Classroom Blocks in Umunnachi, Dunukofia LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">03. Community Secondary School Nimo (Girls)</strong>
                            <p class="text-sm mt-0.5">Renovation of Classroom Blocks in Nimo, Njikoka LGA (Attracted).</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">04. Community Secondary School Obeledu</strong>
                            <p class="text-sm mt-0.5">Construction of Hostel Blocks in Obeledu, Anaocha LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">05. Girls Secondary School Adazi Nnukwu</strong>
                            <p class="text-sm mt-0.5">Renovation of Hostel Blocks in Adazi Nnukwu, Anaocha LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">06. Aguluzigbo Clean Water Station</strong>
                            <p class="text-sm mt-0.5">Drilling of Solar Powered Borehole, Construction of Surface and Overhead Tanks, and Reticulation in Aguluzigbo, Anaocha LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">07. NYSC Corpers Accommodation Ichida</strong>
                            <p class="text-sm mt-0.5">Construction of modern corpers accommodation in Ichida, Anaocha LGA (Ongoing).</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">08. Nawfia Street Lighting Project</strong>
                            <p class="text-sm mt-0.5">Installation of streetlights in Nawfia from opposite St. Michaels Anglican Church to Uruoji, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">09. Solar Powered Borehole Adaku-Enu</strong>
                            <p class="text-sm mt-0.5">Drilling of solar water supply center in Adaku-Enu.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">10. Skills Acquisition Centre Adazi Ani</strong>
                            <p class="text-sm mt-0.5">Solar Powered Borehole near Umuru Hall in Adazi Ani, Anaocha LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">11. Principal Quarters Nawgu</strong>
                            <p class="text-sm mt-0.5">Construction of Principal Quarters in Nawgu, Dunukofia LGA (Ongoing).</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">12. Ifite Civic Center Furnishing</strong>
                            <p class="text-sm mt-0.5">Provided Air Conditioners, furniture sets, and a 60KVA backup generator for Ifite Village Civic Center in Aguluzigbo, Anaocha LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">13. Enuagu Hall Enugwu-Ukwu</strong>
                            <p class="text-sm mt-0.5">Construction and furnishing of Enuagu Hall in Enugwu-Ukwu, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">14. Arinze Primary School</strong>
                            <p class="text-sm mt-0.5">Construction and furnishing of classroom block with toilet building in Enugwu-Ukwu, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">15. Ide Secondary School</strong>
                            <p class="text-sm mt-0.5">Construction and furnishing of classroom blocks in Enugwu-Ukwu, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">16. Urunnebo Community Primary School</strong>
                            <p class="text-sm mt-0.5">Construction, classroom furnishing, and supply of computer sets in Enugwu-Ukwu, Njikoka LGA (Ongoing).</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">17. Awovu Village Street Lighting</strong>
                            <p class="text-sm mt-0.5">Installation of extensive solar streetlights in Awovu village, Enugwu-Ukwu, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">18. Nnamdi Azikiwe Secondary School Abagana</strong>
                            <p class="text-sm mt-0.5">Renovation of the main school hall in Abagana, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">19. Center for Development & ICT Enugwu-Ukwu</strong>
                            <p class="text-sm mt-0.5">Construction of state-of-the-art ICT Development Center in Enugwu-Ukwu, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">20. Federal College of Agriculture & Technical (Campus)</strong>
                            <p class="text-sm mt-0.5">Establishment of Ishiagu-Enugwu-Ukwu Campus, Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">21. Njikoka Road Projects</strong>
                            <p class="text-sm mt-0.5">Construction of primary accessibility roads in selected towns of Njikoka LGA.</p>
                        </div>
                        <div class="p-3 bg-surface rounded-lg border border-outline-variant/20 hover:border-primary/30 transition-colors">
                            <strong class="text-primary">22. Nimo and Enugwu-Agidi Classroom Blocks</strong>
                            <p class="text-sm mt-0.5">Construction of two modern 6-classroom blocks in Nimo and Enugwu-Agidi respectively.</p>
                        </div>
                    </div>
                </div>

                <!-- Healthcare -->
                <div class="bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">healing</span>
                        <h2 class="text-headline-md font-headline-md font-bold text-primary">Healthcare</h2>
                    </div>
                    <ul class="space-y-4 text-body-md text-on-surface-variant">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">verified</span>
                            <span>Construction of the Neni Primary Health Center at Neni, Anaocha LGA.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">verified</span>
                            <span>Construction of 1 Block of 2-bedroom flats, semidetached Doctors' Quarters at Enugu-Agidi Primary Health Center, Njikoka LGA (Ongoing).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">verified</span>
                            <span>Construction of Primary Health Care facilities at Ukwulu, Dunukofia LGA (Ongoing).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">verified</span>
                            <span>Distribution of comprehensive Covid-19 Relief Materials in Anaocha, Njikoka, and Dunukofia local governments.</span>
                        </li>
                    </ul>
                </div>

                <!-- Empowerments -->
                <div class="bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant/30 pb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">stars</span>
                        <h2 class="text-headline-md font-headline-md font-bold text-primary">Empowerments</h2>
                    </div>
                    <ul class="space-y-4 text-body-md text-on-surface-variant">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">payments</span>
                            <span>Skill acquisition and vocational trainings for selected women and youth in Anambra State.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">payments</span>
                            <span>Cash grants disbursed directly to more than 270 women from Anaocha/Njikoka/Dunukofia Federal Constituency.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">payments</span>
                            <span>Facilitated federal employment placements for 80 constituents in MDAs.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary font-bold shrink-0 mt-0.5">payments</span>
                            <span>Continuous secondary and tertiary academic scholarships for underprivileged youths.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Photo Gallery Section -->
<section class="py-20 bg-surface-container-low border-t border-outline-variant/30" x-data="{ lightbox: false, activeSrc: '' }">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-4">
            <div class="accent-bar mx-auto"></div>
            <h2 class="text-headline-lg font-headline-lg text-primary font-bold">Project Gallery</h2>
            <p class="text-body-md text-on-surface-variant">Browse through physical project sites, equipment distributions, health checkups, and empowerment assemblies.</p>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $galleryImages = [
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.43 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.43 (2).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.43.jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (2).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (3).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.44.jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (2).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (3).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.45.jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.46 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.46 (2).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.46 (3).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.46.jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.47 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.47 (2).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.47 (3).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.47.jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.48 (1).jpeg'),
                    asset('images/gallery/WhatsApp Image 2026-06-29 at 10.55.48.jpeg'),
                    
                    // Henry Gallery Images
                    asset('images/henry/1.jpeg'),
                    asset('images/henry/2.jpeg'),
                    asset('images/henry/3.jpeg'),
                    asset('images/henry/4.jpeg'),
                    asset('images/henry/5.jpeg'),
                    asset('images/henry/6.jpeg'),
                    asset('images/henry/7.jpeg'),
                    asset('images/henry/8.jpeg'),
                    asset('images/henry/9.jpeg'),
                    asset('images/henry/10.jpeg'),
                    asset('images/henry/11.jpeg'),
                    asset('images/henry/12.jpeg'),
                    asset('images/henry/13.jpeg'),
                    asset('images/henry/14.jpeg'),
                    asset('images/henry/15.jpeg'),
                    asset('images/henry/16.jpeg'),
                    asset('images/henry/17.jpeg'),
                    asset('images/henry/18.jpeg'),
                ];
            @endphp

            @foreach ($galleryImages as $image)
                <div class="relative aspect-square overflow-hidden rounded-xl group border border-outline-variant/30 bg-white cursor-pointer shadow-xs hover:shadow-md hover:border-primary transition-all"
                     @click="activeSrc = '{{ $image }}'; lightbox = true">
                    <img src="{{ $image }}" 
                         alt="Onyendozi constituency project image" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-3xl">zoom_in</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4"
         x-show="lightbox"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.self="lightbox = false"
         style="display: none;"
         @keydown.escape.window="lightbox = false">
        
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center">
            <!-- Close Button -->
            <button class="absolute -top-12 right-0 text-white hover:text-secondary-container transition-colors flex items-center gap-1 cursor-pointer"
                    @click="lightbox = false">
                <span class="material-symbols-outlined text-3xl">close</span>
                <span class="font-label-md hidden sm:inline">Close</span>
            </button>
            
            <!-- Image inside Lightbox -->
            <img :src="activeSrc" alt="Full screen preview" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-2xl">
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-16 px-margin-mobile md:px-margin-desktop bg-surface">
    <div class="max-w-container-max mx-auto bg-primary rounded-2xl p-8 md:p-12 text-center relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 space-y-6">
            <h2 class="text-headline-lg font-headline-lg text-on-primary">Support A Vision That Delivers</h2>
            <p class="text-body-lg text-on-primary/80 max-w-2xl mx-auto leading-relaxed">Join Hon. Dozie Nwankwo in establishing sustainable leadership and responsive representation for Anambra Central Senatorial District.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                <a href="{{ route('join') }}" class="bg-secondary-container text-on-secondary-container px-8 py-4 rounded-xl font-label-md hover:scale-105 transition-transform shadow-lg">Join the Movement</a>
                <a href="{{ route('about') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-label-md hover:bg-white hover:text-primary transition-all">Read Dozie's Story</a>
            </div>
        </div>
    </div>
</section>
@endsection
