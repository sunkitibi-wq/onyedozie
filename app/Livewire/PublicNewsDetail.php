<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.frontend')]
class PublicNewsDetail extends Component
{
    public News $news;

    public function mount($id)
    {
        $this->news = News::findOrFail($id);
        
        // Ensure it's published
        if (!$this->news->published_at || $this->news->published_at > now()) {
            abort(404);
        }
    }

    public function render()
    {
        $latestNews = News::whereNotNull('published_at')
                          ->where('published_at', '<=', now())
                          ->where('id', '!=', $this->news->id)
                          ->orderBy('published_at', 'desc')
                          ->take(3)
                          ->get();

        return view('livewire.public-news-detail', [
            'latestNews' => $latestNews,
        ]);
    }
}
