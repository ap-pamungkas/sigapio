<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RotateActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:rotate-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotates the activity_log table by moving old logs to a new dated table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting activity log rotation...');

        $currentTableName = 'activity_log';
        $newTableName = 'activity_log_' . Carbon::now()->format('Y_m_d'); // Format YYYY_MM_DD

        // 1. Periksa apakah tabel baru sudah ada (untuk mencegah duplikasi jika command dijalankan manual)
        if (DB::getSchemaBuilder()->hasTable($newTableName)) {
            $this->error("Table '{$newTableName}' already exists. Aborting rotation.");
            return Command::FAILURE;
        }

        try {
            // 2. Buat tabel baru dengan struktur yang sama seperti activity_log
            DB::statement("CREATE TABLE {$newTableName} LIKE {$currentTableName}");
            $this->info("Table '{$newTableName}' created successfully.");

            // 3. Pindahkan semua data dari current_activity_log ke tabel baru
            // HATI-HATI: Ini akan memindahkan SEMUA data dari activity_log.
            // Pastikan tidak ada aktivitas logging yang sedang berjalan saat ini.
            DB::statement("INSERT INTO {$newTableName} SELECT * FROM {$currentTableName}");
            $this->info("Data moved from '{$currentTableName}' to '{$newTableName}'.");

            // 4. Kosongkan tabel activity_log
            DB::table($currentTableName)->truncate(); // TRUNCATE lebih cepat dari DELETE
            $this->info("Table '{$currentTableName}' truncated successfully.");

            $this->info('Activity log rotation completed!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error during log rotation: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
