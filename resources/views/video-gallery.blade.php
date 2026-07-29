@extends('layouts.frontend')

@section('content')
<section class="py-24 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-secondary/10 text-secondary rounded-full text-sm uppercase tracking-widest font-semibold">Video Gallery</span>
            <h1 class="font-headline-lg text-headline-lg text-primary font-bold mt-6">Onyendozi Connect Video Gallery</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto mt-4 leading-relaxed">
                Explore a collection of campaign videos highlighting our engagements, initiatives, and community impact across Anambra Central.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="group overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-video bg-black overflow-hidden">
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/Z3BJZ0y-O7Y?si=9_YKLO05PSIDqYLc"
                        title="Trailer of Hon Dozie Nwankwo Hosting Soludo in Enugwu Ukwu"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">
                        Trailer of Hon Dozie Nwankwo Hosting Soludo in Enugwu Ukwu</h2>
                    
                </div>
            </div>

            <div class="group overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-video bg-black overflow-hidden">
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/RtXwij-tZHo"
                        title="COMMISSIONING OF HON FERDINAND DOZIE NWANKWO CENTRE FOR DEVELOPMENT AND ICT
"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">
                        COMMISSIONING OF HON FERDINAND DOZIE NWANKWO CENTRE FOR DEVELOPMENT AND ICTe</h2>
                   
                </div>
            </div>

            

            <div class="group overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-video bg-black overflow-hidden">
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/E9yLf10FtvI"
                        title="Watch Onyendozi as he Steals the Show at Soludo/Ibezim Rally with Powerful Speech & Dance Moves"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Watch Onyendozi as he Steals the Show at Soludo/Ibezim Rally with Powerful Speech & Dance Moves
</h2>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
