<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';

    // Modal state
    public bool $isModalOpen = false;
    public bool $isDeleteModalOpen = false;
    public ?int $editingUserId = null;
    public ?int $deletingUserId = null;
    public ?string $deletingUserName = null;

    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Photo profile
    public $avatar = null;
    public ?string $existingAvatarUrl = null;
    public bool $removeAvatar = false;

    // Toast notification
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editingUserId = null;
        $this->isModalOpen = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $this->resetForm();

        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->existingAvatarUrl = $user->avatar_url;
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetValidation();
        $this->resetForm();
    }

    public function removeSelectedAvatar(): void
    {
        $this->avatar = null;
        $this->existingAvatarUrl = null;
        $this->removeAvatar = true;
    }

    public function confirmDelete(int $id): void
    {
        if ($id === Auth::id()) {
            $this->errorMessage = 'Anda tidak dapat menghapus akun yang sedang digunakan!';
            return;
        }

        $user = User::findOrFail($id);
        $this->deletingUserId = $user->id;
        $this->deletingUserName = $user->name;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal(): void
    {
        $this->isDeleteModalOpen = false;
        $this->deletingUserId = null;
        $this->deletingUserName = null;
    }

    public function deleteUser(): void
    {
        if ($this->deletingUserId === Auth::id()) {
            $this->errorMessage = 'Anda tidak dapat menghapus akun Anda sendiri!';
            $this->closeDeleteModal();
            return;
        }

        if ($this->deletingUserId) {
            $user = User::findOrFail($this->deletingUserId);
            $deletedName = $user->name;

            if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $user->delete();
            $this->successMessage = "Admin {$deletedName} berhasil dihapus.";
        }

        $this->closeDeleteModal();
    }

    public function save(): void
    {
        // Guard: Do not process if modal has been canceled or closed
        if (! $this->isModalOpen) {
            return;
        }

        if ($this->editingUserId) {
            // Edit validation
            $validated = $this->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->editingUserId)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'avatar'   => ['nullable', 'image', 'max:3072'],
            ], [
                'name.required'      => 'Nama lengkap wajib diisi.',
                'email.required'     => 'Alamat email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.unique'       => 'Alamat email ini sudah terdaftar.',
                'password.min'       => 'Kata sandi minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                'avatar.image'       => 'Foto profil harus berupa gambar.',
                'avatar.max'         => 'Ukuran foto maksimal 3 MB.',
            ]);

            $user = User::findOrFail($this->editingUserId);
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            // Handle avatar upload / remove
            if ($this->removeAvatar) {
                if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $user->avatar_url = null;
            }

            if ($this->avatar) {
                if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $user->avatar_url = $this->avatar->store('avatars', 'public');
            }

            $user->save();
            $this->successMessage = "Data admin {$user->name} berhasil diperbarui.";
        } else {
            // Create validation
            $validated = $this->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'avatar'   => ['nullable', 'image', 'max:3072'],
            ], [
                'name.required'      => 'Nama lengkap wajib diisi.',
                'email.required'     => 'Alamat email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.unique'       => 'Alamat email ini sudah terdaftar.',
                'password.required'  => 'Kata sandi wajib diisi.',
                'password.min'       => 'Kata sandi minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                'avatar.image'       => 'Foto profil harus berupa gambar.',
                'avatar.max'         => 'Ukuran foto maksimal 3 MB.',
            ]);

            $avatarPath = null;
            if ($this->avatar) {
                $avatarPath = $this->avatar->store('avatars', 'public');
            }

            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'avatar_url'        => $avatarPath,
                'email_verified_at' => now(),
            ]);

            $this->successMessage = "Admin baru {$user->name} berhasil ditambahkan.";
        }

        $this->closeModal();
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->editingUserId = null;
        $this->avatar = null;
        $this->existingAvatarUrl = null;
        $this->removeAvatar = false;
    }

    public function dismissToast(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users,
        ]);
    }
}

