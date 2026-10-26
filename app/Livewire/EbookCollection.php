<?php

namespace App\Livewire;

use App\Models\Ebook;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['active' => 'Koleksi E-Book', 'title' => 'Koleksi E-Book - Lentera Sandi', 'hideNavbar' => true])]
class EbookCollection extends Component
{
    use WithPagination;

    public string $search           = '';
    public string $selectedCategory = 'Semua';
    public string $sortBy           = 'Terbaru';
    public string $viewMode         = 'grid';
    public bool $isSearchOpen       = false;

    public array $categories = [];

    public array $sortOptions = [
        'Terbaru',
        'Terlama',
        'Judul A-Z',
        'Paling Banyak Diunduh',
    ];

    protected $paginationTheme = 'tailwind';

    public function getAvailableCategories(): array
    {
        $rawCategories = Ebook::where('status', 'Publik')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori');

        $categories = [];
        foreach ($rawCategories as $raw) {
            foreach (explode(',', $raw) as $c) {
                $c = trim($c);
                if ($c !== '' && !in_array($c, $categories, true)) {
                    $categories[] = $c;
                }
            }
        }

        natcasesort($categories);

        return array_values($categories);
    }

    public function mount(?string $category = null): void
    {
        $available = $this->getAvailableCategories();
        $this->categories = array_merge(['Semua'], $available);

        if ($category) {
            $catTrimmed = strtolower(trim($category));
            foreach ($available as $c) {
                if (\Illuminate\Support\Str::slug($c) === $catTrimmed || strcasecmp($c, $category) === 0) {
                    $this->selectedCategory = $c;
                    break;
                }
            }
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
        $this->isSearchOpen = !$this->isSearchOpen;
        if (!$this->isSearchOpen) {
            $this->search = '';
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->selectedCategory = 'Semua';
        $this->search           = '';
        $this->isSearchOpen     = false;
        $this->resetPage();
    }

    private function getQuery(): \Illuminate\Database\Eloquent\Builder
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
            'Terlama'               => $query->oldest(),
            'Judul A-Z'             => $query->orderBy('judul'),
            'Paling Banyak Diunduh' => $query->orderByDesc('jumlah_unduhan'),
            default                 => $query->latest(),
        };

        return $query;
    }

    public function getTotalCountProperty(): int
    {
        return \Illuminate\Support\Facades\Cache::remember('ebook_total_count', 60, function () {
            return Ebook::where('status', 'Publik')->count();
        });
    }

    public function render()
    {
        $isShelfMode = ($this->selectedCategory === 'Semua' && empty(trim($this->search)));
        $shelves = [];
        $availableCategories = $this->getAvailableCategories();

        if ($isShelfMode) {
            $builtShelves = [];

            foreach ($availableCategories as $cat) {
                $books = Ebook::where('status', 'Publik')
                    ->where(function ($q) use ($cat) {
                        $q->where('kategori', $cat)
                          ->orWhere('kategori', 'like', "{$cat},%")
                          ->orWhere('kategori', 'like', "%, {$cat},%")
                          ->orWhere('kategori', 'like', "%, {$cat}")
                          ->orWhere('kategori', 'like', "%,{$cat},%")
                          ->orWhere('kategori', 'like', "%,{$cat}");
                    })
                    ->latest()
                    ->take(12)
                    ->get();

                $count = Ebook::where('status', 'Publik')
                    ->where(function ($q) use ($cat) {
                        $q->where('kategori', $cat)
                          ->orWhere('kategori', 'like', "{$cat},%")
                          ->orWhere('kategori', 'like', "%, {$cat},%")
                          ->orWhere('kategori', 'like', "%, {$cat}")
                          ->orWhere('kategori', 'like', "%,{$cat},%")
                          ->orWhere('kategori', 'like', "%,{$cat}");
                    })
                    ->count();

                if ($count > 0) {
                    $builtShelves[] = [
                        'name'  => $cat,
                        'count' => $count,
                        'books' => $books,
                    ];
                }
            }

            // Fallback: If no category shelves built but public books exist
            if (empty($builtShelves)) {
                $allBooks = Ebook::where('status', 'Publik')->latest()->take(12)->get();
                if ($allBooks->isNotEmpty()) {
                    $builtShelves[] = [
                        'name'  => 'Koleksi Terbaru',
                        'count' => $allBooks->count(),
                        'books' => $allBooks,
                    ];
                }
            }

            $shelves = $builtShelves;
        }

        // Only run paginated search/filter query when outside of shelf mode
        $ebooks = $isShelfMode ? null : $this->getQuery()->paginate(18);

        return view('pages.ebooks.index', [
            'isShelfMode'   => $isShelfMode,
            'shelves'       => $shelves,
            'ebooks'        => $ebooks,
            'totalCount'    => $this->totalCount,
            'navCategories' => $availableCategories,
        ]);
    }
}
