<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupabasePing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supabase:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping Supabase database to prevent project auto-freezing / pausing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('[' . now()->toDateTimeString() . '] Memulai ping ke database Supabase...');

        $startTime = microtime(true);

        try {
            // Jalankan query ringan untuk memicu aktivitas di Supabase
            $result = DB::select('SELECT 1 as keepalive, current_timestamp as server_time');
            $latency = round((microtime(true) - $startTime) * 1000, 2);

            $message = sprintf(
                'Supabase keepalive BERHASIL! Latency: %sms | Supabase time: %s',
                $latency,
                $result[0]->server_time ?? '-'
            );

            $this->info("<fg=green;options=bold>[BERHASIL]</> {$message}");
            Log::info("SupabasePing: {$message}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $latency = round((microtime(true) - $startTime) * 1000, 2);
            $errorMessage = sprintf(
                'Gagal terhubung ke Supabase (%sms): %s',
                $latency,
                $e->getMessage()
            );

            $this->error("<fg=red;options=bold>[GAGAL]</> {$errorMessage}");
            Log::error("SupabasePing: {$errorMessage}");

            return Command::FAILURE;
        }
    }
}
