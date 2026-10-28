<?php

namespace App\Livewire\Admin;

use App\Models\Ebook;
use Livewire\Component;
use Livewire\WithPagination;

class EbookManager extends Component
{
    use WithPagination;

    public string $search           = '';
    public string $selectedCategory = '';
    public string $selectedStatus   = '';
    public array  $selectedIds      = [];
    public bool   $selectAll        = false;

    /** Reset pagination saat filter berubah */
    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedSelectedCategory(): void { $this->resetPage(); }
    public function updatedSelectedStatus(): void   { $this->resetPage(); }

    public function updatedSelectAll(bool $value): void
    {
        $this->selectedIds = $value
            ? $this->getQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function toggleStatus(int $id): void
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->status = $ebook->status === 'Publik' ? 'Disembunyikan' : 'Publik';
        $ebook->save();
    }

    public function deleteEbook(int $id): void
    {
        $ebook = Ebook::findOrFail($id);

        // Hapus file dari storage jika ada
        if ($ebook->file_url && \Storage::disk('public')->exists($ebook->file_url)) {
            \Storage::disk('public')->delete($ebook->file_url);
        }
        if ($ebook->cover_url && \Storage::disk('public')->exists($ebook->cover_url)) {
            \Storage::disk('public')->delete($ebook->cover_url);
        }

        $ebook->delete();

        // Hapus dari selectedIds jika ada
        $this->selectedIds = array_values(
            array_filter($this->selectedIds, fn($i) => (int) $i !== $id)
        );

        \Illuminate\Support\Facades\Cache::forget('admin_ebook_categories');
        \Illuminate\Support\Facades\Cache::forget('admin_ebook_total');
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) return;

        $ebooks = Ebook::whereIn('id', $this->selectedIds)->get();
        foreach ($ebooks as $ebook) {
            if ($ebook->file_url && \Storage::disk('public')->exists($ebook->file_url)) {
                \Storage::disk('public')->delete($ebook->file_url);
            }
            if ($ebook->cover_url && \Storage::disk('public')->exists($ebook->cover_url)) {
                \Storage::disk('public')->delete($ebook->cover_url);
            }
            $ebook->delete();
        }

        $this->selectedIds = [];
        $this->selectAll   = false;

        \Illuminate\Support\Facades\Cache::forget('admin_ebook_categories');
        \Illuminate\Support\Facades\Cache::forget('admin_ebook_total');
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
    }

    private function getQuery()
    {
        return Ebook::query()
            ->select(['id', 'judul', 'kategori', 'pengarang', 'status', 'cover_url', 'file_url', 'jumlah_unduhan', 'created_at'])
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('judul', 'ilike', '%'.$this->search.'%')
                       ->orWhere('kategori', 'ilike', '%'.$this->search.'%')
                       ->orWhere('pengarang', 'ilike', '%'.$this->search.'%')
                )
            )
            ->when($this->selectedCategory, fn($q) =>
                $q->where(function ($q2) {
                    $c = $this->selectedCategory;
                    $q2->where('kategori', $c)
                       ->orWhere('kategori', 'like', "{$c},%")
                       ->orWhere('kategori', 'like', "%, {$c},%")
                       ->orWhere('kategori', 'like', "%, {$c}")
                       ->orWhere('kategori', 'like', "%,{$c},%")
                       ->orWhere('kategori', 'like', "%,{$c}");
                })
            )
            ->when($this->selectedStatus, fn($q) =>
                $q->where('status', $this->selectedStatus)
            )
            ->latest();
    }

    public function render()
    {
        $ebooks     = $this->getQuery()->paginate(10);
        $totalEbook = \Illuminate\Support\Facades\Cache::remember('admin_ebook_total', 300, fn() => Ebook::count());

        $allCategories = \Illuminate\Support\Facades\Cache::remember('admin_ebook_categories', 300, function () {
            $rawCategories = Ebook::whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori');
            $cats = [];
            foreach ($rawCategories as $raw) {
                foreach (explode(',', $raw) as $c) {
                    $c = trim($c);
                    if ($c !== '' && !in_array($c, $cats, true)) {
                        $cats[] = $c;
                    }
                }
            }
            natcasesort($cats);
            return array_values($cats);
        });

        return view('livewire.admin.ebook-manager', [
            'ebooks'      => $ebooks,
            'totalEbook'  => $totalEbook,
            'categories'  => $allCategories,
        ]);
    }
}
