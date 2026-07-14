<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class NewsManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    
    // Details/Edit/Create Mode
    public $selectedNewsId;
    public $isCreating = false;
    
    public $title, $body, $category, $is_breaking, $published_at;
    public $image;
    public $imagePath;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'required|string|max:255',
            'is_breaking' => 'boolean',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    public function selectNews($id)
    {
        $this->isCreating = false;
        $news = News::find($id);
        if ($news) {
            $this->selectedNewsId = $news->id;
            $this->title = $news->title;
            $this->body = $news->body;
            $this->category = $news->category;
            $this->is_breaking = $news->is_breaking;
            $this->published_at = $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : null;
            $this->imagePath = $news->image_path;
        }
    }

    public function startCreate()
    {
        $this->closeDetails();
        $this->isCreating = true;
        $this->category = 'General';
        $this->is_breaking = false;
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function closeDetails()
    {
        $this->reset([
            'selectedNewsId',
            'isCreating',
            'title',
            'body',
            'category',
            'is_breaking',
            'published_at',
            'image',
            'imagePath'
        ]);
        $this->resetErrorBag();
    }

    public function createNews()
    {
        $this->validate();

        $path = null;
        if ($this->image) {
            $path = $this->image->store('news', 'public');
        }

        News::create([
            'title' => $this->title,
            'body' => $this->body,
            'category' => $this->category,
            'is_breaking' => $this->is_breaking ? true : false,
            'published_at' => $this->published_at ? Carbon::parse($this->published_at) : null,
            'image_path' => $path,
        ]);

        session()->flash('message', 'News article created successfully.');
        $this->closeDetails();
    }

    public function saveNews()
    {
        $this->validate();

        $news = News::find($this->selectedNewsId);
        if ($news) {
            $path = $news->image_path;
            if ($this->image) {
                $path = $this->image->store('news', 'public');
            }

            $news->update([
                'title' => $this->title,
                'body' => $this->body,
                'category' => $this->category,
                'is_breaking' => $this->is_breaking ? true : false,
                'published_at' => $this->published_at ? Carbon::parse($this->published_at) : null,
                'image_path' => $path,
            ]);

            session()->flash('message', 'News article updated successfully.');
            $this->closeDetails();
        }
    }

    public function deleteNews($id)
    {
        $news = News::find($id);
        if ($news) {
            $news->delete();
            session()->flash('message', 'News article deleted successfully.');
            if ($this->selectedNewsId == $id) {
                $this->closeDetails();
            }
        }
    }

    public function render()
    {
        $query = News::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('body', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $categories = News::select('category')->whereNotNull('category')->distinct()->pluck('category');

        return view('livewire.news-management', [
            'newsItems' => $query->latest('published_at')->paginate(15),
            'categories' => $categories,
        ]);
    }
}
