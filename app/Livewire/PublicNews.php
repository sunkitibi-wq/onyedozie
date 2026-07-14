<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.frontend')]
class PublicNews extends Component
{
    use WithPagination;

    public $category = '';

    public function setCategory($cat)
    {
        $this->category = $cat;
        $this->resetPage();
    }

    public function render()
    {
        $query = News::whereNotNull('published_at')
                     ->where('published_at', '<=', now());

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $newsItems = $query->orderBy('is_breaking', 'desc')
                           ->orderBy('published_at', 'desc')
                           ->paginate(12);

        $categories = News::select('category')
                          ->whereNotNull('category')
                          ->distinct()
                          ->pluck('category');

        return view('livewire.public-news', [
            'newsItems' => $newsItems,
            'categories' => $categories,
        ]);
    }
}
