<div class="space-y-6">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-zinc-900 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button wire:click="$set('activeTab', 'whatsapp')" 
            class="py-3 px-6 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $activeTab === 'whatsapp' ? 'border-green-600 text-green-600 dark:text-green-400 dark:border-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
            WhatsApp Broadcast
        </button>
        <button wire:click="$set('activeTab', 'social')" 
            class="py-3 px-6 text-sm font-semibold border-b-2 transition-colors duration-150 {{ $activeTab === 'social' ? 'border-green-600 text-green-600 dark:text-green-400 dark:border-green-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }}">
            Social Media Scheduler
        </button>
    </div>

    @if($activeTab === 'whatsapp')
        <!-- WhatsApp Broadcast Module -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Compose Form -->
            <div class="lg:col-span-1 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">New Broadcast Alert</h3>
                <form wire:submit.prevent="sendWhatsappBroadcast" class="space-y-4">
                    <!-- Message Body -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">MESSAGE CONTENT</label>
                        <textarea wire:model="broadcastMessage" rows="5" 
                            class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:text-white"
                            placeholder="Enter mass mobilization message..."></textarea>
                        @error('broadcastMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Media Upload -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">MEDIA ATTACHMENT (IMAGE)</label>
                        <input type="file" wire:model="broadcastMedia" 
                            class="block w-full text-xs text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 dark:file:bg-zinc-800 file:text-zinc-700 dark:file:text-zinc-300 hover:file:bg-zinc-200" />
                        @error('broadcastMedia') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        @if ($broadcastMedia)
                            <div class="mt-2 text-xs text-zinc-500 flex items-center gap-2">
                                <span>Preview:</span>
                                <img src="{{ $broadcastMedia->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-md border border-zinc-250" />
                            </div>
                        @endif
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">TARGET AUDIENCE</label>
                        <select wire:model.live="broadcastAudienceType" 
                            class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none dark:text-white">
                            <option value="all">All Registered Members</option>
                            <option value="lga">Filter By LGA</option>
                            <option value="role">Filter By Role</option>
                            <option value="custom">Custom Phone Numbers</option>
                        </select>
                    </div>

                    <!-- Dynamic Filtering -->
                    @if($broadcastAudienceType === 'lga')
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">SELECT LGA</label>
                            <select wire:model="broadcastLgaId" 
                                class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none dark:text-white">
                                <option value="">Select LGA...</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                @endforeach
                            </select>
                            @error('broadcastLgaId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($broadcastAudienceType === 'role')
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">SELECT ROLE</label>
                            <select wire:model="broadcastRole" 
                                class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none dark:text-white">
                                <option value="">Select Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('broadcastRole') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($broadcastAudienceType === 'custom')
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">PHONE NUMBERS (COMMA SEPARATED)</label>
                            <textarea wire:model="broadcastCustomPhones" rows="3" 
                                class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none dark:text-white"
                                placeholder="e.g. 08012345678, 08087654321"></textarea>
                            @error('broadcastCustomPhones') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <button type="submit" 
                        class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition-colors duration-150">
                        Dispatch Broadcast
                    </button>
                </form>
            </div>

            <!-- Right Side: Broadcast History -->
            <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Broadcast Dispatch History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-zinc-500 dark:text-zinc-400">
                        <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300">
                            <tr>
                                <th class="px-4 py-3">Message</th>
                                <th class="px-4 py-3">Target</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Delivery Stats</th>
                                <th class="px-4 py-3">Sent At</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse($broadcasts as $b)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                                    <td class="px-4 py-3 max-w-xs truncate">
                                        {{ $b->message }}
                                        @if($b->media_path)
                                            <span class="block text-[10px] text-green-600 font-semibold mt-0.5">📎 Attachment Included</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs capitalize">
                                        {{ $b->audience_type }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($b->status === 'completed')
                                            <span class="px-2 py-0.5 bg-green-150 text-green-800 rounded-full font-bold dark:bg-green-900/30 dark:text-green-400">Sent</span>
                                        @elseif($b->status === 'sending')
                                            <span class="px-2 py-0.5 bg-blue-150 text-blue-800 rounded-full font-bold dark:bg-blue-900/30 dark:text-blue-400">Sending...</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-amber-150 text-amber-800 rounded-full font-bold dark:bg-amber-900/30 dark:text-amber-400">{{ $b->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-green-600" title="Delivered">✔️ {{ $b->delivered_count }}</span>
                                            <span class="text-red-500" title="Failed">❌ {{ $b->failed_count }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ $b->created_at->format('M d, H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-right">
                                        <button wire:click="resendBroadcast({{ $b->id }})" 
                                            class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 rounded text-xs font-semibold transition-colors duration-150">
                                            Resend
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-zinc-500 italic">No broadcasts sent yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $broadcasts->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- Social Media Scheduler Module -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Compose & Schedule Form -->
            <div class="lg:col-span-1 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm h-fit">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Schedule Social Post</h3>
                <form wire:submit.prevent="scheduleSocialPost" class="space-y-4">
                    <!-- Post Content -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">POST CONTENT</label>
                        <textarea wire:model="postContent" rows="5" 
                            class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 dark:text-white"
                            placeholder="Draft your campaign social post..."></textarea>
                        @error('postContent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Images Upload (Max 4) -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">ATTACH IMAGES (MAX 4)</label>
                        <input type="file" wire:model="postMedia" multiple
                            class="block w-full text-xs text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 dark:file:bg-zinc-800 file:text-zinc-700 dark:file:text-zinc-300 hover:file:bg-zinc-200" />
                        @error('postMedia') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        @if ($postMedia)
                            <div class="mt-2 grid grid-cols-4 gap-2">
                                @foreach($postMedia as $media)
                                    <img src="{{ $media->temporaryUrl() }}" class="h-12 w-12 object-cover rounded-md border border-zinc-250" />
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Target Platforms -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">PUBLISH PLATFORMS</label>
                        <div class="space-y-2 mt-2">
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                                <input type="checkbox" wire:model="postPlatforms" value="facebook" class="rounded text-green-600 border-zinc-300 dark:border-zinc-700 focus:ring-green-500">
                                <span>Facebook</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                                <input type="checkbox" wire:model="postPlatforms" value="instagram" class="rounded text-green-600 border-zinc-300 dark:border-zinc-700 focus:ring-green-500">
                                <span>Instagram</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300 cursor-pointer">
                                <input type="checkbox" wire:model="postPlatforms" value="x" class="rounded text-green-600 border-zinc-300 dark:border-zinc-700 focus:ring-green-500">
                                <span>X (formerly Twitter)</span>
                            </label>
                        </div>
                        @error('postPlatforms') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Schedule Time -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">SCHEDULE DATE & TIME</label>
                        <input type="datetime-local" wire:model="postScheduledAt" 
                            class="w-full px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none dark:text-white" />
                        @error('postScheduledAt') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" 
                        class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition-colors duration-150">
                        Schedule Social Post
                    </button>
                </form>
            </div>

            <!-- Right Side: Scheduled & Posted Timeline -->
            <div class="lg:col-span-2 p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white">Content Calendar & Posts Feed</h3>
                    <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 rounded-lg p-1">
                        <button wire:click="$set('socialViewMode', 'feed')" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $socialViewMode === 'feed' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' }}">Feed</button>
                        <button wire:click="$set('socialViewMode', 'calendar')" class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $socialViewMode === 'calendar' ? 'bg-white dark:bg-zinc-700 shadow-sm text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400' }}">Calendar</button>
                    </div>
                </div>

                @if($socialViewMode === 'feed')
                    <div class="space-y-4">
                        @forelse($posts as $post)
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-1 space-y-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @foreach($post->platforms as $plat)
                                            <span class="px-2 py-0.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-[10px] uppercase font-bold text-zinc-600 dark:text-zinc-400 rounded">
                                                {{ $plat }}
                                            </span>
                                        @endforeach
                                        <span class="text-xs text-zinc-500">
                                            Scheduled for: {{ $post->scheduled_at->format('M d, H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-zinc-800 dark:text-zinc-200 font-medium whitespace-pre-wrap">{{ $post->content }}</p>
                                    
                                    @if(!empty($post->media_paths))
                                        <div class="flex gap-1.5 flex-wrap mt-2">
                                            @foreach($post->media_paths as $path)
                                                <img src="{{ asset('storage/' . $path) }}" class="h-12 w-12 object-cover rounded-md border border-zinc-200" />
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($post->error_message)
                                        <span class="block text-xs text-red-500 font-semibold mt-1">⚠️ {{ $post->error_message }}</span>
                                    @endif
                                </div>

                                <div class="flex flex-col justify-between items-end gap-2 text-right">
                                    <!-- Status Badge -->
                                    @if($post->status === 'posted')
                                        <span class="px-2.5 py-0.5 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-full">Published</span>
                                    @elseif($post->status === 'scheduled')
                                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full">Scheduled</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold rounded-full capitalize">{{ $post->status }}</span>
                                    @endif

                                    @if($post->status === 'scheduled')
                                        <button wire:click="deleteScheduledPost({{ $post->id }})" wire:confirm="Are you sure you want to delete this scheduled post?" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-semibold mt-2 transition-colors duration-150">
                                            Delete Post
                                        </button>
                                    @endif

                                    <!-- Engagement Stats (only visible if published) -->
                                    @if($post->status === 'posted')
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 space-y-0.5 font-semibold mt-2">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <span>Likes:</span>
                                                <span class="text-zinc-800 dark:text-zinc-200">{{ $post->engagement_likes }}</span>
                                            </div>
                                            <div class="flex items-center justify-end gap-1.5">
                                                <span>Shares:</span>
                                                <span class="text-zinc-800 dark:text-zinc-200">{{ $post->engagement_shares }}</span>
                                            </div>
                                            <div class="flex items-center justify-end gap-1.5">
                                                <span>Reach:</span>
                                                <span class="text-zinc-800 dark:text-zinc-200">{{ $post->engagement_reach }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-zinc-500 italic">No social media posts scheduled.</div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                @else
                    <!-- Calendar View -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-4 px-2">
                            <button wire:click="prevMonth" class="p-1.5 text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <h4 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">
                                {{ \Carbon\Carbon::createFromDate($calendarYear, $calendarMonth, 1)->format('F Y') }}
                            </h4>
                            <button wire:click="nextMonth" class="p-1.5 text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Days of Week -->
                        <div class="grid grid-cols-7 gap-px bg-zinc-200 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 rounded-t-lg overflow-hidden">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dow)
                                <div class="bg-zinc-50 dark:bg-zinc-900 py-2 text-center text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                    {{ $dow }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-px bg-zinc-200 dark:bg-zinc-800 border-x border-b border-zinc-200 dark:border-zinc-800 rounded-b-lg overflow-hidden">
                            @foreach($this->calendarDays as $dayObj)
                                <div class="bg-white dark:bg-zinc-900 min-h-[100px] p-2 flex flex-col gap-1 {{ !$dayObj['is_current_month'] ? 'opacity-40 bg-zinc-50/50 dark:bg-zinc-900/50' : '' }}">
                                    <span class="text-xs font-bold {{ $dayObj['date'] === now()->format('Y-m-d') ? 'text-green-600 dark:text-green-400' : 'text-zinc-700 dark:text-zinc-300' }}">
                                        {{ $dayObj['day'] }}
                                    </span>
                                    
                                    <div class="flex flex-col gap-1 overflow-y-auto max-h-[80px] pr-1 scrollbar-thin">
                                        @foreach($dayObj['posts'] as $p)
                                            <div class="text-[10px] leading-tight p-1 rounded-md border border-zinc-100 dark:border-zinc-700/50 {{ $p->status === 'scheduled' ? 'bg-blue-50/50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-green-50/50 text-green-700 dark:bg-green-900/20 dark:text-green-300' }}" title="{{ $p->content }}">
                                                <div class="font-bold mb-0.5 truncate">{{ $p->scheduled_at->format('H:i') }}</div>
                                                <div class="flex gap-0.5">
                                                    @foreach($p->platforms as $plat)
                                                        <span class="inline-block truncate uppercase border border-current rounded-[3px] px-[2px] opacity-80" style="font-size: 7px;">{{ substr($plat, 0, 2) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
