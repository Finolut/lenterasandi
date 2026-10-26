@props([
    'title' => 'Lentera Sandi - Portal Edukasi Keamanan Informasi',
    'active' => '',
    'hideNavbar' => false,
    'hideFooter' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    {{-- Dark Mode Early Initialization Script (No-FOUC) --}}
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();

        window.toggleTheme = function() {
            try {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: !isDark } }));
            } catch (e) {}
        };

        document.addEventListener('livewire:navigated', function() {
            try {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        });
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- PDF.js Same-Origin Local Asset (Deferred and Cached for instant SPA navigation) -->
    <script defer data-navigate-once src="{{ asset('vendor/pdfjs/pdf.min.js') }}"></script>
    <script data-navigate-once>
        window.renderPdfCovers = async function() {
            if (typeof pdfjsLib === 'undefined') {
                setTimeout(window.renderPdfCovers, 150);
                return;
            }
            if (!pdfjsLib.GlobalWorkerOptions.workerSrc) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('vendor/pdfjs/pdf.worker.min.js') }}";
            }

            const items = document.querySelectorAll('.pdf-cover-wrap[data-pdf-url]:not([data-rendered="true"])');
            if (items.length === 0) return;

            items.forEach(async (el) => {
                el.setAttribute('data-rendered', 'true');
                const url = el.getAttribute('data-pdf-url');
                if (!url) return;

                const img = el.querySelector('.pdf-cover-img');
                const skeleton = el.querySelector('.pdf-skeleton');

                let fetchUrl = url;
                try {
                    const parsed = new URL(url, window.location.origin);
                    if (parsed.origin === window.location.origin) {
                        fetchUrl = parsed.pathname;
                    } else {
                        fetchUrl = parsed.href;
                    }
                } catch(e) {
                    fetchUrl = url;
                }

                // Check cache in sessionStorage for instant load
                const cacheKey = 'pdf_cover_v4_' + fetchUrl;
                try {
                    const cached = sessionStorage.getItem(cacheKey);
                    if (cached) {
                        if (img) {
                            img.src = cached;
                            img.classList.remove('hidden');
                        }
                        if (skeleton) skeleton.remove();
                        return;
                    }
                } catch(e) {}

                try {
                    const loadingTask = pdfjsLib.getDocument({
                        url: fetchUrl,
                        verbosity: 0,
                        withCredentials: false
                    });
                    const pdf = await loadingTask.promise;
                    const page = await pdf.getPage(1);
                    
                    const viewport = page.getViewport({ scale: 1.5 });
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    await page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    }).promise;

                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    try {
                        sessionStorage.setItem(cacheKey, dataUrl);
                    } catch(e) {}

                    if (img) {
                        img.src = dataUrl;
                        img.classList.remove('hidden');
                    }
                    if (skeleton) skeleton.remove();
                } catch (err) {
                    console.error('Gagal mengekstrak cover dari PDF (' + fetchUrl + '):', err);
                    if (skeleton) {
                        skeleton.classList.remove('animate-pulse');
                        skeleton.innerHTML = `
                            <div class="flex flex-col items-center justify-center p-3 text-center">
                                <div class="w-10 h-12 bg-[#c0392b] text-white font-bold text-xs flex items-center justify-center rounded shadow-xs mb-1">PDF</div>
                                <span class="text-[10px] text-[#64748b]">E-Book</span>
                            </div>
                        `;
                    }
                }
            });
        };

        // Attach event triggers
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', window.renderPdfCovers);
        } else {
            window.renderPdfCovers();
        }

        document.addEventListener('livewire:navigated', () => {
            setTimeout(window.renderPdfCovers, 50);
        });

        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                setTimeout(window.renderPdfCovers, 50);
            });
            Livewire.hook('commit', () => {
                setTimeout(window.renderPdfCovers, 50);
            });
        });
    </script>
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC] dark:bg-[#0b1329] text-[#0F172A] dark:text-[#f8fafc] antialiased transition-colors duration-200">
    @unless($hideNavbar)
        <x-navbar :active="$active" />
    @endunless

    <main class="flex-1 {{ (request()->routeIs('home') || $hideNavbar) ? '' : 'pt-24 md:pt-28' }}">
        {{ $slot }}
    </main>

    @unless($hideFooter)
        <x-footer />
    @endunless

    {{-- Global QR Code Modal --}}
    <x-qr-modal />

    @livewireScripts
</body>
</html>
