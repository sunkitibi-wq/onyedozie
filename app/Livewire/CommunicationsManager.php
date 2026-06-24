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

    public function render()
    {
        return view('livewire.communications-manager', [
            'broadcasts' => WhatsappBroadcast::latest()->paginate(10, ['*'], 'broadcastPage'),
            'posts' => ScheduledPost::latest()->paginate(10, ['*'], 'postPage'),
        ]);
    }
}
