@extends('layouts.frontend')

@section('content')
<section class="py-24 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-secondary/10 text-secondary rounded-full text-sm uppercase tracking-widest font-semibold">Gallery</span>
            <h1 class="font-headline-lg text-headline-lg text-primary font-bold mt-6">Explore Our Image & Video Stories</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto mt-4 leading-relaxed">
                Discover more of our campaign activities, community outreach, and on-the-ground impact through curated photo and video showcases.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('video-gallery') }}" class="group block overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="relative aspect-video bg-black overflow-hidden">
                    <img src="{{ asset('images/gallery/medical.jpeg') }}" alt="Video gallery preview" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/25"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="inline-flex items-center gap-3 rounded-full bg-primary/95 px-4 py-3 text-on-primary font-semibold shadow-lg">
                            <span class="material-symbols-outlined">play_arrow</span>
                            Watch Video Gallery
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Video Gallery</h2>
                    <p class="mt-3 text-body-md text-on-surface-variant">A selection of campaign videos and community stories from Onyendozi Connect.</p>
                </div>
            </a>

            <a href="{{ route('image-gallery') }}" class="group block overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="grid grid-cols-2 gap-2 p-2 bg-surface-container-highest">
                    <img src="{{ asset('images/dozie.jpg') }}" alt="Image gallery preview" class="aspect-square w-full object-cover rounded-3xl">
                    <img src="{{ asset('images/do2.jpg') }}" alt="Image gallery preview" class="aspect-square w-full object-cover rounded-3xl">
                    <img src="{{ asset('images/gallery/medical.jpeg') }}" alt="Image gallery preview" class="aspect-square w-full object-cover rounded-3xl">
                    <div class="aspect-square w-full rounded-3xl bg-primary/10 flex items-center justify-center text-primary font-bold text-body-lg">
                        + more photos
                    </div>
                </div>
                <div class="p-6">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Image Gallery</h2>
                    <p class="mt-3 text-body-md text-on-surface-variant">Browse event photos and campaign highlights from across Anambra Central.</p>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
