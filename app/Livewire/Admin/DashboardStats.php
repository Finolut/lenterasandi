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
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_kpis', 300, function () {
            $startOfWeek = now()->startOfWeek();
            $startOfMonth = now()->startOfMonth();

            // 1. Ebook Aggregated Metrics in single query
            $ebookAgg = Ebook::selectRaw("
                count(*) as total,
                coalesce(sum(jumlah_dibaca), 0) as total_pembaca,
                coalesce(sum(jumlah_unduhan), 0) as total_unduhan,
                count(case when created_at >= ? then 1 end) as minggu_ini,
                count(case when created_at >= ? then 1 end) as bulan_ini,
                count(case when status = 'Publik' then 1 end) as publik,
                count(case when status != 'Publik' then 1 end) as draf
            ", [$startOfWeek, $startOfMonth])->first();

            // 2. Informasi Aggregated Metrics in single query
            $infoAgg = Informasi::selectRaw("
                count(*) as total,
                coalesce(sum(jumlah_dilihat), 0) as total_views,
                count(case when created_at >= ? then 1 end) as minggu_ini,
                count(case when created_at >= ? then 1 end) as bulan_ini,
                count(case when status = 'Publik' then 1 end) as publik,
                count(case when status != 'Publik' then 1 end) as draf
            ", [$startOfWeek, $startOfMonth])->first();

            // 3. Category Breakdown for E-Books
            $ebookCategoryStats = Ebook::select('kategori', DB::raw('count(*) as count'))
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->groupBy('kategori')
                ->pluck('count', 'kategori')
                ->toArray();

            return [
                'totalEbook'         => (int) ($ebookAgg->total ?? 0),
                'totalInformasi'     => (int) ($infoAgg->total ?? 0),
                'totalPembaca'       => (int) ($ebookAgg->total_pembaca ?? 0),
                'totalUnduhan'       => (int) ($ebookAgg->total_unduhan ?? 0),
                'totalViews'         => (int) ($infoAgg->total_views ?? 0),
                'ebookMingguIni'     => (int) ($ebookAgg->minggu_ini ?? 0),
                'ebookBulanIni'      => (int) ($ebookAgg->bulan_ini ?? 0),
                'informasiMingguIni' => (int) ($infoAgg->minggu_ini ?? 0),
                'informasiBulanIni'  => (int) ($infoAgg->bulan_ini ?? 0),
                'ebookCategoryStats' => $ebookCategoryStats,
                'ebookPublik'        => (int) ($ebookAgg->publik ?? 0),
                'ebookDraf'          => (int) ($ebookAgg->draf ?? 0),
                'informasiPublik'    => (int) ($infoAgg->publik ?? 0),
                'informasiDraf'      => (int) ($infoAgg->draf ?? 0),
            ];
        });

        // Combined Pesan Stats
        $pesanStats = \App\Models\PermohonanAkses::selectRaw("
            count(*) as total,
            count(case when dibaca_at is null then 1 end) as unread
        ")->first();

        $stats['totalPesan']     = (int) ($pesanStats->total ?? 0);
        $stats['unreadPesan']    = (int) ($pesanStats->unread ?? 0);

        // Recent items with lightweight selected columns (termasuk cover/thumbnail)
        $stats['recentEbooks']   = Ebook::with('creator:id,name')->select(['id', 'judul', 'kategori', 'status', 'cover_url', 'file_url', 'created_by', 'created_at', 'jumlah_dibaca', 'jumlah_unduhan'])->latest()->limit(5)->get();
        $stats['recentArticles'] = Informasi::with('creator:id,name')->select(['id', 'judul', 'kategori', 'status', 'gambar_url', 'created_by', 'published_at', 'jumlah_dilihat'])->latest('published_at')->limit(5)->get();
        // Prioritize Pending first, then by latest created_at
        $stats['recentMessages'] = \App\Models\PermohonanAkses::with('informasi:id,judul')
            ->select(['id', 'informasi_id', 'nama_lengkap', 'instansi', 'status', 'dibaca_at', 'created_at'])
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard-stats', $stats);
    }
}
