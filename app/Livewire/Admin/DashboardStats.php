<?php

namespace App\Livewire\Admin;

use App\Models\Ebook;
use App\Models\Informasi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_kpis', 30, function () {
            // 1. Core KPIs
            $totalEbook     = Ebook::count();
            $totalInformasi = Informasi::count();
            $totalUnduhan   = (int) Ebook::sum('jumlah_unduhan');
            $totalViews     = (int) Informasi::sum('jumlah_dilihat');

            // 2. Growth this week & month
            $startOfWeek = now()->startOfWeek();
            $startOfMonth = now()->startOfMonth();

            $ebookMingguIni     = Ebook::where('created_at', '>=', $startOfWeek)->count();
            $ebookBulanIni      = Ebook::where('created_at', '>=', $startOfMonth)->count();
            $informasiMingguIni = Informasi::where('created_at', '>=', $startOfWeek)->count();
            $informasiBulanIni  = Informasi::where('created_at', '>=', $startOfMonth)->count();

            // 3. Category Breakdown for E-Books
            $ebookCategoryStats = Ebook::select('kategori', DB::raw('count(*) as count'))
                ->groupBy('kategori')
                ->pluck('count', 'kategori')
                ->toArray();

            // 4. Status Breakdown
            $ebookPublik = Ebook::where('status', 'Publik')->count();
            $ebookDraf   = Ebook::where('status', '!=', 'Publik')->count();

            $informasiPublik = Informasi::where('status', 'Publik')->count();
            $informasiDraf   = Informasi::where('status', '!=', 'Publik')->count();

            return [
                'totalEbook'         => $totalEbook,
                'totalInformasi'     => $totalInformasi,
                'totalUnduhan'       => $totalUnduhan,
                'totalViews'         => $totalViews,
                'ebookMingguIni'     => $ebookMingguIni,
                'ebookBulanIni'      => $ebookBulanIni,
                'informasiMingguIni' => $informasiMingguIni,
                'informasiBulanIni'  => $informasiBulanIni,
                'ebookCategoryStats' => $ebookCategoryStats,
                'ebookPublik'        => $ebookPublik,
                'ebookDraf'          => $ebookDraf,
                'informasiPublik'    => $informasiPublik,
                'informasiDraf'      => $informasiDraf,
            ];
        });

        // Query recent records directly without storing serialized Collections in cache
        $stats['recentEbooks']   = Ebook::with('creator')->latest()->limit(5)->get();
        $stats['recentArticles'] = Informasi::with('creator')->latest('published_at')->limit(5)->get();
        $stats['totalPesan']     = \App\Models\PermohonanAkses::count();
        $stats['unreadPesan']    = \App\Models\PermohonanAkses::whereNull('dibaca_at')->count();
        $stats['recentMessages'] = \App\Models\PermohonanAkses::with('informasi')->latest()->limit(5)->get();

        return view('livewire.admin.dashboard-stats', $stats);
    }
}
