@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<section class="relative pt-20 pb-16 px-margin-mobile md:px-margin-desktop overflow-hidden bg-surface pattern-bg">
    <div class="max-w-container-max mx-auto text-center relative z-10 space-y-6">
        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-label-md rounded-full border border-primary/20">Onyendozi Connect</span>
        <h1 class="font-headline-xl text-headline-xl text-on-surface">Onyendozi Connect is a movement <br class="hidden md:block"/> <span class="text-primary font-bold">powered by the people.</span></h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto leading-relaxed">Join thousands of citizens across the state who are committed to a future of prosperity, effective representation, and accountable leadership. Your voice is the catalyst for change.</p>
        <div class="flex flex-wrap justify-center gap-4 pt-4">
            <a class="bg-primary text-on-primary px-8 py-4 font-label-md rounded-xl flex items-center gap-2 hover:shadow-lg transition-all group" href="#form">
                Sign Up Today
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
    </div>
    <!-- Abstract background shape -->
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[100px] -z-0"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[500px] h-[500px] bg-secondary-container/10 rounded-full blur-[100px] -z-0"></div>
</section>

<!-- Main Content Area: Bento Grid Layout -->
<section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop pb-24 bg-surface" id="form">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
        <!-- Registration Form Column -->
        <div class="lg:col-span-7 bg-white p-8 md:p-12 shadow-sm border border-outline-variant/30 rounded-xl space-y-8">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-primary font-bold">Supporter Registration</h2>
                <p class="text-on-surface-variant font-body-md mt-2">Fill out the details below to join the movement and stay updated.</p>
            </div>
            
            <form class="space-y-6" onsubmit="event.preventDefault(); alert('Supporter registration successful! Welcome to the movement.');">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-label-md text-on-surface font-bold" for="fname">First Name</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="fname" placeholder="John" type="text" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-label-md text-on-surface font-bold" for="lname">Last Name</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="lname" placeholder="Doe" type="text" required/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-label-md text-on-surface font-bold" for="email">Email Address</label>
                    <input class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="email" placeholder="john@example.com" type="email" required/>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-label-md text-on-surface font-bold" for="phone">Phone Number</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="phone" placeholder="+234 000 000 0000" type="tel" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-label-md text-on-surface font-bold" for="location">Location (Ward/LGA)</label>
                        <input class="w-full px-4 py-3 bg-surface border border-outline focus:ring-2 focus:ring-primary/20 focus:border-primary outline-hidden transition-all rounded-lg font-body-md" id="location" placeholder="Anaocha Ward 1" type="text" required/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-label-md text-on-surface font-bold">Area of Interest</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
                            <input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant rounded-sm" type="checkbox"/>
                            <span class="text-body-md">Community Outreach</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
                            <input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant rounded-sm" type="checkbox"/>
                            <span class="text-body-md">Media & Publicity</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
                            <input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant rounded-sm" type="checkbox"/>
                            <span class="text-body-md">Logistics</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container transition-colors">
                            <input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant rounded-sm" type="checkbox"/>
                            <span class="text-body-md">Strategy</span>
                        </label>
                    </div>
                </div>
                
                <button class="w-full bg-primary text-on-primary py-4 font-headline-md text-headline-md rounded-lg hover:shadow-lg transition-all active:scale-[0.98] cursor-pointer mt-6 font-bold" type="submit">
                    Join the Movement
                </button>
                <p class="text-center text-label-sm text-on-surface-variant leading-relaxed">By joining, you agree to receive campaign updates and communications. You can opt-out at any time.</p>
            </form>
        </div>
        
        <!-- Ways to Participate Column -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-primary text-on-primary p-8 md:p-10 shadow-sm rounded-xl space-y-6">
                <h3 class="font-headline-md text-headline-md mb-6 flex items-center gap-3 font-bold border-b border-white/20 pb-4">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    Ways to Participate
                </h3>
                <ul class="space-y-6">
                    <li class="flex items-start gap-4 p-4 bg-white/10 border border-white/20 rounded-xl hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary-container rounded-lg shrink-0">
                            <span class="material-symbols-outlined">person_add</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md mb-1 font-bold">Register as a Supporter</h4>
                            <p class="text-on-primary/80 text-body-md">Be recognized as a formal member of the Onyendozi network.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 p-4 bg-white/10 border border-white/20 rounded-xl hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary-container rounded-lg shrink-0">
                            <span class="material-symbols-outlined">volunteer_activism</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md mb-1 font-bold">Volunteer</h4>
                            <p class="text-on-primary/80 text-body-md">Dedicate your time and skills to campaign operations and field activities.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 p-4 bg-white/10 border border-white/20 rounded-xl hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary-container rounded-lg shrink-0">
                            <span class="material-symbols-outlined">groups</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md mb-1 font-bold">Join Ward Coordinators</h4>
                            <p class="text-on-primary/80 text-body-md">Lead at the grassroots level and mobilize your local community.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 p-4 bg-white/10 border border-white/20 rounded-xl hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 flex items-center justify-center bg-secondary-container text-on-secondary-container rounded-lg shrink-0">
                            <span class="material-symbols-outlined">forum</span>
                        </div>
                        <div>
                            <h4 class="font-label-md text-label-md mb-1 font-bold">Town Hall Meetings</h4>
                            <p class="text-on-primary/80 text-body-md">Participate in dialogue and share your concerns directly with the leadership.</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Side Card - Community Stats -->
            <div class="bg-white border border-outline-variant/30 p-8 shadow-sm rounded-xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full"></div>
                <h4 class="font-headline-md text-headline-md text-primary mb-6 font-bold">Our Presence</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-surface rounded-xl border border-outline-variant/20">
                        <p class="text-headline-md font-headline-md text-primary font-bold">7</p>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Local Govts</p>
                    </div>
                    <div class="text-center p-4 bg-surface rounded-xl border border-outline-variant/20">
                        <p class="text-headline-md font-headline-md text-primary font-bold">100+</p>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Wards Active</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Campaign Values Section -->
<section class="bg-surface-container-high py-20 border-t border-outline-variant/50">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12 space-y-4">
            <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold">Our Campaign Values</h2>
            <div class="w-24 h-1 bg-secondary-container mx-auto"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">verified</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Integrity</span>
            </div>
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">volunteer_activism</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Service</span>
            </div>
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">join_inner</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Unity</span>
            </div>
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">visibility</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Transparency</span>
            </div>
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">diversity_3</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Inclusiveness</span>
            </div>
            <div class="bg-white p-6 border-t-4 border-primary shadow-sm hover:shadow-md transition-all rounded-xl flex items-center gap-4 group">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">trending_up</span>
                <span class="font-headline-md text-headline-md text-on-surface font-semibold">Development</span>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="bg-primary py-20 overflow-hidden relative">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center relative z-10 space-y-6">
        <h2 class="text-white font-headline-xl text-headline-xl font-bold leading-tight">
            Experience You Can Trust. <br class="hidden md:block"/> 
            Leadership You Can Reach. <br class="hidden md:block"/> 
            Representation That Delivers.
        </h2>
        <div class="flex flex-wrap justify-center gap-6 pt-4">
            <a href="#form" class="bg-secondary-container text-on-secondary-container px-10 py-4 font-headline-md rounded-xl hover:bg-secondary transition-colors font-bold shadow-lg">Start Your Journey</a>
        </div>
    </div>
</section>
@endsection
