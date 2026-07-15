<div class="space-y-6">
    <!-- Notifications -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main News List -->
        <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">News & Updates Directory</h3>
                    <p class="text-xs text-zinc-500">Manage campaign news, announcements, and articles.</p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Search -->
                    <input type="text" wire:model.live="search" class="px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-900 dark:text-white w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Search title or body...">

                    <!-- Create Button -->
                    <button wire:click="startCreate" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold whitespace-nowrap flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Article
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                <select wire:model.live="categoryFilter" class="px-2.5 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs text-zinc-655 dark:text-zinc-350 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 font-medium text-xs uppercase tracking-wider">
                            <th class="py-2.5 pr-4">Title</th>
                            <th class="py-2.5 pr-4">Category</th>
                            <th class="py-2.5 pr-4">Status</th>
                            <th class="py-2.5 pr-4">Published At</th>
                            <th class="py-2.5 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newsItems as $news)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/50 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10">
                                <td class="py-3 pr-4 font-semibold text-zinc-900 dark:text-white flex items-center gap-3">
                                    @if($news->image_path)
                                        <img src="{{ asset('storage/' . $news->image_path) }}" class="w-8 h-8 rounded object-cover border border-zinc-200 dark:border-zinc-700" alt="News Image">
                                    @else
                                        <div class="w-8 h-8 rounded bg-zinc-150 dark:bg-zinc-850 flex items-center justify-center text-xs font-bold text-zinc-500">
                                            <span class="material-symbols-outlined text-sm">article</span>
                                        </div>
                                    @endif
                                    <span class="truncate max-w-[200px]">{{ $news->title }}</span>
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-600 dark:text-zinc-400">
                                    <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded text-zinc-800 dark:text-zinc-300">
                                        {{ $news->category }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 text-xs">
                                    @if($news->is_breaking)
                                        <span class="text-red-600 bg-red-50 dark:bg-red-950/20 px-2 py-0.5 rounded font-medium border border-red-200 dark:border-red-800">Breaking</span>
                                    @else
                                        <span class="text-zinc-500">Standard</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-xs text-zinc-500">
                                    {{ $news->published_at ? $news->published_at->format('M d, Y H:i') : 'Draft' }}
                                </td>
                                <td class="py-3 pr-4 flex gap-2">
                                    <button wire:click="selectNews({{ $news->id }})" class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 text-zinc-750 dark:text-zinc-200 rounded text-xs font-semibold">
                                        Edit
                                    </button>
                                    <button wire:click="deleteNews({{ $news->id }})" wire:confirm="Are you sure you want to delete this article?" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded text-xs font-semibold">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $newsItems->links() }}</div>
            </div>
        </div>

        <!-- Detail/Create Sidebar Panel -->
        <div class="p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
            @if($isCreating || $selectedNewsId)
                <!-- Form -->
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">
                    {{ $isCreating ? 'Create New Article' : 'Edit Article' }}
                </h3>
                <form wire:submit.prevent="{{ $isCreating ? 'createNews' : 'saveNews' }}" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Title</label>
                        <input type="text" wire:model="title" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('title') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Category</label>
                        <input type="text" wire:model="category" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g. Press Release, General, Update">
                        @error('category') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Body Content</label>
                        <textarea wire:model="body" rows="6" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500"></textarea>
                        @error('body') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Feature Image</label>
                        @if($imagePath && !$image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-32 rounded-lg object-cover border border-zinc-200 dark:border-zinc-700" alt="Current Image">
                            </div>
                        @endif
                        <input type="file" wire:model="image" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('image') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Main Video</label>
                        @if($videoPath && !$video)
                            <div class="mb-2">
                                <video src="{{ asset('storage/' . $videoPath) }}" controls class="w-full h-32 rounded-lg object-cover border border-zinc-200 dark:border-zinc-700"></video>
                            </div>
                        @endif
                        <input type="file" wire:model="video" accept="video/*" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('video') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Additional Photos</label>
                        @if(!empty($existingPhotos))
                            <div class="mb-2 grid grid-cols-4 gap-2">
                                @foreach($existingPhotos as $photo)
                                    <img src="{{ asset('storage/' . $photo) }}" class="w-full h-16 rounded object-cover border border-zinc-200 dark:border-zinc-700" alt="Additional Photo">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" wire:model="multiplePhotos" multiple accept="image/*" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('multiplePhotos.*') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Additional Videos</label>
                        @if(!empty($existingVideos))
                            <div class="mb-2 grid grid-cols-2 gap-2">
                                @foreach($existingVideos as $vid)
                                    <video src="{{ asset('storage/' . $vid) }}" controls class="w-full h-24 rounded object-cover border border-zinc-200 dark:border-zinc-700"></video>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" wire:model="multipleVideos" multiple accept="video/*" class="w-full mt-1 px-3 py-1.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-xs text-zinc-900 dark:text-white focus:outline-none">
                        @error('multipleVideos.*') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_breaking" id="is_breaking" class="rounded text-green-600 focus:ring-green-500 bg-zinc-50 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700">
                        <label for="is_breaking" class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Mark as Breaking News</label>
                        @error('is_breaking') <span class="text-xs text-red-600 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase">Publish Date/Time</label>
                        <input type="datetime-local" wire:model="published_at" class="w-full mt-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-750 rounded-lg text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-green-500">
                        @error('published_at') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                            {{ $isCreating ? 'Publish Article' : 'Save Changes' }}
                        </button>
                        <button type="button" wire:click="closeDetails" class="py-2 px-4 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white rounded-lg text-sm font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-12 text-zinc-500 text-sm">
                    Select an article from the list or click <strong>New Article</strong> to create a new one.
                </div>
            @endif
        </div>
    </div>
</div>
