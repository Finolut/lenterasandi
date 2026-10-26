<?php

namespace App\Livewire;

use Livewire\Component;

class PdfReader extends Component
{
    public int $currentPage = 5;
    public int $totalPages = 32;
    public int $zoom = 100;
    public string $title = 'Panduan Membuat Kata Sandi yang Kuat';
    public string $searchQuery = '';
    public bool $isSidebarOpen = false;

    public function nextPage(): void
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }

    public function prevPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function goToPage(int $page): void
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
        }
    }

    public function zoomIn(): void
    {
        if ($this->zoom < 200) {
            $this->zoom += 25;
        }
    }

    public function zoomOut(): void
    {
        if ($this->zoom > 50) {
            $this->zoom -= 25;
        }
    }

    public function toggleSidebar(): void
    {
        $this->isSidebarOpen = !$this->isSidebarOpen;
    }

    public function render()
    {
        return view('livewire.pdf-reader');
    }
}
