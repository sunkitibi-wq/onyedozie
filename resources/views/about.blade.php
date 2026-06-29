@extends('layouts.frontend')

@section('content')
<!-- Hero Section: Biography Intro -->
<section class="relative py-20 overflow-hidden pattern-bg">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
        <div class="lg:col-span-7 z-10 space-y-6">
            <span class="text-primary font-bold tracking-widest text-label-md uppercase">Biography</span>
            <h1 class="text-headline-xl font-headline-xl text-on-surface">Meet Hon. Ferdinand <span class="text-primary font-bold">Dozie Nwankwo</span> (Onyendozi)</h1>
            <p class="text-body-lg font-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                A respected public servant, accomplished entrepreneur, philanthropist, and experienced legislator dedicated to the sustainable development of our local communities and the empowerment of every citizen.
            </p>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <span class="font-label-md text-label-md">Legislator</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">business_center</span>
                    <span class="font-label-md text-label-md">Entrepreneur</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-high px-4 py-2 rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">volunteer_activism</span>
                    <span class="font-label-md text-label-md">Philanthropist</span>
                </div>
            </div>
        </div>
        <div class="lg:col-span-5 relative mt-12 lg:mt-0">
            <div class="aspect-[4/5] rounded-xl overflow-hidden shadow-xl border-4 border-white">
                <img class="w-full h-full object-cover" alt="Hon. Dozie Nwankwo portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrO0p8wSsKrZOM8H6USE5KP7XyfC1DLblNVW4Jvty8T_r8Q74mWnVzMzxY1C-1sc8Wt5eiWRsnIJNVXjrWjftuYObDMsbcXSjAAxOaOYPUaAQsWEyZ4khY-DJUd466fzbSWp1bAnYLx5feM0epCm1Xpy8U3YlO_bRKXVNt_U7IUsaWKVF0_1PpCNn1cGipHs5UC2s6qhQNW6LbAYjHE78LfLz4mm2TbrYP2bjs7m8N32ghA-L3RjZyj3fijJQQvxYimwjCs0tFJ56K"/>
            </div>
            <div class="absolute -bottom-6 -left-6 bg-secondary-container p-6 rounded-lg shadow-lg max-w-[240px]">
                <span class="material-symbols-outlined text-on-secondary-container mb-2" style="font-variation-settings: 'FILL' 1;">format_quote</span>
                <p class="text-on-secondary-container font-headline-md italic leading-tight">
                    "When people succeed, communities prosper."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- A Leader with Experience (House of Reps Achievements) -->
<section class="py-20 bg-surface-container-lowest">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-gutter">
            <div class="max-w-xl space-y-4">
                <div class="accent-bar"></div>
                <h2 class="text-headline-lg font-headline-lg">A Leader with Experience</h2>
                <p class="text-body-md font-body-md text-on-surface-variant">Strategic legislative initiatives that transformed the socio-economic landscape of the constituency through rigorous advocacy and policy-making.</p>
            </div>
        </div>

        <!-- Bento Grid Layout for Achievements -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="md:col-span-2 bento-card bg-surface rounded-xl p-8 border border-outline-variant shadow-sm flex flex-col justify-between">
                <div>
                    <span class="material-symbols-outlined text-primary text-4xl mb-4">account_balance</span>
                    <h3 class="text-headline-md font-headline-md mb-2">Legislative Milestones</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant mb-6">Sponsored and co-sponsored over 15 critical bills focused on rural electrification, educational reform, and small business support systems during tenure in the House of Representatives.</p>
                </div>
                <div class="bg-primary/5 p-4 rounded-lg flex items-center justify-between">
                    <span class="font-label-md text-primary uppercase tracking-wider">Achievements In House</span>
                    <span class="material-symbols-outlined text-primary">trending_up</span>
                </div>
            </div>

            <div class="bento-card bg-primary text-on-primary rounded-xl p-8 shadow-md flex flex-col items-center justify-center text-center">
                <div class="text-5xl font-bold mb-2">25+</div>
                <div class="text-headline-sm font-headline-sm opacity-90">Rural Roads Restored</div>
                <div class="w-12 h-1 bg-secondary-container my-4"></div>
                <p class="text-body-md opacity-80">Enhancing connectivity and boosting local trade within the Njikoka/Dunukofia/Anaocha constituency.</p>
            </div>

            <div class="bento-card bg-surface rounded-xl p-8 border border-outline-variant shadow-sm flex flex-col h-full">
                <div class="mb-auto">
                    <h3 class="text-headline-sm font-headline-sm mb-2">Health Outreach</h3>
                    <p class="text-body-sm text-on-surface-variant">Initiated the multi-million naira Medical Mission providing free surgeries and treatments to over 50,000 constituents.</p>
                </div>
                <div class="mt-6 aspect-video rounded-lg overflow-hidden">
                    <img class="w-full h-full object-cover" alt="Medical Mission outreach" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAU4gy1FH6XErFPdgUo6gz3jXlQoo3bghwKlMCuRWtCeHj2E5h4mLxmbi7GfACs8wk5xVmbT3IGDQgBilholFRBWtxssnwqgAjee4bYy0LJeVhgLFbMdojGcaZZn3vZdHrDNVAeHxeqhYsBkq3IbLSrHF7pYw7DXUIWgpJ_cxkGc5R5Rowt8J_aUhunYVo2wOM3XNpKlAVRnT5zUg2VWw4LMaB02FWvy5XPLnwdYrBcUhyziEyOaFk0FczILZt9LyHPDlmdWQiaPzWi"/>
                </div>
            </div>

            <div class="md:col-span-2 bento-card relative overflow-hidden rounded-xl h-full min-h-[300px]">
                <div class="absolute inset-0 z-0">
                    <img class="w-full h-full object-cover" alt="Solar irrigation project" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrOXHxaRMrrDoSg8U4ASs6r-1Hsuynbdg_yEX_viyBu5tM2lzekPKFCKHYWK2Bg7awnTIIPNTK5sLodU7XyHXapBL-hIS6wTJt-8_-P0aKB85VtntV1iZh-YFiE98SxA9dWowMZcqPVPo24IjKHKQ5pAZQrlD1W38GqjVRJBRqviDH5ZQqIhUHMSVgb0fR-lU-V5raNTsb82OFhnYYbPVKwJ22SHcATnpII-4hy2voPO2iP0pw87du7usytsPkx43HgmX4OlCHzDAh"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                </div>
                <div class="relative z-10 p-8 flex flex-col justify-end h-full">
                    <h3 class="text-headline-md font-headline-md text-white mb-2">Sustainable Infrastructure</h3>
                    <p class="text-body-md text-white/90 max-w-lg">Oversaw the installation of solar-powered water boreholes across 42 communities, ensuring clean water access for thousands of families.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Beyond Public Office -->
<section class="py-20 overflow-hidden relative bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-4">
            <div class="accent-bar mx-auto"></div>
            <h2 class="text-headline-lg font-headline-lg">Leadership Beyond Public Office</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">The Ferdinand Dozie Nwankwo Foundation operates with the firm belief that private success must fuel public progress.</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <!-- Initiative 1 -->
                <div class="group flex gap-6 p-6 rounded-xl hover:bg-surface-container transition-all cursor-pointer border border-transparent hover:border-outline-variant shadow-sm bg-white">
                    <div class="flex-shrink-0 w-14 h-14 bg-secondary-container/10 rounded-lg flex items-center justify-center text-secondary-container">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>
                    <div>
                        <h4 class="text-headline-sm font-headline-sm text-primary mb-1">Education First Initiative</h4>
                        <p class="text-body-md text-on-surface-variant">Providing annual scholarships and educational kits to over 2,000 underprivileged students in the Anambra Central region.</p>
                    </div>
                </div>
                <!-- Initiative 2 -->
                <div class="group flex gap-6 p-6 rounded-xl hover:bg-surface-container transition-all cursor-pointer border border-transparent hover:border-outline-variant shadow-sm bg-white">
                    <div class="flex-shrink-0 w-14 h-14 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">agriculture</span>
                    </div>
                    <div>
                        <h4 class="text-headline-sm font-headline-sm text-primary mb-1">Agro-Entrepreneurship Grants</h4>
                        <p class="text-body-md text-on-surface-variant">Empowering local farmers with modern equipment and low-interest capital to modernize traditional agriculture.</p>
                    </div>
                </div>
                <!-- Initiative 3 -->
                <div class="group flex gap-6 p-6 rounded-xl hover:bg-surface-container transition-all cursor-pointer border border-transparent hover:border-outline-variant shadow-sm bg-white">
                    <div class="flex-shrink-0 w-14 h-14 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">diversity_3</span>
                    </div>
                    <div>
                        <h4 class="text-headline-sm font-headline-sm text-primary mb-1">Community Town Hall Forum</h4>
                        <p class="text-body-md text-on-surface-variant">Regular engagement platforms that bring policy discussions directly to the grassroots for collective decision-making.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="pt-12">
                    <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/5] mb-4">
                        <img class="w-full h-full object-cover" alt="Empowering students" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQDyq6rDLkK0k3MJnUuYhDVXHbq6eOjuLiIkvMmqJ2kRz7NBL3aDneLPe8KVCbt-WgDQqKXGBQTwZb3KlQmsBTQ0P-LYsD5wTqT2LXi0AfPal4jP9t7wqEnCJhXuG04mudWwZA-sMiBVORaB8yGdFGdVheDsET-Cu6vwla66mQ63kA3yGMQpDyBZHbigF1YxR7rOaBY6r1e9SNtDV7c0iNXXWoYCE30qdqJ6ASJKkebWE3u50zRl5xnkd_C-rGbT6-0UMtvjpzeWP_"/>
                    </div>
                    <div class="bg-surface-container p-4 rounded-lg text-center">
                        <p class="font-label-md text-primary">Empowering the Future</p>
                    </div>
                </div>
                <div>
                    <div class="bg-primary-container p-4 rounded-lg mb-4 text-center text-on-primary">
                        <p class="font-label-md">Building Legacies</p>
                    </div>
                    <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/5]">
                        <img class="w-full h-full object-cover" alt="Grassroots market renovation" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7egPGBAvlSZLOKj5VpqEjwQh3wHwMRccK0_ZZNd-jP-VOtxO8y-nIvAsFyi81PwnjPFJHyEnIKlHPXm_G760aB1N-CzWEKbXf9pzLWb-FQEd1Nr4t-R-ng-vVVQrE__BTdcNSUsK_loDStdtRAEIY81D3m3kK7Eaual53bYjHZpboCeTR7S_7XxZETuOum7SLrmpyyUzCrYq6WGsaHcDc4K5NlDnlFGPUxD8vYj5RyjmQwY5YWc_Dy4sZWRH5XjLAmwusNClOi58h"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 px-margin-mobile md:px-margin-desktop bg-surface">
    <div class="max-w-container-max mx-auto bg-primary rounded-2xl p-8 md:p-12 text-center relative overflow-hidden shadow-2xl">
        <!-- Abstract Pattern Overlays -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 space-y-6">
            <h2 class="text-headline-lg font-headline-lg text-on-primary">Be Part of the Onyendozi Vision</h2>
            <p class="text-body-lg text-on-primary/80 max-w-2xl mx-auto leading-relaxed">Join thousands of volunteers and supporters working towards a prosperous future for Anambra Central Senatorial District.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                <a href="{{ route('join') }}" class="bg-secondary-container text-on-secondary-container px-8 py-4 rounded-xl font-label-md hover:scale-105 transition-transform shadow-lg">Become a Volunteer</a>
                <a href="{{ route('join') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-label-md hover:bg-white hover:text-primary transition-all">Support the Campaign</a>
            </div>
        </div>
    </div>
</section>
@endsection
