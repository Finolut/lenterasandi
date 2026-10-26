<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Informasi;

#[Layout('components.layouts.app', ['active' => 'Informasi', 'title' => 'Informasi & Berita - Lentera Sandi', 'hideNavbar' => true])]
class InformationList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'Terbaru';

    protected $paginationTheme = 'tailwind';

    public function mount(?string $category = null): void
    {
        if ($category) {
            $this->search = str_replace('-', ' ', $category);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->sortBy = 'Terbaru';
        $this->resetPage();
    }

    public function getNewsListProperty()
    {
        $query = Informasi::where('status', 'Publik');

        if (! empty(trim($this->search))) {
            $s = '%' . strtolower(trim($this->search)) . '%';
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(judul) like ?', [$s])
                  ->orWhereRaw('LOWER(ringkasan) like ?', [$s])
                  ->orWhereRaw('LOWER(konten) like ?', [$s])
                  ->orWhereRaw('LOWER(kategori) like ?', [$s]);
            });
        }

        if ($this->sortBy === 'Terlama') {
            $query->oldest('published_at');
        } else {
            $query->latest('published_at');
        }

        return $query->paginate(8);
    }

    public function render()
    {
        return view('pages.informasi.index', [
            'newsList' => $this->newsList,
        ]);
    }
}

