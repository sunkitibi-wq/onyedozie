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
                <img class="w-full h-full object-cover" alt="Hon. Dozie Nwankwo portrait" src="{{ asset('images/dozie.jpeg') }}"/>
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

<!-- Interactive Giant Strides Section -->
<section id="strides-section" class="py-20 bg-surface-container-lowest border-t border-outline-variant/30" x-data="stridesSearch()">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center max-w-3xl mx-auto mb-12 space-y-4">
            <div class="accent-bar mx-auto"></div>
            <h2 class="text-headline-lg font-headline-lg text-primary font-bold">7 Years of Giant Strides</h2>
            <p class="text-body-md font-body-md text-on-surface-variant max-w-2xl mx-auto">
                Explore the 55 key projects, interventions, and community-driven initiatives completed by Hon. Dozie Nwankwo (Onyendozi) during his tenure in the House of Representatives (excluding bills and motions).
            </p>
        </div>

        <!-- Interactive Control Panel -->
        <div class="bg-surface-container p-6 rounded-2xl border border-outline-variant/30 mb-8 space-y-6">
            <!-- Search bar -->
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" 
                       x-model="search" 
                       placeholder="Search projects, locations, or keywords (e.g. borehole, school, health centre)..." 
                       class="w-full pl-12 pr-10 py-3.5 bg-white border border-outline-variant rounded-xl focus:outline-hidden focus:border-primary focus:ring-1 focus:ring-primary shadow-sm text-body-md"
                >
                <button x-show="search.length > 0" 
                        @click="search = ''" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer"
                        style="display: none;"
                >
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                <!-- LGA Filters -->
                <div class="md:col-span-6 space-y-2">
                    <span class="text-label-sm uppercase font-bold text-on-surface-variant/80 tracking-wider block mb-1">Filter by LGA</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="lga in lgas" :key="lga">
                            <button @click="selectedLga = lga; limit = 12" 
                                    class="px-4 py-2 rounded-full border text-xs font-semibold transition-all cursor-pointer"
                                    :class="selectedLga === lga ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary hover:text-primary'"
                                    x-text="lga"
                            ></button>
                        </template>
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="md:col-span-6 space-y-2">
                    <span class="text-label-sm uppercase font-bold text-on-surface-variant/80 tracking-wider block mb-1">Filter by Category</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="cat in categories" :key="cat.value">
                            <button @click="selectedCategory = cat.value; limit = 12" 
                                    class="px-4 py-2 rounded-full border text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5"
                                    :class="selectedCategory === cat.value ? 'bg-secondary text-on-secondary border-secondary shadow-xs' : 'bg-white text-on-surface-variant border-outline-variant hover:border-secondary hover:text-secondary-container'"
                            >
                                <span class="material-symbols-outlined text-sm" x-text="cat.icon"></span>
                                <span x-text="cat.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Summary & Count -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-body-sm text-on-surface-variant font-medium">
                Showing <span class="text-primary font-bold text-base" x-text="filteredStrides().length"></span> of <span class="font-bold text-base">55</span> projects
            </p>
            <button x-show="search || selectedLga !== 'All' || selectedCategory !== 'All'" 
                    @click="resetFilters()" 
                    class="text-xs font-bold text-primary hover:text-secondary-container flex items-center gap-1 cursor-pointer transition-colors"
                    style="display: none;"
            >
                <span class="material-symbols-outlined text-sm">restart_alt</span> Reset Filters
            </button>
        </div>

        <!-- Strides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-ref="gridContainer">
            <template x-for="(stride, index) in filteredStrides().slice(0, limit)" :key="stride.id">
                <div class="group bg-white border border-outline-variant/30 hover:border-primary p-6 rounded-xl transition-all duration-300 shadow-xs flex flex-col justify-between hover:-translate-y-1 hover:shadow-md">
                    <div class="space-y-4">
                        <!-- Top details -->
                        <div class="flex justify-between items-start">
                            <!-- Icon and Number -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     :class="{
                                         'bg-blue-50 text-blue-600': stride.category === 'water',
                                         'bg-amber-50 text-amber-600': stride.category === 'education',
                                         'bg-emerald-50 text-emerald-600': stride.category === 'health',
                                         'bg-purple-50 text-purple-600': stride.category === 'infrastructure',
                                         'bg-rose-50 text-rose-600': stride.category === 'empowerment'
                                     }"
                                >
                                    <span class="material-symbols-outlined text-xl" x-text="getCategoryIcon(stride.category)"></span>
                                </div>
                                <span class="text-xs font-bold text-on-surface-variant/40" x-text="'#' + stride.id"></span>
                            </div>
                        </div>

                        <!-- Main content -->
                        <p class="text-body-md text-on-surface leading-relaxed group-hover:text-primary transition-colors font-medium" x-text="stride.text"></p>
                    </div>

                    <!-- Badges at bottom -->
                    <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-outline-variant/20">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-surface-container text-on-surface-variant border border-outline-variant/20" x-text="stride.lga"></span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                              :class="{
                                  'bg-blue-50 text-blue-700 border border-blue-100': stride.category === 'water',
                                  'bg-amber-50 text-amber-700 border border-amber-100': stride.category === 'education',
                                  'bg-emerald-50 text-emerald-700 border border-emerald-100': stride.category === 'health',
                                  'bg-purple-50 text-purple-700 border border-purple-100': stride.category === 'infrastructure',
                                  'bg-rose-50 text-rose-700 border border-rose-100': stride.category === 'empowerment'
                              }"
                              x-text="stride.category"
                        ></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredStrides().length === 0" 
             class="text-center py-16 bg-white border border-outline-variant/30 rounded-2xl p-8"
             style="display: none;"
        >
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4">search_off</span>
            <h3 class="text-headline-md font-bold text-on-surface mb-2">No Matching Projects Found</h3>
            <p class="text-body-md text-on-surface-variant max-w-md mx-auto mb-6">
                Try adjusting your filters or search keywords to find what you are looking for.
            </p>
            <button @click="resetFilters()" class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-label-md hover:bg-primary-container transition-all cursor-pointer">
                Reset All Filters
            </button>
        </div>

        <!-- Load More Section -->
        <div x-show="filteredStrides().length > limit" class="text-center mt-12" style="display: none;">
            <button @click="limit += 12" class="bg-white border-2 border-primary text-primary px-8 py-4 rounded-xl font-label-md hover:bg-primary hover:text-on-primary transition-all cursor-pointer shadow-xs hover:shadow-md">
                Load More Projects
            </button>
        </div>
    </div>
</section>

<script>
    function stridesSearch() {
        return {
            search: '',
            selectedLga: 'All',
            selectedCategory: 'All',
            limit: 12,
            lgas: ['All', 'Dunukofia', 'Njikoka', 'Anaocha', 'Constituency-Wide'],
            categories: [
                { value: 'All', label: 'All Categories', icon: 'grid_view' },
                { value: 'water', label: 'Water Projects', icon: 'water_drop' },
                { value: 'education', label: 'Education & Youth', icon: 'school' },
                { value: 'health', label: 'Health & Medical', icon: 'medical_services' },
                { value: 'infrastructure', label: 'Infrastructure', icon: 'home_work' },
                { value: 'empowerment', label: 'Empowerment', icon: 'volunteer_activism' }
            ],
            getCategoryIcon(cat) {
                switch(cat) {
                    case 'water': return 'water_drop';
                    case 'education': return 'school';
                    case 'health': return 'medical_services';
                    case 'infrastructure': return 'home_work';
                    case 'empowerment': return 'volunteer_activism';
                    default: return 'info';
                }
            },
            resetFilters() {
                this.search = '';
                this.selectedLga = 'All';
                this.selectedCategory = 'All';
                this.limit = 12;
            },
            strides: [
                { id: 1, text: `Construction of Modern Health Centre with Doctor's Quarters and Toilet Facilities, At Nnamdi Azikiwe Teaching Hospital, Ukpo, Dunukofia LGA by Hon Dozie Nwankwo.`, lga: `Dunukofia`, category: `health` },
                { id: 2, text: `Rehabilitation of Class Room Blocks at Nneamaka Secondary School ,Ifitedunu, Dunukofia LGA by Hon Dozie Nwankwo`, lga: `Dunukofia`, category: `education` },
                { id: 3, text: `Massive Erosion control project, Enugwu Ukwu, Njikoka LGA by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `infrastructure` },
                { id: 4, text: `Construction of Two Storey ICT building with E- library, Hall, Entrepreneurship centre as well as Health centre located near Njikoka local government, Abagana, Njikoka LGA by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `education` },
                { id: 5, text: `Large Parameter Fencing Of Ide Secondary School, Enugwu Ukwu, Njikoka LGA with borehole ,and new gate in place by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `education` },
                { id: 6, text: `Construction Of Motorized Borehole at Ifiteani village, Agulu Anaocha LGA by Hon Dozie Nwankwo.`, lga: `Anaocha`, category: `water` },
                { id: 7, text: `Provision Of Motorized Borehole Facilities at Eke market Square ,Adazi Nnukwu, Anaocha LGA by Hon Dozie Nwankwo.`, lga: `Anaocha`, category: `water` },
                { id: 8, text: `Construction Of Motorized borehole at Igwe's palace, Akwaeze, Anaocha LGA by Hon Dozie Nwankwo.`, lga: `Anaocha`, category: `water` },
                { id: 9, text: `Provision Of Motorized Borehole Facilities At Adazi Enu, Anaocha LGA by Hon Dozie Nwankwo.`, lga: `Anaocha`, category: `water` },
                { id: 10, text: `Rehabilitation and Modernization of Primary health center at Neni, Anaocha LGA with maternity section, ward ,OPD , Residential Area and Pharmacy Section.`, lga: `Anaocha`, category: `health` },
                { id: 11, text: `Drilling of Borehole at Oye Nimo, Njikoka LGA, with reticulation by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `water` },
                { id: 12, text: `Construction of Pavilion at Egwe Egwe Nimo, Njikoka LGA by Hon Dozie Nwankwo .`, lga: `Njikoka`, category: `infrastructure` },
                { id: 13, text: `Siting of a Borehole Project at Akpu Abagana, Njikoka LGA by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `water` },
                { id: 14, text: `Motorized borehole at Ozu village, Umunnachi, Dunukofia LGA by Hon Dozie Nwankwo.`, lga: `Dunukofia`, category: `water` },
                { id: 15, text: `Motorized borehole project, Enuagu Village, Enugwu Ukwu, Njikoka LGA.`, lga: `Njikoka`, category: `water` },
                { id: 16, text: `Two storey modern civic centre ,with library, health care section ,etc, situated at Enuagu Village, Enugwu Ukwu, Njikoka LGA`, lga: `Njikoka`, category: `infrastructure` },
                { id: 17, text: `Provision Of NYSC (Corpers) Accomodation at Ichida Community, Anaocha LGA. Anambra State.`, lga: `Anaocha`, category: `infrastructure` },
                { id: 18, text: `Installation of Street light in Nawfia, from Opposite St. Michaels Anglican church, to Uruoji at Nawfia , Njikoka LGA, Anambra State`, lga: `Njikoka`, category: `infrastructure` },
                { id: 19, text: `Construction of Doctors Quarters At The Primary health centre, Enugwu Agidi, Njikoka LGA by Hon Dozie Nwankwo.`, lga: `Njikoka`, category: `health` },
                { id: 20, text: `Installation Of Solar Powered Street Lights at Ire, Umuatulu, Enuagu, Umuatuora and Akiyi Villages Of Enugwu Ukwu, Njikoka LGA,`, lga: `Njikoka`, category: `infrastructure` },
                { id: 21, text: `Furnishing of School Hall Nnaemeka Community Secondary School Ifitedunu, Dunukofia LGA, Anambra State. (Attracted)`, lga: `Dunukofia`, category: `education` },
                { id: 22, text: `Renovation of 3 Classroom Blocks at Obiechi Primary School Umunnachi, Dunukofia LGA, Anambra State.`, lga: `Dunukofia`, category: `education` },
                { id: 23, text: `Renovation of Classroom Blocks at Community Secondary (Nimo Girls Secondary School, Nimo), Njikoka LGA, Anambra State. (Attracted)`, lga: `Njikoka`, category: `education` },
                { id: 24, text: `Renovation of Hostel Block at Community Secondary School, Obeledu, Anaocha LGA.`, lga: `Anaocha`, category: `education` },
                { id: 25, text: `Renovation of Hostel Block at Girls Secondary School, Adazi Nnukwu, Anaocha LGA.`, lga: `Anaocha`, category: `education` },
                { id: 26, text: `Drilling of Solar Power Borehole, Construction of Surface Tank , Overhead Tank and Reticulation at Aguluzigbo, Anaocha LGA, Anambra State`, lga: `Anaocha`, category: `water` },
                { id: 27, text: `Provision of a motorized borehole at Igwe's Palace, Ukwulu Dunukofia LGA meant for public use by Hon Dozie Nwankwo.`, lga: `Dunukofia`, category: `water` },
                { id: 28, text: `Drilling of Motorized borehole at Obunagu Village, Nawgu, Dunukofia LGA by Hon Dozie Nwankwo.`, lga: `Dunukofia`, category: `water` },
                { id: 29, text: `Solar Powered Borehole at Skills Acquisition Center, Near Umuru Hall Adazi Ani , Anaocha LGA, Anambra state.`, lga: `Anaocha`, category: `water` },
                { id: 30, text: `Construction of Principal Quarters At Nawgu, Dunukofia LGA Anambra State`, lga: `Dunukofia`, category: `infrastructure` },
                { id: 31, text: `Construction Of Village Hall, Enuagu Enugwu Ukwu Njikoka LGA.`, lga: `Njikoka`, category: `infrastructure` },
                { id: 32, text: `Furnishing of Ifite Civic Center (Furnitures, Air Conditioners And 60KVA Gen. Set) At Ifite Village Civic Center Aguluzigbo, Anaocha LGA, Anambra State.`, lga: `Anaocha`, category: `infrastructure` },
                { id: 33, text: `Construction of 1 Block of 2 bedroom flats, Semidetached Doctors Quarters at Enugu-Agidi Primary Healthcare Center at Enugu Agidi, Njikoka LGA. Anambra State.`, lga: `Njikoka`, category: `health` },
                { id: 34, text: `Construction of Primary Healthcare Center at Ukwulu, Dunukofia LGA, Anambra`, lga: `Dunukofia`, category: `health` },
                { id: 35, text: `Massive Covid-19 Relief Materials in the 3 Local Government Areas Of Anaocha, Njikoka and Dunukofia during the pandemic.`, lga: `Constituency-Wide`, category: `health` },
                { id: 36, text: `Federal Government Skill Acquisition Training for selected women and Youths in Anambra State, facilitated by Hon Ferdinand Dozie Nwankwo.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 37, text: `Federal Government Cash grants for more than 30 women from Anaocha/Njikoka/Dunukofia Federal Constituency Facilitated By Hon Ferdinand Dozie Nwankwo.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 38, text: `Facilitated Employment For Over 109 persons into some Federal Government Ministries, Department And Agencies since 2016.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 39, text: `Capacity Building And Training Of Over 1500 Secondary School Teachers In Njikoka/Anaocha/Dunukofia Federal Constituency`, lga: `Constituency-Wide`, category: `education` },
                { id: 40, text: `Annual Ferdinand Dozie Nwankwo Foundation's Free Medical Outreach For DNA/Anambra Indigenes Where High Scale Operations Are Provided, Like Eye Surgeries , Provision Of Free Eye Glasses, Wheel Chairs, Among many others.`, lga: `Constituency-Wide`, category: `health` },
                { id: 41, text: `Annual Ferdinand Dozie Nwankwo Foundation's Scholarship Scheme That Has Provided Scholarship Of Free Tuition Fees And Accommodations For More Than 4500 Students In The Last 9 Years.`, lga: `Constituency-Wide`, category: `education` },
                { id: 42, text: `Annual Ferdinand Dozie Nwankwo Empowerment Scheme Which Has Provided About 480 Cars, Jeeps, Buses, Keke Napep And Motorcycles For The People Of Anaocha/Njikoka/Dunukofia Federal Constituency In The Last 14 Years.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 43, text: `Ferdinand Dozie Nwankwo Foundation's Empowerment Scheme That Has Provided Sewing Machines, Generating Sets, Grinding Machines and Refrigerators to enhance small scale businesses of our people in DNA Federal Constituency in the last 14 years even before he got elected into public office.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 44, text: `Ferdinand Dozie Nwankwo Foundation's Empowerment Scheme Which Provides Monthly Stipends For Widows, Women And Youths Of DNA Federal Constituency For Years.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 45, text: `Renovation of Hostel blocks at Community Secondary School Adazi-Nnukwu formerly known as Girls' Secondary School Adazi-Nnukwu.`, lga: `Anaocha`, category: `education` },
                { id: 46, text: `Solar Powered Borehole At Skill Acquisition Centre near Amaeku/Umuku Hall ,Adazi-Ani Anaocha LGA.`, lga: `Anaocha`, category: `water` },
                { id: 47, text: `Construction of Corpers lodge/Accommodation at Community Secondary School Ichida Anaocha LGA.`, lga: `Anaocha`, category: `infrastructure` },
                { id: 48, text: `Renovation of Hostel blocks at Community Secondary School Obeledu.`, lga: `Anaocha`, category: `education` },
                { id: 49, text: `Renovation of School Hall at Nneamaka Secondary School, Ifitedunu.`, lga: `Dunukofia`, category: `education` },
                { id: 50, text: `Payment Of WAEC/NECO Fees For Selected Indigent Students Spread Across Secondary Schools In DNA Federal Constituency.`, lga: `Constituency-Wide`, category: `education` },
                { id: 51, text: `Regular Donation Of Relief Materials/Palliatives to the needy, destitute and less privileged in his DNA Federal Constituency.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 52, text: `Ferdinand Dozie Nwankwo's Support To Farmers With Soft Loans and Subsidized Fertilizers to boost food production.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 53, text: `Dozie Nwankwo's Inter Community Football Tournament For youths in the spirit of oneness, unity and talent hunt.`, lga: `Constituency-Wide`, category: `empowerment` },
                { id: 54, text: `Dozie Nwankwo's Annual Essay Competition For Secondary School Students Of Njikoka/Anaocha/ Dunukofia Federal Constituency`, lga: `Constituency-Wide`, category: `education` },
                { id: 55, text: `Dozie Nwankwo's Skills Acquisition Training On Cosmotology, Soap Making, Disinfectants, Perfumes, Air Fresheners, Including Bead Making. And Stain Removal. Afterwhich He Empowered The Training Beneficiaries With Money To Start Up Their Own Businesses, among many others.`, lga: `Constituency-Wide`, category: `empowerment` }
            ],
            filteredStrides() {
                const searchLower = this.search.toLowerCase().trim();
                return this.strides.filter(stride => {
                    const matchesSearch = !searchLower || 
                                          stride.text.toLowerCase().includes(searchLower) ||
                                          stride.lga.toLowerCase().includes(searchLower) ||
                                          stride.category.toLowerCase().includes(searchLower);
                    
                    const matchesLga = this.selectedLga === 'All' || stride.lga === this.selectedLga;
                    const matchesCategory = this.selectedCategory === 'All' || stride.category === this.selectedCategory;
                    
                    return matchesSearch && matchesLga && matchesCategory;
                });
            }
        }
    }
</script>

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
