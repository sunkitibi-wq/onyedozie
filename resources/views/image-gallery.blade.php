@extends('layouts.frontend')

@section('content')
<section class="py-24 bg-surface">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-secondary/10 text-secondary rounded-full text-sm uppercase tracking-widest font-semibold">Image Gallery</span>
            <h1 class="font-headline-lg text-headline-lg text-primary font-bold mt-6">Onyendozi Connect Image Gallery</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto mt-4 leading-relaxed">
                Browse images from recent community outreach, campaign events, healthcare initiatives, and grassroots development activities.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @php
                $images = [
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.43 (1).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.43 (2).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.43.jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (1).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (2).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.44 (3).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.44.jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (1).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (2).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.45 (3).jpeg',
                    'images/gallery/WhatsApp Image 2026-06-29 at 10.55.45.jpeg',
                ];
            @endphp

            @foreach ($images as $image)
                <div class="overflow-hidden rounded-3xl border border-outline-variant bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ asset($image) }}" alt="Gallery image" class="w-full h-80 object-cover transition-transform duration-300 hover:scale-105">
                    <div class="p-4">
                        <p class="font-body-md text-on-surface">Onyendozi Connect community outreach and campaign engagement.</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
