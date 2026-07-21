<?php

namespace App\Livewire;

use App\Models\WhatsappBroadcast;
use App\Models\ScheduledPost;
use App\Models\Lga;
use App\Jobs\SendWhatsappBroadcastJob;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class CommunicationsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $activeTab = 'whatsapp';
    public $socialViewMode = 'feed'; // feed or calendar

    public $calendarMonth;
    public $calendarYear;

    // WhatsApp Broadcast Form Fields
    public $broadcastMessage = '';
    public $broadcastMedia;
    public $broadcastAudienceType = 'all';
    public $broadcastLgaId = '';
    public $broadcastRole = '';
    public $broadcastCustomPhones = '';

    // Social Post Form Fields
    public $postContent = '';
    public $postMedia = []; // temporary uploads
    public $postPlatforms = []; // array of selected platforms
    public $postScheduledAt = '';

    // LGA and Role lists
    public $lgas = [];
    public $roles = [];

    public function mount()
    {
        $this->lgas = Lga::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
        $this->postScheduledAt = now()->addHours(1)->format('Y-m-d\TH:i'); // default to 1 hour from now

        $this->calendarMonth = now()->month;
        $this->calendarYear = now()->year;
    }

    public function sendWhatsappBroadcast()
    {
        $this->validate([
            'broadcastMessage' => 'required|string',
            'broadcastMedia' => 'nullable|image|max:10240', // max 10MB
            'broadcastAudienceType' => 'required|in:all,lga,role,custom',
            'broadcastLgaId' => 'required_if:broadcastAudienceType,lga',
            'broadcastRole' => 'required_if:broadcastAudienceType,role',
            'broadcastCustomPhones' => 'required_if:broadcastAudienceType,custom',
        ]);

        $mediaPath = null;
        if ($this->broadcastMedia) {
            $mediaPath = $this->broadcastMedia->store('broadcasts', 'public');
        }

        $filter = [];
        if ($this->broadcastAudienceType === 'lga') {
            $filter['lga_id'] = $this->broadcastLgaId;
        } elseif ($this->broadcastAudienceType === 'role') {
            $filter['role'] = $this->broadcastRole;
        } elseif ($this->broadcastAudienceType === 'custom') {
            $phones = preg_split('/[\s,]+/', $this->broadcastCustomPhones);
            $filter['phones'] = array_filter(array_map('trim', $phones));
        }

        $broadcast = WhatsappBroadcast::create([
            'message' => $this->broadcastMessage,
            'media_path' => $mediaPath,
            'audience_type' => $this->broadcastAudienceType,
            'audience_filter' => $filter,
            'status' => 'pending',
        ]);

        SendWhatsappBroadcastJob::dispatch($broadcast);

        // Reset fields
        $this->broadcastMessage = '';
        $this->broadcastMedia = null;
        $this->broadcastAudienceType = 'all';
        $this->broadcastLgaId = '';
        $this->broadcastRole = '';
        $this->broadcastCustomPhones = '';

        session()->flash('message', 'WhatsApp Broadcast has been queued for sending.');
    }

    public function scheduleSocialPost()
    {
        $this->validate([
            'postContent' => 'required|string',
            'postMedia' => 'nullable|array|max:4',
            'postMedia.*' => 'image|max:10240',
            'postPlatforms' => 'required|array|min:1',
            'postScheduledAt' => 'required|date|after:now',
        ]);

        $mediaPaths = [];
        if ($this->postMedia) {
            foreach ($this->postMedia as $media) {
                $mediaPaths[] = $media->store('social_posts', 'public');
            }
        }

        ScheduledPost::create([
            'content' => $this->postContent,
            'media_paths' => $mediaPaths,
            'platforms' => $this->postPlatforms,
            'scheduled_at' => $this->postScheduledAt,
            'status' => 'scheduled',
        ]);

        // Reset fields
        $this->postContent = '';
        $this->postMedia = [];
        $this->postPlatforms = [];
        $this->postScheduledAt = now()->addHours(1)->format('Y-m-d\TH:i');

        session()->flash('message', 'Social Media Post has been scheduled.');
    }

    public function resendBroadcast(WhatsappBroadcast $broadcast)
    {
        $broadcast->update([
            'status' => 'pending',
            'sent_count' => 0,
            'delivered_count' => 0,
            'failed_count' => 0,
        ]);

        SendWhatsappBroadcastJob::dispatch($broadcast);

        session()->flash('message', 'WhatsApp Broadcast has been re-queued for sending.');
    }

    public function deleteScheduledPost(ScheduledPost $post)
    {
        if ($post->status === 'scheduled') {
            $post->delete();
            session()->flash('message', 'Scheduled post has been deleted.');
        }
    }

    public function nextMonth()
    {
        if ($this->calendarMonth == 12) {
            $this->calendarMonth = 1;
            $this->calendarYear++;
        } else {
            $this->calendarMonth++;
        }
    }

    public function prevMonth()
    {
        if ($this->calendarMonth == 1) {
            $this->calendarMonth = 12;
            $this->calendarYear--;
        } else {
            $this->calendarMonth--;
        }
    }

    public function getCalendarDaysProperty()
    {
        $days = [];
        $firstDayOfMonth = \Carbon\Carbon::createFromDate($this->calendarYear, $this->calendarMonth, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        
        // Find what day of the week the month starts on (0 = Sunday)
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek;
        
        // Pad beginning of calendar with previous month's days
        if ($startDayOfWeek > 0) {
            $prevMonth = $firstDayOfMonth->copy()->subMonth();
            $prevMonthDays = $prevMonth->daysInMonth;
            
            for ($i = $startDayOfWeek - 1; $i >= 0; $i--) {
                $date = $prevMonth->copy()->day($prevMonthDays - $i);
                $days[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->day,
                    'is_current_month' => false,
                    'posts' => []
                ];
            }
        }
        
        // Add current month's days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = \Carbon\Carbon::createFromDate($this->calendarYear, $this->calendarMonth, $day);
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $day,
                'is_current_month' => true,
                'posts' => []
            ];
        }
        
        // Pad end of calendar with next month's days to complete the grid (usually 42 cells total, or just fill the last week)
        $remainingCells = 42 - count($days);
        // If it can fit in 35 cells, do 35
        if (count($days) <= 35) {
            $remainingCells = 35 - count($days);
        }

        if ($remainingCells > 0) {
            $nextMonth = $firstDayOfMonth->copy()->addMonth();
            for ($day = 1; $day <= $remainingCells; $day++) {
                $date = $nextMonth->copy()->day($day);
                $days[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $day,
                    'is_current_month' => false,
                    'posts' => []
                ];
            }
        }
        
        // Fetch posts for the entire visible grid
        $startDate = collect($days)->first()['date'];
        $endDate = collect($days)->last()['date'];
        
        $posts = ScheduledPost::whereBetween('scheduled_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->get();
        
        // Attach posts to respective days
        foreach ($days as &$dayObj) {
            $dayDate = $dayObj['date'];
            $dayObj['posts'] = $posts->filter(function($post) use ($dayDate) {
                return $post->scheduled_at->format('Y-m-d') === $dayDate;
            })->values()->all();
        }
        
        return $days;
    }

    public function render()
    {
        return view('livewire.communications-manager', [
            'broadcasts' => WhatsappBroadcast::latest()->paginate(10, ['*'], 'broadcastPage'),
            'posts' => ScheduledPost::latest()->paginate(10, ['*'], 'postPage'),
            'calendarDays' => $this->calendarDays,
        ]);
    }
}
