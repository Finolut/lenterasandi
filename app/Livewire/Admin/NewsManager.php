<?php

namespace App\Livewire\Admin;

use App\Models\Informasi;
use Livewire\Component;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedCategory = 'Semua Kategori';
    public string $selectedStatus = 'Semua Status';
    public array $selectedIds = [];
    public bool $selectAll = false;

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->filteredNews->pluck('id')->all();
        } else {
            $this->selectedIds = [];
        }
    }

    public function toggleStatus(int $id): void
    {
        $informasi = Informasi::findOrFail($id);
        $informasi->update(['status' => $informasi->status === 'Publik' ? 'Disembunyikan' : 'Publik']);
    }

    public function deleteNews(int $id): void
    {
        $informasi = Informasi::findOrFail($id);
        if ($informasi->gambar_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($informasi->gambar_url)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($informasi->gambar_url);
        }
        $informasi->delete();
        $this->selectedIds = array_values(array_filter($this->selectedIds, fn($i) => (int) $i !== $id));
        \Illuminate\Support\Facades\Cache::forget('admin_news_categories');
        \Illuminate\Support\Facades\Cache::forget('admin_news_total');
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) return;

        $items = Informasi::whereIn('id', $this->selectedIds)->get();
        foreach ($items as $item) {
            if ($item->gambar_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->gambar_url)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->gambar_url);
            }
            $item->delete();
        }

        $this->selectedIds = [];
        $this->selectAll = false;
        \Illuminate\Support\Facades\Cache::forget('admin_news_categories');
        \Illuminate\Support\Facades\Cache::forget('admin_news_total');
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
    }

    public function getFilteredNewsProperty()
    {
        return Informasi::query()
            ->select(['id', 'judul', 'kategori', 'status', 'gambar', 'published_at', 'jumlah_dilihat', 'created_by', 'created_at'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('konten', 'like', '%' . $this->search . '%')
                        ->orWhere('kategori', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory !== 'Semua Kategori', fn ($query) => $query->where('kategori', 'like', '%' . $this->selectedCategory . '%'))
            ->when($this->selectedStatus !== 'Semua Status', fn ($query) => $query->where('status', $this->selectedStatus))
            ->latest()
            ->paginate(10);
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedSelectedCategory(): void { $this->resetPage(); }
    public function updatedSelectedStatus(): void { $this->resetPage(); }

    public function render()
    {
        $allCategories = \Illuminate\Support\Facades\Cache::remember('admin_news_categories', 300, function () {
            $cats = Informasi::whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->pluck('kategori')
                ->flatMap(function ($raw) {
                    return array_map('trim', explode(',', $raw));
                })
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();

            return !empty($cats) ? $cats : ['Pengumuman', 'Kegiatan', 'Informasi'];
        });

        $totalNews = \Illuminate\Support\Facades\Cache::remember('admin_news_total', 300, function () {
            return Informasi::count();
        });

        return view('livewire.admin.news-manager', [
            'filteredNews'  => $this->filteredNews,
            'totalNews'     => $totalNews,
            'allCategories' => $allCategories,
        ]);
    }
}

