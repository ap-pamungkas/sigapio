<?php

namespace App\Http\Controllers\Api;


use App\Models\LogInsiden;
use App\Models\PetugasInsiden;
use App\Models\Perangkat;
use App\Models\Insiden;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsidenLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'no_seri' => 'required|exists:perangkat,no_seri',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'kualitas_udara' => 'nullable|numeric',
            'darurat' => 'nullable|boolean',
        ]);

        $perangkat = Perangkat::where('no_seri', $request->no_seri)->first();

        if (!$perangkat) {
            return response()->json(['error' => 'Perangkat tidak ditemukan'], 404);
        }

        $petugasInsiden = PetugasInsiden::where('perangkat_id', $perangkat->id)->latest()->first();

        if (!$petugasInsiden) {
            return response()->json(['error' => 'Petugas atau insiden tidak ditemukan'], 404);
        }

        $insiden = Insiden::find($petugasInsiden->insiden_id);

        // Cek jika latitude dan longitude belum diatur (data pertama)
        if ($insiden && 
            ($insiden->latitude === null || $insiden->longitude === null) && 
            $request->latitude !== '0' && 
            $request->longitude !== '0'
        ) {
            $insiden->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

        $logData = [
            'insiden_id' => $petugasInsiden->insiden_id,
            'petugas_insiden_id' => $petugasInsiden->id,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'suhu' => $request->suhu,
            'kualitas_udara' => $request->kualitas_udara,
            'darurat' => $request->darurat,
        ];

        $insidenLog = LogInsiden::create($logData);

        return response()->json([
            'message' => 'Log insiden berhasil disimpan',
            'data' => $insidenLog
        ], 201);
    }
}
