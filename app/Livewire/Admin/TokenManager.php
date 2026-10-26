<?php

namespace App\Livewire\Admin;

use App\Models\BerkasToken;
use App\Models\Informasi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TokenManager extends Component
{
    use WithPagination;

    // Filter & Search
    public string $search = '';
    public string $selectedStatus = 'Semua'; // 'Semua', 'Tersedia', 'Sudah Digunakan'
    public ?int $selectedInformasiId = null;
    public int $perPage = 10;

    // Checkbox selections
    public array $selectedIds = [];
    public bool $selectAll = false;

    // Modal Generate Token
    public bool $showGenerateModal = false;
    public ?int $formInformasiId = null;
    public int $formJumlah = 1;
    public string $formCatatan = '';
    public array $newlyGeneratedTokens = [];

    // Flash notification
    public string $flashMessage = '';

    protected $queryString = [
        'search'         => ['except' => ''],
        'selectedStatus' => ['except' => 'Semua'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (int) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function openGenerateModal(?int $informasiId = null): void
    {
        $this->resetValidation();
        $this->newlyGeneratedTokens = [];
        $this->formInformasiId = $informasiId;
        $this->formJumlah = 1;
        $this->formCatatan = '';
        $this->showGenerateModal = true;
    }

    public function closeGenerateModal(): void
    {
        $this->showGenerateModal = false;
        $this->newlyGeneratedTokens = [];
    }

    public function generateTokens(): void
    {
        $this->validate([
            'formInformasiId' => 'required|exists:informasi,id',
            'formJumlah'      => 'required|integer|min:1|max:20',
            'formCatatan'     => 'nullable|string|max:255',
        ], [
            'formInformasiId.required' => 'Pilih artikel yang memiliki berkas pendukung.',
            'formInformasiId.exists'   => 'Artikel tidak ditemukan.',
            'formJumlah.required'      => 'Jumlah token wajib diisi.',
            'formJumlah.min'           => 'Jumlah minimal 1 token.',
            'formJumlah.max'           => 'Jumlah maksimal 20 token sekaligus.',
        ]);

        $created = [];
        for ($i = 0; $i < $this->formJumlah; $i++) {
            $token = BerkasToken::create([
                'informasi_id' => $this->formInformasiId,
                'token'        => BerkasToken::generateUniqueToken(),
                'is_used'      => false,
                'created_by'   => Auth::id(),
                'catatan'      => $this->formCatatan ? trim($this->formCatatan) : null,
            ]);
            $created[] = $token->token;
        }

        $this->newlyGeneratedTokens = $created;
        $this->flashMessage = count($created) . ' token berhasil digenerate!';
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        $this->resetPage();
    }

    public function deleteToken(int $id): void
    {
        $token = BerkasToken::findOrFail($id);
        $token->delete();

        $this->selectedIds = array_values(array_filter($this->selectedIds, fn($val) => $val !== $id));
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        $this->flashMessage = 'Token berhasil dihapus.';
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) {
            return;
        }

        $count = BerkasToken::whereIn('id', $this->selectedIds)->count();
        BerkasToken::whereIn('id', $this->selectedIds)->delete();

        $this->selectedIds = [];
        $this->selectAll = false;
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
        $this->flashMessage = "{$count} token berhasil dihapus.";
    }

    private function getFilteredQuery()
    {
        $query = BerkasToken::with(['informasi', 'creator', 'permohonan'])->latest();

        if ($this->search) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('token', 'ilike', $s)
                  ->orWhere('catatan', 'ilike', $s)
                  ->orWhereHas('informasi', fn($sub) => $sub->where('judul', 'ilike', $s)->orWhere('file_pendukung_nama', 'ilike', $s))
                  ->orWhereHas('permohonan', fn($sub) => $sub->where('nama_lengkap', 'ilike', $s)->orWhere('instansi', 'ilike', $s));
            });
        }

        if ($this->selectedStatus === 'Tersedia') {
            $query->where('is_used', false);
        } elseif ($this->selectedStatus === 'Sudah Digunakan') {
            $query->where('is_used', true);
        }

        if ($this->selectedInformasiId) {
            $query->where('informasi_id', $this->selectedInformasiId);
        }

        return $query;
    }

    public function render()
    {
        $tokens = $this->getFilteredQuery()->paginate($this->perPage);

        // Daftar artikel yang memiliki berkas pendukung
        $articlesWithFiles = Informasi::whereNotNull('file_pendukung_url')
            ->where('file_pendukung_url', '!=', '')
            ->orderBy('judul')
            ->get();

        // Statistics
        $totalTokens = BerkasToken::count();
        $availableTokens = BerkasToken::where('is_used', false)->count();
        $usedTokens = BerkasToken::where('is_used', true)->count();

        return view('livewire.admin.token-manager', [
            'tokens'            => $tokens,
            'articlesWithFiles' => $articlesWithFiles,
            'totalTokens'       => $totalTokens,
            'availableTokens'   => $availableTokens,
            'usedTokens'        => $usedTokens,
        ]);
    }
}
