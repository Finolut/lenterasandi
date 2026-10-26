<?php

namespace App\Livewire\Admin;

use App\Models\Informasi;
use App\Models\PermohonanAkses;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class MessageManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $selectedStatus = 'Semua';
    public ?int $selectedInformasiId = null;
    public int $perPage = 10;

    public array $selectedIds = [];
    public bool $selectAll = false;

    // Detail Modal
    public ?int $viewingMessageId = null;
    public ?PermohonanAkses $activeMessage = null;
    public string $editStatus = 'Menunggu';
    public string $adminNotes = '';
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

    public function viewMessage(int $id): void
    {
        $message = PermohonanAkses::with(['informasi', 'tokens'])->findOrFail($id);
        
        // Mark as read if not read yet
        if ($message->dibaca_at === null) {
            $message->update(['dibaca_at' => now()]);
            Cache::forget('admin_sidebar_counts');
        }

        $this->activeMessage = $message;
        $this->viewingMessageId = $id;
        $this->editStatus = $message->status;
        $this->adminNotes = $message->catatan_admin ?? '';
        $this->flashMessage = '';
    }

    /**
     * Setujui permohonan, autogenerate token sekali pakai, dan kirim otomatis ke email pemohon.
     */
    public function approveAndSendToken(int $messageId): void
    {
        $message = PermohonanAkses::with(['informasi', 'tokens'])->findOrFail($messageId);

        if (!$message->informasi_id || !$message->informasi) {
            $this->flashMessage = 'Tidak dapat memproses: artikel atau berkas pendukung tidak ditemukan.';
            return;
        }

        // Cari token aktif yang belum digunakan untuk permohonan ini jika sudah ada
        $existingToken = $message->tokens()->where('is_used', false)->latest()->first();

        if ($existingToken) {
            $token = $existingToken;
        } else {
            // Generate token baru
            $token = \App\Models\BerkasToken::create([
                'informasi_id'  => $message->informasi_id,
                'token'         => \App\Models\BerkasToken::generateUniqueToken(),
                'is_used'       => false,
                'created_by'    => auth()->id(),
                'catatan'       => "Pemohon: {$message->nama_lengkap} ({$message->email})",
                'permohonan_id' => $message->id,
            ]);
        }

        // Update status permohonan menjadi Disetujui
        $message->update([
            'status'    => 'Disetujui',
            'dibaca_at' => $message->dibaca_at ?? now(),
        ]);

        if ($this->activeMessage && $this->activeMessage->id === $message->id) {
            $this->activeMessage = $message->fresh(['informasi', 'tokens']);
            $this->editStatus = 'Disetujui';
        }

        Cache::forget('admin_sidebar_counts');

        $waNote = $message->formatted_wa_number ? " Klik tombol 'Kirim via WA' untuk langsung mengirimkan token ke pemohon." : "";
        $this->flashMessage = "✓ Permohonan disetujui! Token ({$token->token}) berhasil dibuat.{$waNote}";
        session()->flash('success', "Permohonan disetujui! Token ({$token->token}) dibuat.{$waNote}");
    }

    public function generateTokenForMessage(): void
    {
        if (!$this->activeMessage) return;
        $this->approveAndSendToken($this->activeMessage->id);
    }

    public function closeModal(): void
    {
        $this->viewingMessageId = null;
        $this->activeMessage = null;
    }

    public function updateStatus(string $newStatus): void
    {
        if (!$this->activeMessage) return;

        if ($newStatus === 'Disetujui') {
            $this->approveAndSendToken($this->activeMessage->id);
            return;
        }

        $this->activeMessage->update([
            'status'        => $newStatus,
            'catatan_admin' => $this->adminNotes,
        ]);

        $this->editStatus = $newStatus;
        $this->flashMessage = "Status permohonan berhasil diubah menjadi: {$newStatus}";
    }

    public function saveDetails(): void
    {
        if (!$this->activeMessage) return;

        $this->activeMessage->update([
            'status'        => $this->editStatus,
            'catatan_admin' => $this->adminNotes,
        ]);

        $this->flashMessage = 'Perubahan data permohonan berhasil disimpan!';
    }

    public function markAsRead(int $id): void
    {
        $msg = PermohonanAkses::findOrFail($id);
        $msg->update(['dibaca_at' => now()]);
        Cache::forget('admin_sidebar_counts');
        session()->flash('success', 'Pesan ditandai sebagai sudah dibaca.');
    }

    public function markAllAsRead(): void
    {
        PermohonanAkses::whereNull('dibaca_at')->update(['dibaca_at' => now()]);
        Cache::forget('admin_sidebar_counts');
        session()->flash('success', 'Semua pesan berhasil ditandai sudah dibaca.');
    }

    public function deleteMessage(int $id): void
    {
        $msg = PermohonanAkses::findOrFail($id);
        $msg->delete();
        $this->selectedIds = array_diff($this->selectedIds, [$id]);
        Cache::forget('admin_sidebar_counts');
        session()->flash('success', 'Pesan permohonan berhasil dihapus.');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) return;

        PermohonanAkses::whereIn('id', $this->selectedIds)->delete();
        $count = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll = false;
        Cache::forget('admin_sidebar_counts');
        session()->flash('success', "{$count} pesan permohonan berhasil dihapus.");
    }

    protected function getFilteredQuery()
    {
        $query = PermohonanAkses::with(['informasi', 'tokens'])->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('instansi', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('telepon', 'like', '%' . $this->search . '%')
                  ->orWhere('keperluan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('informasi', function ($sub) {
                      $sub->where('judul', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedStatus === 'Belum Dibaca') {
            $query->whereNull('dibaca_at');
        } elseif ($this->selectedStatus !== 'Semua') {
            $query->where('status', $this->selectedStatus);
        }

        if ($this->selectedInformasiId) {
            $query->where('informasi_id', $this->selectedInformasiId);
        }

        return $query;
    }

    public function render()
    {
        $messages = $this->getFilteredQuery()->paginate($this->perPage);

        $totalAll = PermohonanAkses::count();
        $totalUnread = PermohonanAkses::whereNull('dibaca_at')->count();
        $totalPending = PermohonanAkses::where('status', 'Menunggu')->count();
        $totalApproved = PermohonanAkses::where('status', 'Disetujui')->count();

        return view('livewire.admin.message-manager', [
            'messages'      => $messages,
            'totalAll'      => $totalAll,
            'totalUnread'   => $totalUnread,
            'totalPending'  => $totalPending,
            'totalApproved' => $totalApproved,
        ]);
    }
}
