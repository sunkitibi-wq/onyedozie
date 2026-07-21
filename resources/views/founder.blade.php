@extends('layouts.frontend')

@section('content')
<!-- Hero Section: Biography Intro -->
<section class="relative py-20 overflow-hidden pattern-bg">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
        <div class="lg:col-span-7 z-10 space-y-6">
            <span class="text-primary font-bold tracking-widest text-label-md uppercase">Meet the President & Founder</span>
            <h1 class="text-headline-xl font-headline-xl text-on-surface">Dr. Henry <span class="text-primary font-bold">Obiozor</span></h1>
            <p class="text-secondary font-headline-md font-bold text-headline-md mt-1">President & Founder, Onyendozi Connect</p>
            <p class="text-body-lg font-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                Dr. Henry Obiozor is the visionary founder and President of Onyendozi Connect, a digital grassroots engagement platform established to bridge the gap between the people of Anambra Central Senatorial District and responsive leadership. Guided by the philosophy of service, innovation, and inclusive participation, he founded Onyendozi Connect to create a technology-driven platform that empowers citizens, promotes civic engagement, and connects communities to opportunities for growth and development.
            </p>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">terminal</span>
                    <span class="font-label-md text-label-md">ICT Professional</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">forum</span>
                    <span class="font-label-md text-label-md">Political Strategist</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">business_center</span>
                    <span class="font-label-md text-label-md">Entrepreneur</span>
                </div>
            </div>
        </div>
        <div class="lg:col-span-5 relative mt-12 lg:mt-0">
            <div class="aspect-[4/5] rounded-xl overflow-hidden shadow-xl border-4 border-white max-w-[400px] mx-auto">
                <img class="w-full h-full object-cover" alt="Dr. Henry Obiozor portrait" src="{{ asset('images/henry.jpeg') }}"/>
            </div>
            <div class="absolute -bottom-6 left-4 right-4 sm:left-auto sm:right-auto sm:-left-6 bg-secondary-container p-6 rounded-lg shadow-lg max-w-[280px] sm:max-w-[240px]">
                <span class="material-symbols-outlined text-on-secondary-container mb-2" style="font-variation-settings: 'FILL' 1;">format_quote</span>
                <p class="text-on-secondary-container font-headline-md italic leading-tight">
                    "When people succeed, communities prosper."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Career Profile & Tech Background -->
<section class="py-20 bg-surface-container-lowest">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="accent-bar"></div>
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">Two Decades of Leadership</h2>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    An accomplished ICT professional, political strategist, media consultant, and entrepreneur, Dr. Obiozor has over two decades of experience in information technology, digital communication, public engagement, and organizational leadership. He has successfully led several digital transformation initiatives, political communication campaigns, and community development projects across Nigeria.
                </p>
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    As the Managing Director and Chief Executive Officer of Henmark Information Technology Ltd, he has championed innovative technology solutions in web application development, mobile applications, ICT consultancy, software development, and digital media. His passion for leveraging technology to improve governance and citizen participation inspired the creation of Onyendozi Connect.
                </p>
            </div>
            <!-- Technical achievements cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                    <span class="material-symbols-outlined text-primary text-3xl">developer_mode</span>
                    <h3 class="font-headline-sm font-bold">Web & Mobile Apps</h3>
                    <p class="text-body-sm text-on-surface-variant">Deploying robust, user-centric systems that bridge communication divides.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                    <span class="material-symbols-outlined text-primary text-3xl">hub</span>
                    <h3 class="font-headline-sm font-bold">Digital Campaigns</h3>
                    <p class="text-body-sm text-on-surface-variant">Spearheading digital mobilization strategies with verifiable community reach.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                    <span class="material-symbols-outlined text-primary text-3xl">support_agent</span>
                    <h3 class="font-headline-sm font-bold">ICT Consultancy</h3>
                    <p class="text-body-sm text-on-surface-variant">Providing technical consultation services for digital reform initiatives.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                    <span class="material-symbols-outlined text-primary text-3xl">group_work</span>
                    <h3 class="font-headline-sm font-bold">Community Mobilization</h3>
                    <p class="text-body-sm text-on-surface-variant">Developing digital architectures to coordinate large-scale public networks.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- The Vision Behind Onyendozi Connect -->
<section class="py-20 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-4">
            <div class="accent-bar mx-auto"></div>
            <h2 class="text-headline-lg font-headline-lg text-primary font-bold">The Vision Behind Onyendozi Connect</h2>
            <p class="text-body-md text-on-surface-variant">Dr. Obiozor believes that effective representation goes beyond elections—it requires continuous engagement between leaders and the people.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">contact_mail</span>
                <h3 class="font-headline-sm font-bold text-primary">Connect Citizens & Leaders</h3>
                <p class="text-body-sm text-on-surface-variant">Serve as a digital bridge between the constituents of Anambra Central and their elected representatives.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">groups_3</span>
                <h3 class="font-headline-sm font-bold text-primary">Grassroots Participation</h3>
                <p class="text-body-sm text-on-surface-variant">Promote inclusive citizen participation and input in governance policies and local initiatives.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">card_membership</span>
                <h3 class="font-headline-sm font-bold text-primary">Access to Empowerment</h3>
                <p class="text-body-sm text-on-surface-variant">Provide direct channels to access educational, economic, and skills development programmes.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">policy</span>
                <h3 class="font-headline-sm font-bold text-primary">Transparency & Accountability</h3>
                <p class="text-body-sm text-on-surface-variant">Encourage open administration, transparent feedback loops, and accountable representation.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">volunteer_activism</span>
                <h3 class="font-headline-sm font-bold text-primary">Mobilize Local Advocates</h3>
                <p class="text-body-sm text-on-surface-variant">Build and coordinate volunteer networks across the 7 LGAs of Anambra Central Senatorial District.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-outline-variant/30 shadow-xs space-y-3">
                <span class="material-symbols-outlined text-secondary text-3xl">input</span>
                <h3 class="font-headline-sm font-bold text-primary">Connect to Federal Projects</h3>
                <p class="text-body-sm text-on-surface-variant">Connect Anambra Central citizens directly to Federal Government developmental projects, opportunities, and programmes.</p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Philosophy & Movement -->
<section class="py-20 bg-surface-container-high/50 border-t border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Driving the movement -->
        <div class="lg:col-span-6 space-y-6">
            <h2 class="text-headline-lg font-headline-lg text-primary font-bold">Driving the Onyendozi Movement</h2>
            <p class="text-body-md text-on-surface-variant leading-relaxed">
                As President of Onyendozi Connect, Dr. Obiozor provides strategic leadership for the platform's programmes, digital infrastructure, volunteer network, media engagement, and community mobilization efforts. Under his leadership, the platform continues to grow as a trusted hub for information sharing, volunteer coordination, civic education, policy engagement, and grassroots mobilization.
            </p>
            <p class="text-body-md text-on-surface-variant leading-relaxed">
                Working alongside supporters, community leaders, professionals, youths, women, and stakeholders, he is committed to advancing the vision of Hon. Ferdinand Dozie Nwankwo (Onyendozi) by fostering stronger citizen participation and promoting people-centred development throughout Anambra Central.
            </p>
        </div>

        <!-- Five Core Principles -->
        <div class="lg:col-span-6 bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs space-y-6">
            <h3 class="font-headline-md font-bold text-primary border-b border-outline-variant/30 pb-4">Leadership Philosophy</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">verified</span>
                    <span class="text-body-md font-bold text-on-surface">Service Before Self</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">verified</span>
                    <span class="text-body-md font-bold text-on-surface">Innovation Through Technology</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">verified</span>
                    <span class="text-body-md font-bold text-on-surface">Integrity and Accountability</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">verified</span>
                    <span class="text-body-md font-bold text-on-surface">Grassroots Inclusion</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary">verified</span>
                    <span class="text-body-md font-bold text-on-surface">Sustainable Community Development</span>
                </div>
            </div>
            <p class="text-body-sm text-on-surface-variant italic pt-2">
                "Leadership should inspire hope, create opportunities, and deliver measurable impact to the people."
            </p>
        </div>
    </div>
</section>

<!-- Founder's message quote -->
<section class="py-20 px-margin-mobile md:px-margin-desktop bg-surface">
    <div class="max-w-container-max mx-auto bg-primary rounded-2xl p-8 md:p-12 text-center relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 space-y-6 max-w-4xl mx-auto">
            <span class="material-symbols-outlined text-secondary-container text-5xl" style="font-variation-settings: 'FILL' 1;">format_quote</span>
            <h3 class="text-headline-md font-headline-md text-on-primary italic leading-relaxed">
                "Onyendozi Connect is more than a political platform; it is a movement dedicated to connecting people with opportunities, empowering communities through technology, and ensuring that every citizen of Anambra Central has a voice in shaping our collective future. Together, we will connect Anambra Central to the centre of national development and build a stronger, more prosperous society for generations to come."
            </h3>
            <div class="pt-4 space-y-1">
                <p class="text-headline-sm font-bold text-secondary-container">Dr. Henry Obiozor, PhD</p>
                <p class="text-label-sm text-white/80">President & Founder, Onyendozi Connect</p>
            </div>
        </div>
    </div>
</section>
@endsection
