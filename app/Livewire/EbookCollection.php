<?php

namespace App\Livewire;

use App\Models\Ebook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['active' => 'Koleksi E-Book', 'title' => 'Koleksi E-Book - Lentera Sandi'])]
class EbookCollection extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedCategory = 'Semua';

    public string $sortBy = 'Terbaru';

    public string $viewMode = 'grid';

    public bool $isSearchOpen = false;

    public array $categories = [];

    public array $sortOptions = [
        'Terbaru',
        'Terlama',
        'Judul A-Z',
        'Paling Banyak Dibaca',
    ];

    protected $paginationTheme = 'tailwind';

    public function getAvailableCategories(): array
    {
        return Cache::remember('ebook_public_categories', now()->addMinutes(10), function (): array {
            $rawCategories = Ebook::where('status', 'Publik')
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->pluck('kategori');

            $categories = [];
            foreach ($rawCategories as $raw) {
                foreach (explode(',', $raw) as $c) {
                    $c = trim($c);
                    if ($c !== '' && ! in_array($c, $categories, true)) {
                        $categories[] = $c;
                    }
                }
            }

            natcasesort($categories);

            return array_values($categories);
        });
    }

    public function mount(?string $category = null): void
    {
        $available = $this->getAvailableCategories();
        $this->categories = array_merge(['Semua'], $available);

        if ($category) {
            $catTrimmed = strtolower(trim($category));
            foreach ($available as $c) {
                if (Str::slug($c) === $catTrimmed || strcasecmp($c, $category) === 0) {
                    $this->selectedCategory = $c;
                    break;
                }
            }
        }

        if (request()->filled('search')) {
            $this->search = trim(request('search'));
            $this->isSearchOpen = true;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
        $this->resetPage();
    }

    public function toggleSearch(): void
    {
        $this->isSearchOpen = ! $this->isSearchOpen;
        if (! $this->isSearchOpen) {
            $this->search = '';
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->selectedCategory = 'Semua';
        $this->search = '';
        $this->isSearchOpen = false;
        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = Ebook::query()->where('status', 'Publik');

        if (! empty(trim($this->search))) {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'ilike', "%{$s}%")
                    ->orWhere('deskripsi', 'ilike', "%{$s}%")
                    ->orWhere('pengarang', 'ilike', "%{$s}%")
                    ->orWhere('kategori', 'ilike', "%{$s}%");
            });
        }

        if ($this->selectedCategory !== 'Semua') {
            $cat = $this->selectedCategory;
            $query->where(function ($q) use ($cat) {
                $q->where('kategori', $cat)
                    ->orWhere('kategori', 'like', "{$cat},%")
                    ->orWhere('kategori', 'like', "%, {$cat},%")
                    ->orWhere('kategori', 'like', "%, {$cat}")
                    ->orWhere('kategori', 'like', "%,{$cat},%")
                    ->orWhere('kategori', 'like', "%,{$cat}");
            });
        }

        match ($this->sortBy) {
            'Terlama' => $query->oldest(),
            'Judul A-Z' => $query->orderBy('judul'),
            'Paling Banyak Dibaca' => $query->orderByDesc('jumlah_dibaca'),
            'Paling Banyak Diunduh' => $query->orderByDesc('jumlah_dibaca'),
            default => $query->latest(),
        };

        return $query;
    }

    public function getTotalCountProperty(): int
    {
        return Cache::remember('ebook_total_count', 60, function () {
            return Ebook::where('status', 'Publik')->count();
        });
    }

    public function render()
    {
        $availableCategories = $this->getAvailableCategories();
        $ebooks = $this->getQuery()->paginate(18);

        return view('pages.ebooks.index', [
            'ebooks' => $ebooks,
            'totalCount' => $this->totalCount,
            'navCategories' => $availableCategories,
        ]);
    }
}
