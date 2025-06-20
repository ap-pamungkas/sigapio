<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendInsidenLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insiden:send-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim data insiden log ke endpoint API secara terus-menerus (simulasi)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deviceSerials = ['SN-PRK-001', 'SN-PRK-002', 'SN-PRK-004'];
        $url = 'http://localhost/sigap-io_v_0.2/api/insiden-log';

        $this->info("Mulai mengirim log insiden setiap 5 detik. Tekan Ctrl + C untuk berhenti.\n");

        while (true) {
            // Pilih no_seri secara acak
            $noSeri = $deviceSerials[array_rand($deviceSerials)];

            // Data acak
          $latitude = -1.8222 + mt_rand(-100, 100) / 10000;    // -1.8322 s/d -1.8122
        $longitude = 110.5231 + mt_rand(-100, 100) / 10000;  // 110.5131 s/d 110.5331
            $suhu = mt_rand(250, 350) / 10; // 25.0 - 35.0
            $kualitasUdara = mt_rand(10, 150);

            $payload = [
                'no_seri' => $noSeri,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'suhu' => $suhu,
                'kualitas_udara' => $kualitasUdara,
            ];

            try {
                $response = Http::post($url, $payload);

                if ($response->successful()) {
                    $this->info(now() . ' - Log berhasil dikirim: ' . json_encode($payload));
                } else {
                    $this->error(now() . ' - Gagal kirim: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                $this->error(now() . ' - Exception: ' . $e->getMessage());
            }

            sleep(2); // jeda antar kiriman
        }
    }
}
