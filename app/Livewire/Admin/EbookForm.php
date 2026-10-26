<?php

namespace App\Livewire\Admin;

use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EbookForm extends Component
{
    use WithFileUploads;

    public string $mode    = 'add'; // 'add' or 'edit'
    public ?int   $ebookId = null;

    // Form fields
    public string  $judul              = '';
    public array   $selectedCategories = [];
    public string  $customCategoryInput = '';
    public array   $suggestedCategories = [];
    public string  $deskripsi          = '';
    public string  $pengarang          = 'Diskominfo Provinsi Jawa Timur';
    public string  $penerbit           = '';
    public string  $tahun              = '';
    public string  $status             = 'Publik';

    // File upload (Livewire temporary files)
    public $filePdf   = null;
    public $fileCover = null;
    public string $coverSourceType = 'upload'; // 'upload' or 'url'
    public string $coverUrlInput = '';

    // Existing file paths (untuk mode edit)
    public ?string $existingPdfUrl   = null;
    public ?string $existingCoverUrl = null;

    public string  $successMessage = '';

    public function mount(string $mode = 'add', ?int $id = null): void
    {
        $this->mode    = $mode;
        $this->ebookId = $id;
        $this->tahun   = (string) date('Y');

        $defaults = ['Keamanan Informasi', 'Keamanan Siber', 'Literasi Digital', 'Panduan', 'Modul'];
        $existingRaw = Ebook::whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori');
        $all = [];
        foreach ($existingRaw as $raw) {
            foreach (explode(',', $raw) as $c) {
                $c = trim($c);
                if ($c !== '' && !in_array($c, $all, true)) {
                    $all[] = $c;
                }
            }
        }
        $this->suggestedCategories = array_values(array_unique(array_merge($defaults, $all)));

        if ($mode === 'edit' && $id) {
            $ebook = Ebook::findOrFail($id);

            $this->judul              = $ebook->judul;
            $this->selectedCategories = $ebook->kategori_list;
            $this->deskripsi          = $ebook->deskripsi ?? '';
            $this->pengarang          = $ebook->pengarang;
            $this->penerbit           = $ebook->penerbit ?? '';
            $this->tahun              = (string) ($ebook->tahun ?? date('Y'));
            $this->status             = $ebook->status;
            $this->existingPdfUrl     = $ebook->file_url;
            $this->existingCoverUrl   = $ebook->cover_url;

            if ($ebook->cover_url && (str_starts_with($ebook->cover_url, 'http://') || str_starts_with($ebook->cover_url, 'https://'))) {
                $this->coverSourceType = 'url';
                $this->coverUrlInput = $ebook->cover_url;
            } else {
                $this->coverSourceType = 'upload';
            }
        } else {
            $this->selectedCategories = [];
        }
    }

    public function addCategory(?string $name = null): void
    {
        $target = trim($name ?? $this->customCategoryInput);
        if (!empty($target)) {
            $parts = array_map('trim', explode(',', $target));
            foreach ($parts as $p) {
                if ($p !== '' && !in_array($p, $this->selectedCategories, true)) {
                    $this->selectedCategories[] = $p;
                }
            }
        }
        $this->customCategoryInput = '';
    }

    public function removeCategory(int $index): void
    {
        if (isset($this->selectedCategories[$index])) {
            unset($this->selectedCategories[$index]);
            $this->selectedCategories = array_values($this->selectedCategories);
        }
    }

    public function toggleSuggestedCategory(string $cat): void
    {
        if (in_array($cat, $this->selectedCategories, true)) {
            $this->selectedCategories = array_values(array_filter($this->selectedCategories, fn($c) => $c !== $cat));
        } else {
            $this->selectedCategories[] = $cat;
        }
    }

    public function removeCover(): void
    {
        $this->fileCover = null;
        $this->coverUrlInput = '';
        $this->existingCoverUrl = null;
    }

    public function deleteExistingCover(): void
    {
        if ($this->existingCoverUrl && !str_starts_with($this->existingCoverUrl, 'http') && Storage::disk('public')->exists($this->existingCoverUrl)) {
            Storage::disk('public')->delete($this->existingCoverUrl);
        }
        $this->existingCoverUrl = null;
        $this->coverUrlInput = '';
        if ($this->ebookId) {
            Ebook::where('id', $this->ebookId)->update(['cover_url' => null]);
        }
    }

    public function save(): void
    {
        $this->validate([
            'judul'              => 'required|min:3|max:255',
            'selectedCategories' => 'required|array|min:1',
            'deskripsi'          => 'required|min:10',
            'pengarang'          => 'required',
            'tahun'              => 'nullable|integer|min:2000|max:'.(date('Y') + 1),
            'filePdf'            => $this->mode === 'add' && !$this->existingPdfUrl
                                      ? 'required|file|mimes:pdf|max:51200'
                                      : 'nullable|file|mimes:pdf|max:51200',
            'fileCover'          => $this->coverSourceType === 'upload' ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120' : 'nullable',
            'coverUrlInput'      => $this->coverSourceType === 'url' ? 'nullable|url|max:2048' : 'nullable',
        ], [
            'judul.required'              => 'Judul e-book wajib diisi.',
            'judul.min'                   => 'Judul minimal 3 karakter.',
            'selectedCategories.required' => 'Kategori e-book wajib diisi minimal 1 kategori.',
            'selectedCategories.min'      => 'Kategori e-book wajib diisi minimal 1 kategori.',
            'deskripsi.required'          => 'Deskripsi wajib diisi.',
            'deskripsi.min'               => 'Deskripsi minimal 10 karakter.',
            'pengarang.required'          => 'Pengarang / instansi wajib diisi.',
            'filePdf.required'            => 'File PDF wajib diupload.',
            'filePdf.mimes'               => 'File harus berformat PDF.',
            'filePdf.max'                 => 'Ukuran file PDF maksimal 50 MB.',
            'fileCover.image'             => 'Cover harus berupa gambar.',
            'fileCover.max'               => 'Ukuran cover maksimal 5 MB.',
            'coverUrlInput.url'           => 'Tautan URL cover harus berupa URL valid (contoh: https://...).',
        ]);

        $pdfPath   = $this->existingPdfUrl;
        $coverPath = $this->existingCoverUrl;
        $fileSize  = null;

        // Upload PDF baru
        if ($this->filePdf) {
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            $pdfPath  = $this->filePdf->store('ebooks/pdf', 'public');
            $fileSize = $this->filePdf->getSize();
        }

        // Cover handling
        if ($this->coverSourceType === 'url') {
            if (!empty(trim($this->coverUrlInput))) {
                if ($this->existingCoverUrl && !str_starts_with($this->existingCoverUrl, 'http') && Storage::disk('public')->exists($this->existingCoverUrl)) {
                    Storage::disk('public')->delete($this->existingCoverUrl);
                }
                $coverPath = trim($this->coverUrlInput);
            } else {
                $coverPath = null;
            }
        } elseif ($this->fileCover) {
            if ($coverPath && !str_starts_with($coverPath, 'http') && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $this->fileCover->store('ebooks/covers', 'public');
        }

        $data = [
            'judul'      => $this->judul,
            'kategori'   => implode(', ', $this->selectedCategories),
            'deskripsi'  => $this->deskripsi,
            'pengarang'  => $this->pengarang,
            'penerbit'   => $this->penerbit ?: null,
            'tahun'      => $this->tahun ?: null,
            'file_url'   => $pdfPath,
            'cover_url'  => $coverPath,
            'status'     => $this->status,
            'created_by' => Auth::id(),
        ];

        if ($fileSize) {
            $data['ukuran_file'] = $fileSize;
        }

        if ($this->mode === 'edit' && $this->ebookId) {
            Ebook::findOrFail($this->ebookId)->update($data);
            $this->successMessage = 'E-Book berhasil diperbarui!';
            $this->existingCoverUrl = $coverPath;
            $this->fileCover = null;
        } else {
            $data['jumlah_unduhan'] = 0;
            Ebook::create($data);
            $this->successMessage = 'E-Book berhasil ditambahkan!';

            $this->reset(['judul', 'selectedCategories', 'customCategoryInput', 'deskripsi', 'penerbit', 'filePdf', 'fileCover', 'coverUrlInput']);
            $this->selectedCategories = [];
            $this->pengarang = 'Diskominfo Provinsi Jawa Timur';
            $this->tahun     = (string) date('Y');
            $this->status    = 'Publik';
        }
    }

    public function render()
    {
        return view('livewire.admin.ebook-form');
    }
}
