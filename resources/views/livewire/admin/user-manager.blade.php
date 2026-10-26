<div 
    x-data="{
        isModalOpen: @entangle('isModalOpen'),
        isDeleteModalOpen: @entangle('isDeleteModalOpen'),
        openCreateModal() {
            $wire.set('editingUserId', null, false);
            $wire.set('name', '', false);
            $wire.set('email', '', false);
            $wire.set('password', '', false);
            $wire.set('password_confirmation', '', false);
            $wire.set('avatar', null, false);
            $wire.set('existingAvatarUrl', null, false);
            $wire.set('removeAvatar', false, false);
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
        },
        closeDeleteModal() {
            this.isDeleteModalOpen = false;
        }
    }"
    class="space-y-6"
>
    {{-- Toast Notification --}}
    @if($successMessage)
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="dismissToast" class="text-emerald-600 hover:text-emerald-900 p-1 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errorMessage)
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-medium flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
            <button wire:click="dismissToast" class="text-rose-600 hover:text-rose-900 p-1 cursor-pointer">✕</button>
        </div>
    @endif

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Kelola Administrator</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $users->total() }} akun administrator terdaftar dengan hak akses sistem</p>
        </div>

        <button
            @click="openCreateModal()"
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-xs sm:text-sm text-white bg-[#0284c7] hover:bg-[#0369a1] shadow-xs transition-colors cursor-pointer shrink-0"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span>Tambah Admin Baru</span>
        </button>
    </div>

    {{-- Search Filter Bar --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
        <div class="relative w-full sm:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Cari nama admin atau email..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all placeholder:text-slate-400"
            />
        </div>
    </div>

    {{-- Admin Table Card --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-5">Administrator</th>
                        <th class="py-3 px-4">Terdaftar Sejak</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        @php
                            $isCurrentUser = ($user->id === auth()->id());
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isCurrentUser ? 'bg-sky-50/20' : '' }}">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    {{-- User Avatar / Initials --}}
                                    @if($user->avatar_url)
                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-lg object-cover shrink-0 select-none border border-slate-200" />
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-[#0284c7] to-[#083575] text-white flex items-center justify-center font-bold text-xs shrink-0 select-none">
                                            {{ $user->initials() ?? 'LS' }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-xs sm:text-sm text-slate-900 truncate">{{ $user->name }}</span>
                                            @if($isCurrentUser)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-50 text-[#0284c7] border border-sky-200/60">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500 font-mono mt-0.5 truncate">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600">
                                <div>{{ $user->created_at ? $user->created_at->locale('id')->translatedFormat('d M Y') : '—' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $user->created_at ? $user->created_at->format('H:i') . ' WIB' : '' }}</div>
                            </td>

                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Edit Button --}}
                                    <button
                                        wire:click="openEditModal({{ $user->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 border border-slate-300 transition-colors cursor-pointer"
                                        title="Edit Data Admin"
                                    >
                                        <span>Edit</span>
                                    </button>

                                    {{-- Delete Button (Protected for currently logged in user) --}}
                                    @if(!$isCurrentUser)
                                        <button
                                            wire:click="confirmDelete({{ $user->id }})"
                                            type="button"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200/60 transition-colors cursor-pointer"
                                            title="Hapus Akun Admin"
                                        >
                                            <span>Hapus</span>
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic px-2 py-1 select-none">
                                            Sedang Login
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 px-6 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-900">Tidak ada data admin ditemukan</p>
                                <p class="text-xs text-slate-500 mt-0.5">Coba kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200/80 bg-slate-50/75">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- CREATE / EDIT MODAL --}}
    <div 
        x-show="isModalOpen"
        x-cloak
        x-transition.opacity.duration.150ms
        @click.self="closeModal()"
        @keydown.escape.window="closeModal()"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity"
        style="display: none;"
    >
        <div class="bg-white rounded-xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/75">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284c7] flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $editingUserId ? 'Edit Data Administrator' : 'Tambah Administrator Baru' }}
                        </h3>
                    </div>
                    <button
                        type="button"
                        @click="closeModal()"
                        class="text-slate-400 hover:text-slate-700 p-1 rounded-lg transition-colors cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                {{-- Modal Form --}}
                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-4 text-xs">
                        {{-- Foto Profil --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-2">
                                Foto Profil
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    @if($avatar)
                                        <img src="{{ $avatar->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover border border-slate-300 shadow-2xs" alt="Preview Avatar" />
                                    @elseif($existingAvatarUrl)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($existingAvatarUrl) }}" class="w-16 h-16 rounded-lg object-cover border border-slate-300 shadow-2xs" alt="Avatar Admin" />
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-slate-100 text-slate-400 border border-dashed border-slate-300 flex items-center justify-center font-bold">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2">
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 cursor-pointer transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Unggah Foto</span>
                                            <input type="file" wire:model="avatar" accept="image/png,image/jpeg,image/webp,image/gif" class="hidden" />
                                        </label>

                                        @if($avatar || $existingAvatarUrl)
                                            <button 
                                                type="button" 
                                                wire:click.prevent="removeSelectedAvatar" 
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                    <div wire:loading wire:target="avatar" class="text-[11px] text-[#0284c7] font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        <span>Mengunggah foto...</span>
                                    </div>
                                    @error('avatar')
                                        <span class="text-[11px] text-rose-500 font-semibold block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input
                                wire:model="name"
                                type="text"
                                placeholder="Contoh: Budi Santoso, S.Kom"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all placeholder:text-slate-400"
                            />
                            @error('name')
                                <span class="text-[11px] text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">
                                Alamat Email Instansi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                wire:model="email"
                                type="email"
                                placeholder="admin@jatimprov.go.id"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all placeholder:text-slate-400"
                            />
                            @error('email')
                                <span class="text-[11px] text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Kata Sandi --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">
                                Kata Sandi @if(!$editingUserId)<span class="text-rose-500">*</span>@endif
                            </label>
                            <input
                                wire:model="password"
                                type="password"
                                placeholder="{{ $editingUserId ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all placeholder:text-slate-400"
                            />
                            @if($editingUserId)
                                <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tetap menggunakan kata sandi saat ini.</p>
                            @endif
                            @error('password')
                                <span class="text-[11px] text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Konfirmasi Kata Sandi --}}
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">
                                Konfirmasi Kata Sandi
                            </label>
                            <input
                                wire:model="password_confirmation"
                                type="password"
                                placeholder="Ulangi kata sandi di atas"
                                class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all placeholder:text-slate-400"
                            />
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/75 flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            @click="closeModal()"
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-xs font-semibold text-white bg-[#0284c7] hover:bg-[#0369a1] shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="save">{{ $editingUserId ? 'Simpan Perubahan' : 'Buat Administrator' }}</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div 
        x-show="isDeleteModalOpen"
        x-cloak
        x-transition.opacity.duration.150ms
        @click.self="closeDeleteModal()"
        @keydown.escape.window="closeDeleteModal()"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs transition-opacity"
        style="display: none;"
    >
        <div class="bg-white rounded-xl border border-slate-200 shadow-xl w-full max-w-sm overflow-hidden p-6 text-center animate-in fade-in zoom-in-95 duration-150">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3 border border-rose-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-1">Hapus Administrator?</h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-5">
                Apakah Anda yakin ingin menghapus akun <strong class="text-slate-800">{{ $deletingUserName }}</strong>? Akun ini tidak akan dapat login lagi ke sistem.
            </p>

            <div class="flex items-center justify-center gap-2.5">
                <button
                    type="button"
                    @click="closeDeleteModal()"
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button
                    wire:click="deleteUser"
                    type="button"
                    wire:loading.attr="disabled"
                    wire:target="deleteUser"
                    class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 shadow-xs transition-colors cursor-pointer disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="deleteUser">Ya, Hapus</span>
                    <span wire:loading wire:target="deleteUser">Menghapus...</span>
                </button>
            </div>
        </div>
    </div>
</div>
