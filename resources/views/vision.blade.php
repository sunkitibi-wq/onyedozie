@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<header class="relative py-24 px-margin-mobile md:px-margin-desktop overflow-hidden bg-primary text-on-primary">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="max-w-container-max mx-auto relative z-10 text-center space-y-6">
        <div class="inline-block px-4 py-1 bg-secondary-container text-on-secondary-container text-label-sm font-label-sm rounded-full">
            A BLUEPRINT FOR PROGRESS
        </div>
        <h1 class="font-headline-xl text-headline-xl max-w-4xl mx-auto">Our Shared Vision for Anambra Central</h1>
        <p class="font-body-lg text-body-lg max-w-2xl mx-auto opacity-90 leading-relaxed">
            A legislative agenda centered on sustainable empowerment, modern infrastructure, and the dignity of every constituent in Anambra Central.
        </p>
    </div>
</header>

<!-- Vision Bento Grid -->
<section class="py-20 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto bg-surface">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
        <!-- Education (Large Card) -->
        <div class="md:col-span-8 bg-white border border-outline-variant p-8 bento-card rounded-xl flex flex-col justify-between shadow-sm">
            <div class="space-y-4">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center rounded-lg">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Quality Education & Digital Literacy</h3>
                <p class="text-on-surface-variant font-body-md max-w-xl leading-relaxed">Transforming our schools into centers of excellence through scholarship programs, teacher training, and integrating digital tools for globally competitive learning.</p>
            </div>
            <div class="flex flex-wrap gap-2 pt-6">
                <span class="px-3 py-1 bg-surface-container text-primary text-label-sm font-label-sm rounded-md">E-Learning Hubs</span>
                <span class="px-3 py-1 bg-surface-container text-primary text-label-sm font-label-sm rounded-md">Scholarship Funds</span>
                <span class="px-3 py-1 bg-surface-container text-primary text-label-sm font-label-sm rounded-md">Vocational Training</span>
            </div>
        </div>

        <!-- Youth Development -->
        <div class="md:col-span-4 bg-white border border-outline-variant p-8 bento-card rounded-xl shadow-sm vibrant-yellow-accent flex flex-col justify-between">
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">rocket_launch</span>
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Youth & Innovation</h3>
                <p class="text-on-surface-variant font-body-md leading-relaxed">Creating ICT innovation hubs and employment pathways that empower the youth to drive the modern economy.</p>
            </div>
            <div class="pt-6">
                <span class="px-3 py-1 bg-secondary-container/20 text-secondary text-label-sm font-label-sm rounded-md">Tech Hubs</span>
            </div>
        </div>

        <!-- Healthcare -->
        <div class="md:col-span-4 bg-white border border-outline-variant p-8 bento-card rounded-xl shadow-sm flex flex-col justify-between">
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">health_and_safety</span>
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Quality Healthcare</h3>
                <p class="text-on-surface-variant font-body-md leading-relaxed">Infrastructure upgrades and preventive health programs focusing on maternal and community health services across all wards.</p>
            </div>
            <div class="pt-6">
                <span class="px-3 py-1 bg-surface-container text-primary text-label-sm font-label-sm rounded-md">Mobile Clinics</span>
            </div>
        </div>

        <!-- Infrastructure (Wide Card) -->
        <div class="md:col-span-8 bg-white border border-outline-variant bento-card rounded-xl overflow-hidden flex flex-col md:flex-row shadow-sm">
            <div class="p-8 md:w-1/2 flex flex-col justify-center space-y-4">
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Basic Infrastructure</h3>
                <p class="text-on-surface-variant font-body-md leading-relaxed">Ensuring consistent access to clean water, reliable solar electricity, and durable road networks connecting our rural communities to urban markets.</p>
            </div>
            <div class="md:w-1/2 bg-cover bg-center h-48 md:h-full" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBEU4cOV-nIc4lFl0pjpaRloKYEhY4M5A0Kb9KKSqmy2NAMYtqZ7PEz_nLRkoflL3HNNMBEtlG5zYi2Z5DN5W52ak1QAdRoXZ-iqVoxjiEjkpnniPK_Vc1UjlPeRNBJ4mRzJHuVqm4Q-ApckuAAFfab9WsWtTC2hGFJFIUq3hxKUQC34CMRU4K4Uo89dAgt8H67J2_oFl27dT6LvDmI075nQWVRjK-kD-gYFWfUQpS58U6_G8Br3e4BB3X39LjdAa4s18UYV1bgxjdn')"></div>
        </div>

        <!-- Women's Empowerment -->
        <div class="md:col-span-4 bg-primary text-on-primary p-8 bento-card rounded-xl shadow-md flex flex-col justify-between">
            <div class="space-y-4">
                <span class="material-symbols-outlined text-secondary-container text-4xl" style="font-variation-settings: 'FILL' 1;">diversity_3</span>
                <h3 class="font-headline-md text-headline-md font-bold">Women in Leadership</h3>
                <p class="opacity-90 font-body-md leading-relaxed">Economic opportunities and financial inclusion for women-led SMEs through specialized micro-credit schemes and skills acquisition programs.</p>
            </div>
            <div class="pt-6">
                <span class="px-3 py-1 bg-white/20 text-white text-label-sm font-label-sm rounded-md">Micro-Grants</span>
            </div>
        </div>

        <!-- Agriculture -->
        <div class="md:col-span-4 bg-white border border-outline-variant p-8 bento-card rounded-xl shadow-sm flex flex-col justify-between">
            <div class="space-y-4">
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">agriculture</span>
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Modern Agriculture</h3>
                <p class="text-on-surface-variant font-body-md leading-relaxed">Empowering farmers with modern tools, solar irrigation, sustainable financing, and value-chain development for regional food security.</p>
            </div>
            <div class="pt-6">
                <span class="px-3 py-1 bg-surface-container text-primary text-label-sm font-label-sm rounded-md">Solar Irrigation</span>
            </div>
        </div>

        <!-- Economic Growth -->
        <div class="md:col-span-4 bg-secondary text-on-secondary p-8 bento-card rounded-xl shadow-md flex flex-col justify-between">
            <div class="space-y-4">
                <span class="material-symbols-outlined text-on-secondary text-4xl" style="font-variation-settings: 'FILL' 1;">currency_exchange</span>
                <h3 class="font-headline-md text-headline-md font-bold">Economic Growth</h3>
                <p class="font-body-md leading-relaxed">Strengthening SMEs and attracting investments to create sustainable jobs, foster technology adoption, and promote localized economic systems.</p>
            </div>
            <div class="pt-6">
                <span class="px-3 py-1 bg-white/20 text-white text-label-sm font-label-sm rounded-md">SME Incubators</span>
            </div>
        </div>
    </div>
</section>

<!-- Why Anambra Needs Onyendozi Section -->
<section class="py-24 bg-surface-container-low border-y border-outline-variant/50">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            <div class="lg:w-1/2 space-y-8">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-secondary-container/20 -z-10 rounded-sm"></div>
                    <h2 class="font-headline-lg text-headline-lg leading-tight">Why Anambra Central Needs <span class="text-primary">Onyendozi</span></h2>
                </div>
                <p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed">Hon. Ferdinand Dozie Nwankwo (Onyendozi) brings a unique blend of legislative precision, private sector management expertise, and deep-rooted grassroots understanding.</p>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary text-on-primary flex items-center justify-center rounded-md">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md text-on-surface font-bold">Legislative Excellence</h4>
                            <p class="text-on-surface-variant font-body-md leading-relaxed">Proven track record in sponsoring bills that directly impact the welfare of citizens.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary text-on-primary flex items-center justify-center rounded-md">
                            <span class="material-symbols-outlined text-[20px]">groups</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md text-on-surface font-bold">Grassroots Connection</h4>
                            <p class="text-on-surface-variant font-body-md leading-relaxed">A leader who walks with the people and understands the pulse of the community.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary text-on-primary flex items-center justify-center rounded-md">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md text-on-surface font-bold">Strategic Vision</h4>
                            <p class="text-on-surface-variant font-body-md leading-relaxed">Forward-thinking policies tailored to the 21st-century digital economy and infrastructure development.</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="lg:w-1/2 w-full">
                <div class="grid grid-cols-2 gap-4">
                    <div class="h-80 bg-cover bg-center rounded-xl shadow-md border-4 border-white" style="background-image: url('{{ asset('images/dozie_vision.jpg') }}')"></div>
                    <div class="flex flex-col gap-4">
                        <div class="h-36 bg-primary rounded-xl flex items-center justify-center p-6 text-center shadow-md">
                            <p class="text-on-primary font-headline-md text-headline-md font-bold">10+ Years Experience</p>
                        </div>
                        <div class="h-40 bg-secondary-container rounded-xl flex flex-col justify-center p-6 shadow-md border-b-4 border-secondary">
                            <p class="text-on-secondary-container font-headline-md text-headline-md font-bold leading-tight">Countless Lives Touched</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 text-center px-margin-mobile bg-surface">
    <div class="max-w-2xl mx-auto space-y-6">
        <h2 class="font-headline-lg text-headline-lg font-bold text-primary">Be a Part of the Vision</h2>
        <p class="text-body-lg font-body-lg text-on-surface-variant leading-relaxed">Your support drives the progress of Anambra Central. Join us today as a volunteer or supporter.</p>
        <div class="flex flex-wrap justify-center gap-4 pt-4">
            <a href="{{ route('join') }}" class="px-10 py-4 bg-primary text-on-primary font-label-md hover:bg-primary-container transition-all rounded-xl shadow-lg">Become a Member</a>
            <a href="{{ route('join') }}" class="px-10 py-4 border-2 border-primary text-primary font-label-md hover:bg-surface-container-high transition-all rounded-xl">View Campaign Roadmap</a>
        </div>
    </div>
</section>
@endsection
