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
                        src="https://www.youtube.com/embed/E9yLf10FtvI"
                        title="Onyendozi Connect Video 1"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Onyendozi Campaign Highlight</h2>
                    <p class="mt-3 text-body-md text-on-surface-variant">A closer look at our campaign engagement and community outreach efforts.</p>
                </div>
            </div>

            <div class="group overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-video bg-black overflow-hidden">
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/RtXwij-tZHo"
                        title="Onyendozi Connect Video 2"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Campaign Update</h2>
                    <p class="mt-3 text-body-md text-on-surface-variant">Updates from the field and key program announcements.</p>
                </div>
            </div>

            <div class="group overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-video bg-black overflow-hidden">
                    <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/E9yLf10FtvI"
                        title="Onyendozi Connect Video 3"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Community Program Feature</h2>
                    <p class="mt-3 text-body-md text-on-surface-variant">A feature spotlighting our work with local communities and stakeholders.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
