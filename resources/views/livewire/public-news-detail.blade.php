<div class="bg-surface min-h-screen pb-24">
    <!-- Hero Article Header -->
    <section class="bg-primary text-on-primary py-16 mb-12 relative overflow-hidden">
        <div class="absolute inset-0 pattern-bg opacity-20"></div>
        <div class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop relative z-10">
            <a href="{{ url('/updates') }}" wire:navigate class="inline-flex items-center gap-2 text-primary-container hover:text-white transition-colors font-label-md mb-8">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Updates
            </a>
            
            <div class="flex items-center gap-3 mb-6 text-sm font-bold">
                <span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full">{{ $news->category }}</span>
                <span>{{ $news->published_at->format('F d, Y') }}</span>
                @if($news->is_breaking)
                    <span class="bg-red-600 text-white px-3 py-1 rounded-full flex items-center gap-1 uppercase tracking-wider animate-pulse">
                        <span class="material-symbols-outlined text-sm">emergency</span> Breaking
                    </span>
                @endif
            </div>
            
            <h1 class="text-headline-lg md:text-headline-xl font-headline-xl font-bold leading-tight mb-6">
                {{ $news->title }}
            </h1>
        </div>
    </section>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Main Article Content -->
        <article class="lg:col-span-8 space-y-8 bg-white p-6 md:p-10 rounded-xl shadow-sm border border-outline-variant/30">
            @if($news->video_path)
                <video src="{{ asset('storage/' . $news->video_path) }}" controls class="w-full h-auto rounded-xl shadow-md mb-8 bg-black"></video>
            @elseif($news->image_path)
                <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" class="w-full h-auto rounded-xl shadow-md mb-8">
            @endif
            
            <div class="prose prose-lg prose-zinc max-w-none prose-headings:font-headline-md prose-headings:text-primary prose-a:text-primary">
                {!! nl2br(e($news->body)) !!}
            </div>

            @php
                $photos = is_array($news->photos) ? $news->photos : (json_decode($news->photos, true) ?? []);
                $videos = is_array($news->videos) ? $news->videos : (json_decode($news->videos, true) ?? []);
            @endphp
            
            @if(!empty($photos) || !empty($videos))
                <div class="mt-12">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-primary mb-6">Media Gallery</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($photos as $photo)
                            <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="block aspect-square overflow-hidden rounded-xl shadow-sm border border-outline-variant/30">
                                <img src="{{ asset('storage/' . $photo) }}" alt="Gallery Image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </a>
                        @endforeach
                        @foreach($videos as $vid)
                            <div class="aspect-square overflow-hidden rounded-xl shadow-sm border border-outline-variant/30 bg-black">
                                <video src="{{ asset('storage/' . $vid) }}" controls class="w-full h-full object-cover"></video>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <hr class="border-outline-variant/50 my-10">
            
            <!-- Share Section -->
            <div class="flex items-center justify-between border-t border-outline-variant/30 pt-6 mt-6">
                <span class="font-bold text-zinc-900">Share this update:</span>
                <div class="flex gap-3 flex-wrap">
                    @php
                        $shareUrl = urlencode(request()->url());
                        $shareText = urlencode($news->title);
                    @endphp
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#1877F2]/10 flex items-center justify-center text-[#1877F2] hover:bg-[#1877F2] hover:text-white transition-colors" title="Share on Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <!-- Twitter / X -->
                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-black/10 flex items-center justify-center text-black hover:bg-black hover:text-white transition-colors" title="Share on X">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                        </svg>
                    </a>
                    <!-- WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#25D366]/10 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors" title="Share on WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.016 2.01c-5.508 0-9.98 4.473-9.98 9.982 0 1.761.458 3.486 1.332 5.006L2.01 22l5.127-1.345a9.983 9.983 0 004.88 1.272c5.506 0 9.978-4.474 9.978-9.982 0-5.509-4.472-9.935-9.98-9.935zM8.324 7.558c-.286-.64-.59-.654-.86-.665-.246-.01-.527-.01-.813-.01a1.56 1.56 0 00-1.135.534c-.388.423-1.492 1.459-1.492 3.559s1.53 4.133 1.745 4.418c.216.286 3.011 4.597 7.29 6.444.996.429 1.77.685 2.378.878.999.317 1.91.272 2.628.165.805-.12 2.47-.1 2.82-.464.35-.365.35-1.01.246-1.108-.103-.1-.387-.156-.814-.37l-2.47-1.22c-.427-.212-.738-.318-1.049.155-.311.472-1.186 1.508-1.454 1.815-.268.307-.537.345-.964.133-.427-.212-1.764-.65-3.36-2.083-1.242-1.116-2.08-2.497-2.323-2.923-.242-.426-.026-.657.188-.868.192-.19.427-.497.641-.745.213-.249.284-.426.427-.71.142-.284.07-.534-.035-.747-.107-.213-1.05-2.536-1.439-3.473z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#0A66C2]/10 flex items-center justify-center text-[#0A66C2] hover:bg-[#0A66C2] hover:text-white transition-colors" title="Share on LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <!-- Email -->
                    <a href="mailto:?subject={{ $shareText }}&body={{ $shareUrl }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors" title="Share via Email">
                        <span class="material-symbols-outlined">mail</span>
                    </a>
                    <!-- Copy Link -->
                    <button onclick="navigator.clipboard.writeText('{{ urldecode($shareUrl) }}'); alert('Link copied to clipboard!');" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors" title="Copy Link">
                        <span class="material-symbols-outlined">link</span>
                    </button>
                </div>
            </div>
        </article>

        <!-- Sidebar / Latest News -->
        <aside class="lg:col-span-4 space-y-8">
            <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30">
                <h3 class="font-headline-sm text-headline-sm font-bold text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined">update</span> Latest Updates
                </h3>
                
                @if($latestNews->count() > 0)
                    <div class="space-y-6">
                        @foreach($latestNews as $latest)
                            <a href="{{ url('/updates/' . $latest->id) }}" wire:navigate class="group block border-b border-outline-variant/30 pb-6 last:border-0 last:pb-0">
                                <span class="text-xs font-bold text-primary mb-1 block">{{ $latest->category }} &bull; {{ $latest->published_at->format('M d') }}</span>
                                <h4 class="font-headline-sm text-headline-sm font-bold text-zinc-900 group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $latest->title }}
                                </h4>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-on-surface-variant text-sm">No other updates available.</p>
                @endif
                
                <a href="{{ url('/updates') }}" wire:navigate class="block w-full text-center mt-6 bg-white border border-primary text-primary font-bold py-2 rounded-lg hover:bg-primary/5 transition-colors">
                    View All News
                </a>
            </div>
            
            <div class="bg-primary text-on-primary rounded-xl p-6 shadow-md">
                <h3 class="font-headline-sm font-bold mb-3">Join the Movement</h3>
                <p class="text-sm opacity-90 mb-6">Stay connected with our latest initiatives and become a volunteer today.</p>
                <a href="{{ route('join') }}" wire:navigate class="block w-full text-center bg-secondary text-on-secondary font-bold py-3 rounded-lg hover:bg-secondary-container transition-colors">
                    Get Involved
                </a>
            </div>
        </aside>
    </div>
</div>
