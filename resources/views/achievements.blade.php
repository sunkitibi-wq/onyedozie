@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 overflow-hidden pattern-bg">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop text-center relative z-10 space-y-6">
        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary font-label-md rounded-full border border-primary/20">Legislative Record</span>
        <h1 class="text-headline-xl font-headline-xl text-on-surface">Achievements in the <br class="hidden md:block"/> <span class="text-primary font-bold">House of Representatives</span></h1>
        <p class="text-body-lg font-body-lg text-on-surface-variant max-w-3xl mx-auto leading-relaxed">
            During his tenure representing Dunukofia, Njikoka, and Anaocha Federal Constituency, Hon. Ferdinand Dozie Nwankwo (Onyendozi) established a benchmark for public office through physical infrastructure development, high-impact bill sponsorship, and historic human capital empowerment.
        </p>
    </div>
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[100px] -z-0"></div>
</section>

<!-- Stats Showcase -->
<section class="py-12 bg-surface border-y border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">account_balance</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">15+</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Bills Sponsored</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">add_road</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">25+</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Roads Restored</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">water_drop</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">42</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Clean Water Projects</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-xs border border-outline-variant/30 hover:border-primary transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-2">volunteer_activism</span>
                <p class="text-headline-xl font-headline-xl text-primary font-bold">50k+</p>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Medical Beneficiaries</p>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Achievements Section: Bento Grid layout -->
<section class="py-20 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop space-y-12">
        <div class="max-w-2xl space-y-4">
            <div class="accent-bar"></div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface">Delivering Tangible Development</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">Explore the key developmental categories initiated and executed to elevate the livelihood of the Anambra Central constituents.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
            <!-- Legislative Bills -->
            <div class="md:col-span-8 bento-card bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
                <div class="space-y-4">
                    <div class="inline-flex p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined text-3xl">gavel</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-primary">Legislative Bills & Advocacy</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed">
                        Sponsored over 15 major bills and motions designed to address structural challenges. Key initiatives include the National Electrification Act amendments, establishment of ICT Centers in federal constituencies, and bills promoting accessibility to credit for rural agro-businesses.
                    </p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-body-sm text-on-surface-variant pt-2">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary font-bold text-lg">check</span>
                            Rural Electrification Expansion Bill
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary font-bold text-lg">check</span>
                            ICT Constituency Resource Bill
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary font-bold text-lg">check</span>
                            Agro-Business Loan Guarantee Act
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary font-bold text-lg">check</span>
                            Youth Vocational Center Funding Motion
                        </li>
                    </ul>
                </div>
                <div class="mt-8 pt-4 border-t border-outline-variant/30 text-label-md text-primary font-bold flex items-center gap-2">
                    Active Legislative Oversight <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </div>
            </div>

            <!-- Health Mission -->
            <div class="md:col-span-4 bento-card bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs flex flex-col hover:shadow-md transition-all">
                <div class="space-y-4 mb-6">
                    <div class="inline-flex p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined text-3xl">medical_services</span>
                    </div>
                    <h3 class="text-headline-sm font-headline-sm font-bold text-primary">Dozie Nwankwo Medical Mission</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">
                        Initiated the largest private-led free healthcare mission in the constituency, bringing standard medical facilities directly to local residents.
                    </p>
                </div>
                <div class="mt-auto aspect-video rounded-lg overflow-hidden border border-outline-variant/30">
                    <img class="w-full h-full object-cover" alt="Medical Mission outreach" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAU4gy1FH6XErFPdgUo6gz3jXlQoo3bghwKlMCuRWtCeHj2E5h4mLxmbi7GfACs8wk5xVmbT3IGDQgBilholFRBWtxssnwqgAjee4bYy0LJeVhgLFbMdojGcaZZn3vZdHrDNVAeHxeqhYsBkq3IbLSrHF7pYw7DXUIWgpJ_cxkGc5R5Rowt8J_aUhunYVo2wOM3XNpKlAVRnT5zUg2VWw4LMaB02FWvy5XPLnwdYrBcUhyziEyOaFk0FczILZt9LyHPDlmdWQiaPzWi"/>
                </div>
            </div>

            <!-- Road Rehabilitation -->
            <div class="md:col-span-4 bento-card bg-primary text-on-primary rounded-xl p-8 shadow-md flex flex-col justify-between hover:shadow-lg transition-all">
                <div class="space-y-4">
                    <span class="material-symbols-outlined text-4xl text-secondary-container">engineering</span>
                    <h3 class="text-headline-sm font-headline-sm font-bold">Infrastructure & Road Restorations</h3>
                    <p class="text-body-sm opacity-90 leading-relaxed">
                        Facilitated the restoration of over 25 crucial rural feeder roads, linking agricultural hubs in Dunukofia, Njikoka, and Anaocha directly to main urban markets.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-white/20">
                    <div class="text-3xl font-bold text-secondary-container">25+ Roads</div>
                    <div class="text-label-sm opacity-70">Completed & Restored</div>
                </div>
            </div>

            <!-- Solar Water Boreholes -->
            <div class="md:col-span-8 bento-card relative overflow-hidden rounded-xl shadow-xs min-h-[320px] group hover:shadow-md transition-all">
                <div class="absolute inset-0 z-0">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Solar irrigation water project" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrOXHxaRMrrDoSg8U4ASs6r-1Hsuynbdg_yEX_viyBu5tM2lzekPKFCKHYWK2Bg7awnTIIPNTK5sLodU7XyHXapBL-hIS6wTJt-8_-P0aKB85VtntV1iZh-YFiE98SxA9dWowMZcqPVPo24IjKHKQ5pAZQrlD1W38GqjVRJBRqviDH5ZQqIhUHMSVgb0fR-lU-V5raNTsb82OFhnYYbPVKwJ22SHcATnpII-4hy2voPO2iP0pw87du7usytsPkx43HgmX4OlCHzDAh"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/60 to-transparent"></div>
                </div>
                <div class="relative z-10 p-8 flex flex-col justify-end h-full text-white space-y-2">
                    <span class="inline-block px-3 py-1 bg-secondary-container text-on-secondary-container font-label-sm rounded-full w-fit">Utility Infrastructure</span>
                    <h3 class="text-headline-md font-headline-md font-bold">Solar-Powered Borehole Facilities</h3>
                    <p class="text-body-md text-white/90 max-w-xl leading-relaxed">
                        Installed solar-powered clean water distribution stations across 42 distinct communities, giving clean drinking water access to over 150,000 households previously reliant on streams.
                    </p>
                </div>
            </div>

            <!-- Education and ICT -->
            <div class="md:col-span-6 bento-card bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
                <div class="space-y-4">
                    <div class="inline-flex p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined text-3xl">school</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-primary">Educational Grants & ICT Centres</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed">
                        Constructed and equipped ICT resource centers in local schools, ensuring students gain digital literacy. Provided yearly scholarships and learning aids for over 5,000 students.
                    </p>
                </div>
                <div class="mt-6 aspect-video rounded-lg overflow-hidden border border-outline-variant/30">
                    <img class="w-full h-full object-cover" alt="Empowering students" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQDyq6rDLkK0k3MJnUuYhDVXHbq6eOjuLiIkvMmqJ2kRz7NBL3aDneLPe8KVCbt-WgDQqKXGBQTwZb3KlQmsBTQ0P-LYsD5wTqT2LXi0AfPal4jP9t7wqEnCJhXuG04mudWwZA-sMiBVORaB8yGdFGdVheDsET-Cu6vwla66mQ63kA3yGMQpDyBZHbigF1YxR7rOaBY6r1e9SNtDV7c0iNXXWoYCE30qdqJ6ASJKkebWE3u50zRl5xnkd_C-rGbT6-0UMtvjpzeWP_"/>
                </div>
            </div>

            <!-- Agriculture & Agro Grants -->
            <div class="md:col-span-6 bento-card bg-white border border-outline-variant/30 rounded-xl p-8 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
                <div class="space-y-4">
                    <div class="inline-flex p-3 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined text-3xl">agriculture</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-primary">Agro-Grants & Seed Supplies</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed">
                        Supported over 10,000 local cooperative farmers with high-yield hybrid seedlings, modern agricultural tools, fertilizer distribution, and low-interest capital grants.
                    </p>
                </div>
                <div class="mt-6 aspect-video rounded-lg overflow-hidden border border-outline-variant/30">
                    <img class="w-full h-full object-cover" alt="Agro-Entrepreneurship" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7egPGBAvlSZLOKj5VpqEjwQh3wHwMRccK0_ZZNd-jP-VOtxO8y-nIvAsFyi81PwnjPFJHyEnIKlHPXm_G760aB1N-CzWEKbXf9pzLWb-FQEd1Nr4t-R-ng-vVVQrE__BTdcNSUsK_loDStdtRAEIY81D3m3kK7Eaual53bYjHZpboCeTR7S_7XxZETuOum7SLrmpyyUzCrYq6WGsaHcDc4K5NlDnlFGPUxD8vYj5RyjmQwY5YWc_Dy4sZWRH5XjLAmwusNClOi58h"/>
                </div>
            </div>
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
