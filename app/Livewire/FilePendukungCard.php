<?php

namespace App\Livewire;

use App\Models\Informasi;
use App\Models\PermohonanAkses;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FilePendukungCard extends Component
{
    public int $informasiId;
    
    // Form fields for Ajukan Akses
    public string $namaLengkap = '';
    public string $instansi = '';
    public string $email = '';
    public string $telepon = '';
    public string $keperluan = '';

    public bool $showModal = false;
    public string $modalTab = 'form'; // 'form' or 'token'
    public bool $isSubmitted = false;
    public string $successMessage = '';

    // Token Sekali Pakai fields
    public bool $showTokenModal = false;
    public string $tokenInput = '';
    public string $tokenError = '';
    public string $tokenSuccess = '';

    public function mount(int $informasiId): void
    {
        $this->informasiId = $informasiId;
    }

    public function openModal(string $tab = 'form'): void
    {
        $this->resetValidation();
        $this->isSubmitted = false;
        $this->modalTab = $tab;
        $this->tokenInput = '';
        $this->tokenError = '';
        $this->showModal = true;
    }

    public function setModalTab(string $tab): void
    {
        $this->modalTab = $tab;
        $this->tokenError = '';
    }

    public function openTokenModal(): void
    {
        $this->openModal('token');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showTokenModal = false;
        $this->tokenInput = '';
        $this->tokenError = '';
    }

    public function closeTokenModal(): void
    {
        $this->closeModal();
    }

    public function verifyAndDownloadToken()
    {
        $this->tokenError = '';
        $cleanToken = strtoupper(trim($this->tokenInput));

        if (empty($cleanToken)) {
            $this->tokenError = 'Harap masukkan kode token terlebih dahulu.';
            return null;
        }

        $informasi = Informasi::findOrFail($this->informasiId);

        $token = \App\Models\BerkasToken::where('informasi_id', $this->informasiId)
            ->where('token', $cleanToken)
            ->first();

        if (!$token) {
            $this->tokenError = 'Token tidak valid atau tidak cocok untuk berkas artikel ini.';
            return null;
        }

        if ($token->is_used) {
            $usedTime = $token->used_at ? $token->used_at->translatedFormat('d M Y H:i') : 'sebelumnya';
            $this->tokenError = "Token ini sudah pernah digunakan pada {$usedTime} dan sudah hangus (hanya berlaku 1x unduh).";
            return null;
        }

        if (!$informasi->hasFilePendukung() || !Storage::disk('public')->exists($informasi->file_pendukung_url)) {
            $this->tokenError = 'Berkas fisik tidak ditemukan di server penyimpanan.';
            return null;
        }

        // Hanguskan token (sekali pakai)
        $token->markAsUsed(request()->ip());
        \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');

        $this->showModal = false;
        $this->showTokenModal = false;
        $this->dispatch('close-file-modal');
        session()->flash('token_success', 'Token valid! Berkas berhasil diunduh (token kini telah hangus).');

        return Storage::disk('public')->download(
            $informasi->file_pendukung_url,
            $informasi->file_pendukung_nama ?? 'berkas-pendukung'
        );
    }

    public function submitPermohonan(): void
    {
        $this->validate([
            'namaLengkap' => 'required|min:3|max:150',
            'instansi'    => 'required|min:3|max:150',
            'email'       => 'required|email|max:150',
            'telepon'     => 'required|min:9|max:30',
            'keperluan'   => 'nullable|max:1000',
        ], [
            'namaLengkap.required' => 'Nama lengkap wajib diisi.',
            'namaLengkap.min'      => 'Nama lengkap minimal 3 karakter.',
            'instansi.required'    => 'Instansi / Organisasi wajib diisi.',
            'instansi.min'         => 'Instansi minimal 3 karakter.',
            'email.required'       => 'Alamat email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'telepon.required'     => 'Nomor WhatsApp / Telepon aktif wajib diisi untuk pengiriman token.',
            'telepon.min'          => 'Nomor WhatsApp minimal 9 digit.',
        ]);

        $informasi = Informasi::findOrFail($this->informasiId);

        PermohonanAkses::create([
            'informasi_id' => $informasi->id,
            'nama_lengkap' => $this->namaLengkap,
            'instansi'     => $this->instansi,
            'email'        => $this->email,
            'telepon'      => $this->telepon,
            'keperluan'    => $this->keperluan ?: null,
            'status'       => 'Menunggu',
        ]);

        $this->isSubmitted = true;
        $this->successMessage = 'Permohonan akses berhasil dikirim! Tim administrator kami akan segera meninjau dan mengirimkan token akses ke WhatsApp Anda.';
        
        $this->reset(['namaLengkap', 'instansi', 'email', 'telepon', 'keperluan']);
    }

    public function downloadFile()
    {
        $informasi = Informasi::findOrFail($this->informasiId);
        
        if (!$informasi->hasFilePendukung() || !$informasi->filePendukungIsPublik()) {
            session()->flash('error', 'Berkas ini membutuhkan permohonan akses terlebih dahulu.');
            return null;
        }

        if (!Storage::disk('public')->exists($informasi->file_pendukung_url)) {
            session()->flash('error', 'Berkas fisik tidak ditemukan di server.');
            return null;
        }

        return Storage::disk('public')->download(
            $informasi->file_pendukung_url,
            $informasi->file_pendukung_nama ?? 'berkas-pendukung'
        );
    }

    public function render()
    {
        $informasi = Informasi::findOrFail($this->informasiId);
        return view('livewire.file-pendukung-card', [
            'informasi' => $informasi,
        ]);
    }
}
