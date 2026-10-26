<?php

namespace App\Livewire\Admin;

use App\Models\Informasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsForm extends Component
{
    use WithFileUploads;

    public string $mode = 'add'; // 'add' or 'edit'
    public ?int $newsId = null;

    public string $title = '';
    public array $selectedCategories = [];
    public string $customCategoryInput = '';
    public string $content = '';
    public string $publishDate = '';
    public string $status = 'Publik';

    public array $suggestedCategories = [];
    
    // Thumbnail / Featured Image
    public $image;
    public ?string $existingImage = null;
    public string $imageSourceType = 'upload'; // 'upload' or 'url'
    public string $imageUrlInput = '';
    
    // File Pendukung (Opsional)
    public $supportingFile = null;
    public ?string $existingSupportingFileUrl = null;
    public ?string $existingSupportingFileName = null;
    public ?string $existingSupportingFileSize = null;
    public ?string $existingSupportingFileType = null;
    public string $fileAccessType = 'Publik'; // 'Publik' or 'Perlu Ajukan'
    public bool $removeSupportingFile = false;

    public string $successMessage = '';

    public function mount(string $mode = 'add', ?int $id = null): void
    {
        $this->mode = $mode;
        $this->newsId = $id;

        $defaults = ['Pengumuman', 'Kegiatan', 'Informasi', 'Keamanan Siber', 'Edukasi', 'Panduan'];
        $existing = Informasi::whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori');
        $all = [];
        foreach ($existing as $raw) {
            foreach (explode(',', $raw) as $c) {
                $c = trim($c);
                if ($c !== '' && !in_array($c, $all, true)) {
                    $all[] = $c;
                }
            }
        }
        $this->suggestedCategories = array_values(array_unique(array_merge($defaults, $all)));

        if ($mode === 'edit' && $id) {
            $informasi = Informasi::findOrFail($id);
            $this->title = $informasi->judul;
            $this->selectedCategories = $informasi->kategori_list;
            $this->content = $informasi->konten;
            $this->publishDate = $informasi->published_at ? $informasi->published_at->format('d / m / Y') : '';
            $this->status = $informasi->status;
            $this->existingImage = $informasi->gambar_url;

            if ($informasi->gambar_url && (str_starts_with($informasi->gambar_url, 'http://') || str_starts_with($informasi->gambar_url, 'https://'))) {
                $this->imageSourceType = 'url';
                $this->imageUrlInput = $informasi->gambar_url;
            } else {
                $this->imageSourceType = 'upload';
            }

            $this->existingSupportingFileUrl  = $informasi->file_pendukung_url;
            $this->existingSupportingFileName = $informasi->file_pendukung_nama;
            $this->existingSupportingFileSize = $informasi->file_pendukung_ukuran;
            $this->existingSupportingFileType = $informasi->file_pendukung_tipe;
            $this->fileAccessType             = $informasi->akses_file ?? 'Publik';
        } else {
            $this->publishDate = date('d / m / Y');
            $this->fileAccessType = 'Publik';
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

    public function removeImage(): void
    {
        $this->image = null;
        $this->imageUrlInput = '';
        $this->existingImage = null;
    }

    public function save(): void
    {
        if (!empty(trim($this->customCategoryInput))) {
            $this->addCategory();
        }

        $this->validate([
            'title'              => 'required|min:3',
            'selectedCategories' => 'required|array|min:1',
            'content'            => 'required',
            'image'              => $this->imageSourceType === 'upload' ? 'nullable|image|max:10240' : 'nullable',
            'imageUrlInput'      => $this->imageSourceType === 'url' ? 'nullable|url|max:2048' : 'nullable',
            'supportingFile'     => 'nullable|file|max:51200',  // max 50MB
            'fileAccessType'     => 'required|in:Publik,Perlu Ajukan',
        ], [
            'title.required'              => 'Judul artikel wajib diisi.',
            'selectedCategories.required' => 'Kategori artikel wajib diisi minimal 1 kategori.',
            'selectedCategories.min'      => 'Kategori artikel wajib diisi minimal 1 kategori.',
            'content.required'            => 'Konten artikel wajib diisi.',
            'image.image'                 => 'Berkas thumbnail harus berupa gambar.',
            'image.max'                   => 'Ukuran gambar thumbnail maksimal 10 MB.',
            'imageUrlInput.url'           => 'Tautan URL gambar harus berupa URL valid (contoh: https://...).',
            'supportingFile.max'          => 'Ukuran berkas pendukung maksimal 50 MB.',
        ]);

        $imagePath = $this->existingImage;

        if ($this->imageSourceType === 'url') {
            if (!empty(trim($this->imageUrlInput))) {
                if ($this->existingImage && !str_starts_with($this->existingImage, 'http') && Storage::disk('public')->exists($this->existingImage)) {
                    Storage::disk('public')->delete($this->existingImage);
                }
                $imagePath = trim($this->imageUrlInput);
            }
        } elseif ($this->image) {
            if ($this->existingImage && !str_starts_with($this->existingImage, 'http') && Storage::disk('public')->exists($this->existingImage)) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('informasi', 'public');
        }

        $supportingFilePath = $this->existingSupportingFileUrl;
        $supportingFileName = $this->existingSupportingFileName;
        $supportingFileSize = $this->existingSupportingFileSize;
        $supportingFileType = $this->existingSupportingFileType;

        if ($this->removeSupportingFile) {
            if ($this->existingSupportingFileUrl && Storage::disk('public')->exists($this->existingSupportingFileUrl)) {
                Storage::disk('public')->delete($this->existingSupportingFileUrl);
            }
            $supportingFilePath = null;
            $supportingFileName = null;
            $supportingFileSize = null;
            $supportingFileType = null;
        }

        if ($this->supportingFile) {
            if ($this->existingSupportingFileUrl && Storage::disk('public')->exists($this->existingSupportingFileUrl)) {
                Storage::disk('public')->delete($this->existingSupportingFileUrl);
            }

            $supportingFilePath = $this->supportingFile->store('informasi/files', 'public');
            $supportingFileName = $this->supportingFile->getClientOriginalName();
            $supportingFileType = strtolower($this->supportingFile->getClientOriginalExtension());

            $bytes = $this->supportingFile->getSize();
            if ($bytes >= 1048576) {
                $supportingFileSize = number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                $supportingFileSize = number_format($bytes / 1024, 0) . ' KB';
            } else {
                $supportingFileSize = $bytes . ' B';
            }
        }

        $publishDateCarbon = null;
        if (!empty($this->publishDate)) {
            $parts = array_map('trim', explode('/', $this->publishDate));
            if (count($parts) === 3) {
                $publishDateCarbon = Carbon::createFromFormat('Y-m-d', "{$parts[2]}-{$parts[1]}-{$parts[0]}")->startOfDay();
            }
        }

        // Generate unique slug automatically from title
        $baseSlug = Str::slug($this->title) ?: 'artikel';
        $slug = $baseSlug;
        $counter = 1;
        while (Informasi::where('slug', $slug)->when($this->newsId, fn($q) => $q->where('id', '!=', $this->newsId))->exists()) {
            $slug = $baseSlug . '-' . (++$counter);
        }

        // Automatic excerpt/summary from content
        $summary = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->content))), 160);

        $data = [
            'judul'                 => $this->title,
            'slug'                  => $slug,
            'konten'                => $this->content,
            'ringkasan'             => $summary,
            'gambar_url'            => $imagePath,
            'file_pendukung_url'    => $supportingFilePath,
            'file_pendukung_nama'   => $supportingFileName,
            'file_pendukung_ukuran' => $supportingFileSize,
            'file_pendukung_tipe'   => $supportingFileType,
            'akses_file'            => $supportingFilePath ? $this->fileAccessType : 'Publik',
            'kategori'              => implode(', ', $this->selectedCategories),
            'status'                => $this->status,
            'published_at'          => $publishDateCarbon,
        ];

        if ($this->mode === 'add') {
            $data['created_by'] = auth()->id() ?? 1;
            Informasi::create($data);
            $this->successMessage = 'Artikel berita baru berhasil disimpan!';
            
            // reset after creation
            $this->reset(['title', 'selectedCategories', 'customCategoryInput', 'content', 'image', 'imageUrlInput', 'supportingFile']);
            $this->selectedCategories = [];
            $this->publishDate = date('d / m / Y');
            $this->status = 'Publik';
        } else {
            $informasi = Informasi::findOrFail($this->newsId);
            $informasi->update($data);
            $this->successMessage = 'Artikel berita berhasil diperbarui!';
            $this->existingImage = $imagePath;
            $this->image = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.news-form');
    }
}
