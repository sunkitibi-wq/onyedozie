<div class="py-12 bg-surface min-h-screen">
    <!-- Hero Header -->
    <section class="bg-primary text-on-primary py-16 mb-12 relative overflow-hidden">
        <div class="absolute inset-0 pattern-bg opacity-20"></div>
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop relative z-10 text-center">
            <h1 class="text-headline-xl font-headline-xl font-bold mb-4">News & Updates</h1>
            <p class="text-body-lg font-body-lg opacity-90 max-w-2xl mx-auto">
                Stay informed with the latest campaign news, press releases, and announcements from Onyendozi Connect.
            </p>
        </div>
    </section>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <h3 class="font-headline-sm text-headline-sm font-bold text-primary mb-4">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <button wire:click="setCategory('')" class="w-full text-left px-3 py-2 rounded-lg transition-colors {{ $category === '' ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface hover:bg-surface-variant' }}">
                            All News
                        </button>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <button wire:click="setCategory('{{ $cat }}')" class="w-full text-left px-3 py-2 rounded-lg transition-colors {{ $category === $cat ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface hover:bg-surface-variant' }}">
                                {{ $cat }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            @if($newsItems->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($newsItems as $news)
                        <a href="{{ url('/updates/' . $news->id) }}" wire:navigate class="group bg-white rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
                            <div class="aspect-video bg-surface-container-high relative overflow-hidden">
                                @if($news->image_path)
                                    <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-primary/30">
                                        <span class="material-symbols-outlined text-6xl">campaign</span>
                                    </div>
                                @endif
                                @if($news->is_breaking)
                                    <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wider animate-pulse">
                                        Breaking
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-3 mb-3 text-xs text-on-surface-variant">
                                    <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $news->category }}</span>
                                    <span>{{ $news->published_at->format('M d, Y') }}</span>
                                </div>
                                <h2 class="font-headline-md text-headline-md font-bold text-zinc-900 group-hover:text-primary transition-colors line-clamp-2 mb-3">
                                    {{ $news->title }}
                                </h2>
                                <p class="text-body-sm text-on-surface-variant line-clamp-3 mb-4 flex-1">
                                    {{ Str::limit(strip_tags($news->body), 150) }}
                                </p>
                                <span class="text-primary font-bold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                    Read More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <div class="pt-6">
                    {{ $newsItems->links() }}
                </div>
            @else
                <div class="text-center py-24 bg-white rounded-xl shadow-sm border border-outline-variant/30">
                    <span class="material-symbols-outlined text-6xl text-primary/30 mb-4">newspaper</span>
                    <h3 class="font-headline-md text-headline-md font-bold text-zinc-900">No Articles Found</h3>
                    <p class="text-on-surface-variant mt-2">Check back later for updates and announcements.</p>
                </div>
            @endif
        </div>
    </div>
</div>
