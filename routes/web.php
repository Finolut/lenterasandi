<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Lentera Sandi
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC ROUTES (LENTERA SANDI PORTAL)
// ==========================================

Route::view('/', 'pages.home')->name('home');

Route::prefix('koleksi')->name('ebooks.')->group(function () {
    Route::get('/', \App\Livewire\EbookCollection::class)->name('index');
    Route::get('/kategori/{category}', \App\Livewire\EbookCollection::class)->name('category');

    Route::view('/{id}', 'pages.ebooks.show')->name('show');
    
    // Baca Online Route
    Route::get('/{id}/baca', function ($id) {
        $ebook = \App\Models\Ebook::findOrFail($id);
        if (!$ebook->file_url || !Storage::disk('public')->exists($ebook->file_url)) abort(404, 'File tidak ditemukan');
        
        return Storage::disk('public')->response($ebook->file_url, $ebook->judul . '.pdf', [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($ebook->judul) . '.pdf"',
        ]);
    })->name('read');

    // Download Route
    Route::get('/{id}/download', function ($id) {
        $ebook = \App\Models\Ebook::findOrFail($id);
        if ($ebook->file_url && Storage::disk('public')->exists($ebook->file_url)) {
            $ebook->increment('jumlah_unduhan');
            return Storage::disk('public')->download($ebook->file_url, $ebook->judul . '.pdf');
        }
        abort(404, 'File tidak ditemukan');
    })->name('download');
});

Route::prefix('informasi')->name('informasi.')->group(function () {
    Route::get('/', \App\Livewire\InformationList::class)->name('index');
    Route::get('/kategori/{category}', \App\Livewire\InformationList::class)->name('category');

    Route::view('/{id}', 'pages.informasi.show')->name('show');

    // Download Berkas Pendukung (Akses Publik)
    Route::get('/{id}/download-berkas', function ($id) {
        $informasi = \App\Models\Informasi::where('status', 'Publik')->findOrFail($id);
        if (!$informasi->hasFilePendukung() || !$informasi->filePendukungIsPublik()) {
            abort(403, 'Berkas ini membutuhkan permohonan akses terlebih dahulu.');
        }
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($informasi->file_pendukung_url)) {
            abort(404, 'Berkas fisik tidak ditemukan di server.');
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->download(
            $informasi->file_pendukung_url,
            $informasi->file_pendukung_nama ?? ($informasi->judul . '.' . ($informasi->file_pendukung_tipe ?? 'pdf'))
        );
    })->name('download-berkas');
});


// ==========================================
// 2. AUTHENTICATION
// ==========================================

Route::get('/login', function () {
    // Jika sudah login, langsung ke dashboard
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('pages.auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email atau kata sandi yang Anda masukkan salah.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

// ==========================================
// 3. ADMIN PANEL (auth wajib)
// ==========================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'pages.admin.dashboard')->name('dashboard');
    Route::view('/dashboard', 'pages.admin.dashboard');

    // E-Book Management
    Route::prefix('ebooks')->name('ebooks.')->group(function () {
        Route::view('/', 'pages.admin.ebooks.index')->name('index');
        Route::view('/create', 'pages.admin.ebooks.create')->name('create');
        Route::view('/{id}/edit', 'pages.admin.ebooks.edit')->name('edit');
    });

    // Information / News Management
    Route::prefix('informasi')->name('informasi.')->group(function () {
        Route::view('/', 'pages.admin.informasi.index')->name('index');
        Route::view('/create', 'pages.admin.informasi.create')->name('create');
        Route::view('/{id}/edit', 'pages.admin.informasi.edit')->name('edit');
    });

    // Pesan Masuk & Permohonan Akses
    Route::view('/pesan', 'pages.admin.pesan.index')->name('pesan.index');
    Route::redirect('/token-berkas', '/admin/pesan');

    // User / Admin Management
    Route::view('/users', 'pages.admin.users.index')->name('users.index');


    // PDF Inline Preview for temporary uploads
    Route::get('/preview-pdf/{filename}', function ($filename) {
        $filename = basename($filename);
        $possiblePaths = [
            storage_path('app/livewire-tmp/' . $filename),
            storage_path('app/private/livewire-tmp/' . $filename),
            storage_path('app/public/livewire-tmp/' . $filename),
        ];

        $path = null;
        foreach ($possiblePaths as $p) {
            if (file_exists($p)) {
                $path = $p;
                break;
            }
        }

        if (!$path) {
            abort(404, 'File PDF tidak ditemukan');
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    })->name('preview.pdf');

});
