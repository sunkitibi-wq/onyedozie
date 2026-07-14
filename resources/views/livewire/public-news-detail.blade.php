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
            @if($news->image_path)
                <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" class="w-full h-auto rounded-xl shadow-md mb-8">
            @endif
            
            <div class="prose prose-lg prose-zinc max-w-none prose-headings:font-headline-md prose-headings:text-primary prose-a:text-primary">
                {!! nl2br(e($news->body)) !!}
            </div>
            
            <hr class="border-outline-variant/50 my-10">
            
            <!-- Share Section -->
            <div class="flex items-center justify-between">
                <span class="font-bold text-zinc-900">Share this update:</span>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors">
                        <span class="material-symbols-outlined">share</span>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors">
                        <span class="material-symbols-outlined">mail</span>
                    </a>
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
