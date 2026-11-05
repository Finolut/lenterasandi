<?php

namespace App\Services;

use App\Models\Informasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JatimprovArticleService
{
    protected string $baseUrl = 'https://kominfo.jatimprov.go.id';

    /**
     * Fetch and synchronize the latest articles from Diskominfo Jatim across multiple pages.
     *
     * @param int $limit Maximum number of new articles to import
     * @param int $maxPages Maximum number of pagination pages to scan
     * @param int $startPage The starting page number
     * @return array{success: bool, total_scanned: int, imported: int, skipped: int, pages_scanned: int, articles: array}
     */
    public function sync(int $limit = 25, int $maxPages = 5, int $startPage = 1): array
    {
        @set_time_limit(180);

        $imported = 0;
        $skipped = 0;
        $totalScanned = 0;
        $pagesScanned = 0;
        $importedArticles = [];

        $adminId = auth()->id() ?? User::first()?->id;

        for ($page = $startPage; $page < ($startPage + $maxPages); $page++) {
            $pageUrl = $page === 1
                ? $this->baseUrl . '/berita'
                : $this->baseUrl . '/berita?page=' . $page;

            $indexHtml = $this->fetchUrl($pageUrl);

            if (! $indexHtml) {
                if ($page === $startPage && $imported === 0) {
                    return [
                        'success' => false,
                        'message' => "Gagal menghubungi portal berita Diskominfo Jatim pada halaman {$page}.",
                        'total_scanned' => 0,
                        'imported' => 0,
                        'skipped' => 0,
                        'pages_scanned' => 0,
                        'articles' => [],
                    ];
                }
                break;
            }

            $pagesScanned++;

            // Extract article relative URLs from this page
            preg_match_all('/<a[^>]+href="(\/berita\/[^"]+)"[^>]*>/i', $indexHtml, $matches);

            $urls = array_values(array_unique(array_filter($matches[1] ?? [], function ($u) {
                return strpos($u, '?') === false && $u !== '/berita/cari' && $u !== '/berita';
            })));

            if (empty($urls)) {
                break;
            }

            foreach ($urls as $relUrl) {
                $totalScanned++;

                // Fast check by slug first
                $urlSlug = basename($relUrl);
                if (Informasi::where('slug', $urlSlug)->exists()) {
                    $skipped++;
                    continue;
                }

                $fullUrl = $this->baseUrl . $relUrl;
                $detailHtml = $this->fetchUrl($fullUrl);

                if (! $detailHtml) {
                    continue;
                }

                $articleData = $this->parseArticle($detailHtml, $fullUrl);

                if (! $articleData || empty($articleData['headline'])) {
                    continue;
                }

                $title = trim($articleData['headline']);

                // Check if article already exists by title
                $exists = Informasi::where('judul', $title)
                    ->orWhere('slug', Str::slug($title))
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Generate unique slug
                $slug = Str::slug($title);
                $count = 1;
                while (Informasi::where('slug', $slug)->exists()) {
                    $slug = Str::slug($title) . '-' . $count++;
                }

                // Format body content into HTML paragraphs
                $formattedContent = $this->formatContent($articleData['body'] ?? '', $fullUrl);

                // Determine relevant categories
                $kategori = $this->determineCategory($title, $articleData['keywords'] ?? [], $articleData['body'] ?? '');

                $publishedAt = null;
                if (! empty($articleData['datePublished'])) {
                    try {
                        $publishedAt = Carbon::parse($articleData['datePublished']);
                    } catch (\Throwable $e) {
                        $publishedAt = now();
                    }
                } else {
                    $publishedAt = now();
                }

                $informasi = Informasi::create([
                    'judul' => $title,
                    'slug' => $slug,
                    'konten' => $formattedContent,
                    'ringkasan' => Str::limit(strip_tags($articleData['description'] ?? $articleData['body'] ?? ''), 250),
                    'gambar_url' => $articleData['image'] ?? null,
                    'kategori' => $kategori,
                    'status' => 'Publik',
                    'published_at' => $publishedAt,
                    'jumlah_dilihat' => rand(5, 40),
                    'created_by' => $adminId,
                ]);

                $imported++;
                $importedArticles[] = [
                    'id' => $informasi->id,
                    'judul' => $informasi->judul,
                    'kategori' => $informasi->kategori,
                    'halaman' => $page,
                ];

                if ($imported >= $limit) {
                    break 2;
                }
            }
        }

        // Clear relevant caches
        Cache::forget('home_recent_news');
        Cache::forget('admin_news_categories');
        Cache::forget('admin_news_total');
        Cache::forget('admin_sidebar_counts');
        Cache::forget('admin_dashboard_kpis');

        return [
            'success' => true,
            'pages_scanned' => $pagesScanned,
            'total_scanned' => $totalScanned,
            'imported' => $imported,
            'skipped' => $skipped,
            'articles' => $importedArticles,
        ];
    }

    /**
     * Perform HTTP GET request via cURL with standard browser headers to bypass simple bot checks.
     */
    protected function fetchUrl(string $url): ?string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: id,en-US;q=0.9,en;q=0.8',
            'Cache-Control: no-cache',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300 && $response) {
            return $response;
        }

        if ($error) {
            Log::warning("JatimprovArticleService fetch failed for {$url}: {$error}");
        }

        return null;
    }

    /**
     * Parse article details from HTML (JSON-LD schema with OpenGraph fallback).
     */
    protected function parseArticle(string $html, string $sourceUrl): ?array
    {
        $data = [];

        // 1. Try to parse JSON-LD NewsArticle
        if (preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m)) {
            $json = json_decode($m[1], true);
            if (is_array($json) && ($json['@type'] ?? '') === 'NewsArticle') {
                $data['headline'] = $json['headline'] ?? null;
                $data['description'] = $json['description'] ?? null;
                $data['body'] = $json['articleBody'] ?? null;
                $data['image'] = is_array($json['image'] ?? null) ? ($json['image'][0] ?? null) : ($json['image'] ?? null);
                $data['datePublished'] = $json['datePublished'] ?? null;
                $data['keywords'] = $json['keywords'] ?? [];
            }
        }

        // 2. OpenGraph Fallbacks if fields are missing
        if (empty($data['headline']) && preg_match('/<meta property="og:title" content="([^"]+)"/i', $html, $m)) {
            $data['headline'] = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        if (empty($data['description']) && preg_match('/<meta property="og:description" content="([^"]+)"/i', $html, $m)) {
            $data['description'] = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        if (empty($data['image']) && preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $m)) {
            $data['image'] = $m[1];
        }

        if (empty($data['body'])) {
            // Fallback: extract from article container in HTML
            if (preg_match('/<article[^>]*>(.*?)<\/article>/is', $html, $artMatch)) {
                $cleanArticle = strip_tags($artMatch[1]);
                $data['body'] = trim($cleanArticle);
            }
        }

        return ! empty($data['headline']) ? $data : null;
    }

    /**
     * Format raw text into responsive HTML paragraphs and append source attribution.
     */
    protected function formatContent(string $rawBody, string $sourceUrl): string
    {
        $trimmed = trim($rawBody);
        if (empty($trimmed)) {
            return '';
        }

        // Split by existing newlines or double spaces
        $paragraphs = preg_split('/(\r\n|\n|\r){2,}/', $trimmed);

        // If it's a single massive wall of text without double newlines, split by sentence clumps
        if (count($paragraphs) <= 1 && strlen($trimmed) > 300) {
            $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z0-9"“])/', $trimmed);
            $chunks = array_chunk($sentences, 3);
            $paragraphs = array_map(fn ($c) => implode(' ', $c), $chunks);
        }

        $html = '';
        foreach ($paragraphs as $p) {
            $p = trim($p);
            if (! empty($p)) {
                $html .= '<p class="mb-4 leading-relaxed text-slate-700 dark:text-slate-300">' . htmlspecialchars($p, ENT_QUOTES, 'UTF-8') . '</p>' . "\n";
            }
        }

        // Add verified source credit footer
        $html .= '<div class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 flex items-center justify-between flex-wrap gap-2">';
        $html .= '<span>Sumber Berita Resmi: <a href="' . htmlspecialchars($sourceUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" class="text-sky-600 dark:text-sky-400 font-medium hover:underline inline-flex items-center gap-1">Dinas Komunikasi dan Informatika Provinsi Jawa Timur <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a></span>';
        $html .= '<span class="inline-flex items-center gap-1 text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded font-medium">Diskominfo Jatim</span>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Determine best category based on keywords and content.
     */
    protected function determineCategory(string $title, array $keywords, string $body): string
    {
        $haystack = strtolower($title . ' ' . implode(' ', $keywords) . ' ' . substr($body, 0, 500));

        if (Str::contains($haystack, ['pelantikan', 'kegiatan', 'sosialisasi', 'workshop', 'pelatihan', 'forum', 'bina', 'kunjungan', 'gelar'])) {
            return 'Kegiatan';
        }

        if (Str::contains($haystack, ['pengumuman', 'pendaftaran', 'imbauan', 'waspada', 'peringatan', 'advisory'])) {
            return 'Pengumuman';
        }

        return 'Informasi';
    }
}
