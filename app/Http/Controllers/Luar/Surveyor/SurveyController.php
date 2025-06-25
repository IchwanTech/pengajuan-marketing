<?php

namespace App\Http\Controllers\Luar\Surveyor;

use App\Http\Controllers\Controller;
use App\Models\Luar\FotoSurvey;
use App\Models\Luar\HasilSurveyPengajuan;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationCA;
use App\Models\Luar\PengajuanNasabahLuar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanNasabahLuar::whereIn('status_pengajuan', ['perlu survey', 'survey selesai'])
            ->whereHas('nasabahLuar.user', function ($query) {
                $query->where('usertype', 'marketing');
            })->with([
                'nasabahLuar.user',
                'nasabahLuar.alamatLuar',
                'nasabahLuar.pekerjaanLuar',
                'nasabahLuar.penjaminLuar',
                'nasabahLuar.tanggunganLuar',
                'nasabahLuar.pinjamanLainLuar',
                'nasabahLuar.kontakDarurat'
            ])
            ->get();
        // NasabahLuar::whereHas('user', function ($query) {
        //     $query->where('usertype', 'marketing');
        // })
        //     ->whereHas('pengajuanLuar', function ($query) {
        //         $query->whereIn('status_pengajuan', ['perlu survey', 'survey selesai']);
        //     })
        //     ->with([
        //         'user',
        //         'alamatLuar',
        //         'pekerjaanLuar',
        //         'penjaminLuar',
        //         'tanggunganLuar',
        //         'pinjamanLainLuar',
        //         'kontakDarurat',
        //         'pengajuanLuar'
        //     ])
        //     ->get();

        return view('pengajuan-luar.surveyor.hasil-survey', compact('pengajuan'));
    }

    public function formHasilSurvey($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        // $nasabah = NasabahLuar::where('id', $id)
        //     ->with([
        //         'user',
        //         'alamatLuar',
        //         'pekerjaanLuar',
        //         'penjaminLuar',
        //         'tanggunganLuar',
        //         'pinjamanLainLuar',
        //         'kontakDarurat',
        //         'pengajuanLuar'
        //     ])
        //     ->first();

        return view('pengajuan-luar.surveyor.form-hasil-survey', compact('pengajuan'));
    }

    public function store(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'berjumpa_siapa' => 'required|string',
            'hubungan_jumpa' => 'required|string',
            'status_rumah' => 'required|string',
            'hasil_cekling1' => 'required|string',
            'hasil_cekling2' => 'nullable|string',
            'kesimpulan' => 'required|string',
            'rekomendasi' => 'required|string',
            'foto_survey.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nasabah = $pengajuan->nasabahLuar;

        DB::beginTransaction();

        try {
            $hasilSurvey = HasilSurveyPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'berjumpa_siapa' => $request->berjumpa_siapa,
                'hubungan' => $request->hubungan_jumpa,
                'status_rumah' => $request->status_rumah,
                'hasil_cekling1' => $request->hasil_cekling1,
                'hasil_cekling2' => $request->hasil_cekling2,
                'kesimpulan' => $request->kesimpulan,
                'rekomendasi' => $request->rekomendasi,
            ]);

            // Buat folder berdasarkan nama nasabah
            $namaNasabah = str_replace(' ', '-', strtolower($nasabah->nama_lengkap));
            $folderDokumen = 'dokumen_pendukung_luar/' . $namaNasabah . '-' . $nasabah->id . '/hasil_survey';

            // Simpan foto rumah
            if ($request->hasFile('foto_survey')) {
                $fileCount = 1; // Mulai nomor dari 1
                foreach ($request->file('foto_survey') as $file) {
                    $RumahFileName = 'foto-rumah-' . $namaNasabah . '-' . $fileCount . '.' . $file->getClientOriginalExtension();
                    $file->storeAs($folderDokumen, $RumahFileName, 'public');

                    // Simpan path ke tabel foto_survey
                    FotoSurvey::create([
                        'hasil_survey_id' => $hasilSurvey->id,
                        'foto_survey' => $RumahFileName,
                    ]);

                    $fileCount++; // Increment nomor
                }
            }

            if ($pengajuan) {
                $pengajuan->update([
                    'status_pengajuan' => 'survey selesai',
                ]);
            }

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Hasil Survey Pengajuan ' . $nasabah->nama_lengkap . ' telah berhasil diinput. Silakan tinjau data hasil survey pada sistem untuk langkah selanjutnya.',
                'read' => false
            ]);

            DB::commit();

            return redirect()->route('surveyor.daftar.survey')->with('success', 'Hasil Survey Berhasil Disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error kirim data survey: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detailHasilSurvey($id)
    {
        $pengajuan = PengajuanNasabahLuar::with([
            'nasabahLuar',
            'hasilSurvey'
        ])
            ->findOrFail($id);

        return view('pengajuan-luar.surveyor.detail-hasil-survey', compact('pengajuan'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanNasabahLuar::with([
            'nasabahLuar',
            'hasilSurvey'
        ])
            ->findOrFail($id);

        return view('pengajuan-luar.surveyor.form-edit-hasil-survey', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'berjumpa_siapa' => 'required|string',
            'hubungan_jumpa' => 'required|string',
            'status_rumah' => 'required|string',
            'hasil_cekling1' => 'required|string',
            'hasil_cekling2' => 'nullable|string',
            'kesimpulan' => 'required|string',
            'rekomendasi' => 'required|string',
            'foto_survey.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nasabah = $pengajuan->nasabahLuar;

        DB::beginTransaction();

        try {
            // Cek jika hasil survey sudah ada untuk pengajuan ini
            $hasilSurvey = HasilSurveyPengajuan::where('pengajuan_id', $pengajuan->id)->first();

            if (!$hasilSurvey) {
                // Jika tidak ditemukan, buat baru
                $hasilSurvey = HasilSurveyPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'berjumpa_siapa' => $request->berjumpa_siapa,
                    'hubungan' => $request->hubungan_jumpa,
                    'status_rumah' => $request->status_rumah,
                    'hasil_cekling1' => $request->hasil_cekling1,
                    'hasil_cekling2' => $request->hasil_cekling2,
                    'kesimpulan' => $request->kesimpulan,
                    'rekomendasi' => $request->rekomendasi,
                ]);
            } else {
                // Update data yang sudah ada
                $hasilSurvey->update([
                    'berjumpa_siapa' => $request->berjumpa_siapa,
                    'hubungan' => $request->hubungan_jumpa,
                    'status_rumah' => $request->status_rumah,
                    'hasil_cekling1' => $request->hasil_cekling1,
                    'hasil_cekling2' => $request->hasil_cekling2,
                    'kesimpulan' => $request->kesimpulan,
                    'rekomendasi' => $request->rekomendasi,
                ]);
            }

            // Buat folder berdasarkan nama nasabah
            $namaNasabah = str_replace(' ', '-', strtolower($nasabah->nama_lengkap));
            $folderDokumen = 'dokumen_pendukung_luar/' . $namaNasabah . '-' . $nasabah->id . '/hasil_survey';

            // Simpan foto rumah jika ada
            if ($request->hasFile('foto_survey')) {
                $fileCount = FotoSurvey::where('hasil_survey_id', $hasilSurvey->id)->count() + 1; // Lanjutkan penomoran
                foreach ($request->file('foto_survey') as $file) {
                    $RumahFileName = 'foto-rumah-' . $namaNasabah . '-' . $fileCount . '.' . $file->getClientOriginalExtension();
                    $file->storeAs($folderDokumen, $RumahFileName, 'public');

                    // Simpan path ke tabel foto_survey
                    FotoSurvey::create([
                        'hasil_survey_id' => $hasilSurvey->id,
                        'foto_survey' => $RumahFileName,
                    ]);

                    $fileCount++; // Increment nomor
                }
            }

            if ($pengajuan) {
                $pengajuan->update([
                    'status_pengajuan' => 'survey selesai',
                ]);
            }

            NotificationCA::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Hasil Survey Pengajuan ' . $nasabah->nama_lengkap . ' telah diperbarui. Silakan tinjau data hasil survey pada sistem untuk langkah selanjutnya.',
                'read' => false
            ]);

            DB::commit();

            return redirect()->route('surveyor.daftar.survey')->with('success', 'Hasil Survey Berhasil Diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error update data survey: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
