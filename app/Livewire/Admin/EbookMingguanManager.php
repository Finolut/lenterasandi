<?php

namespace App\Livewire\Admin;

use App\Models\Ebook;
use Livewire\Component;
use Livewire\WithPagination;

class EbookMingguanManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedCategory = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function setMingguan(int $id): void
    {
        $ebook = Ebook::findOrFail($id);

        // Reset semua e-book mingguan yang lama
        Ebook::where('is_mingguan', true)->update(['is_mingguan' => false]);

        // Aktifkan e-book yang dipilih
        $ebook->is_mingguan = true;
        if ($ebook->status !== 'Publik') {
            $ebook->status = 'Publik';
        }
        $ebook->save();

        session()->flash('success', "E-Book \"{$ebook->judul}\" berhasil ditetapkan sebagai E-Book Mingguan di Beranda publik.");
    }

    public function unsetMingguan(int $id): void
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->is_mingguan = false;
        $ebook->save();

        session()->flash('success', "Status E-Book Mingguan untuk \"{$ebook->judul}\" berhasil dilepas. Beranda akan menampilkan e-book terbaru secara otomatis.");
    }

    public function render()
    {
        $currentMingguan = Ebook::where('is_mingguan', true)->first();

        $query = Ebook::query()->where('status', 'Publik');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'ilike', "%{$this->search}%")
                  ->orWhere('pengarang', 'ilike', "%{$this->search}%")
                  ->orWhere('penerbit', 'ilike', "%{$this->search}%");
            });
        }

        if ($this->selectedCategory) {
            $query->where('kategori', $this->selectedCategory);
        }

        $ebooks = $query->latest()->paginate(8);

        $categories = Ebook::whereNotNull('kategori')
            ->where('status', 'Publik')
            ->distinct()
            ->pluck('kategori');

        return view('livewire.admin.ebook-mingguan-manager', [
            'currentMingguan' => $currentMingguan,
            'ebooks'          => $ebooks,
            'categories'      => $categories,
        ]);
    }
}

