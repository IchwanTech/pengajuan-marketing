<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKontrak;
use App\Models\User;
use App\Services\NomorKontrakService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class KontrakController extends Controller
{
    public function index()
    {
        $kontrak = SuratKontrak::orderBy('created_at', 'desc')->get();
        $marketing = User::where('usertype', 'marketing')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        $ca = User::where('usertype', 'credit')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.suratkontrak', compact('kontrak', 'marketing', 'ca'));
    }

    public function store(Request $request, NomorKontrakService $nomorKontrakService)
    {
        $gen = $nomorKontrakService->generate($request->type, $request->perusahaan);

        $request->validate([
            'nomor_kontrak' => 'required',
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string',
            'type' => 'required|string',
            'pokok_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'cicilan' => 'required|numeric',
            'biaya_admin' => 'required|numeric',
            'biaya_layanan' => 'required|numeric',
            'bunga' => 'required|numeric',
            'tanggal_jatuh_tempo' => 'required|date',
            'inisial_marketing' => 'required|string',
            'golongan' => 'nullable|string',
            'perusahaan' => 'nullable|string',
            'inisial_ca' => 'nullable|string',
            'id_card' => 'nullable|string',
            'kedinasan' => 'nullable|string',
            'pinjaman_ke' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $perusahaan = $request->perusahaan;

        if (in_array($request->type, ['Borongan', 'Borongan BPJS'])) {
            $perusahaan = $request->perusahaan_borongan;
        }

        SuratKontrak::create([
            'nomor_kontrak' => $request->nomor_kontrak,
            'nomor_urut' => $gen['nomor_urut'],
            'kelompok' => $gen['kelompok'],
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'type' => $request->type,
            'inisial_marketing' => $request->inisial_marketing,
            'golongan' => $request->golongan,
            'perusahaan' => $perusahaan,
            'inisial_ca' => $request->inisial_ca,
            'id_card' => $request->id_card,
            'kedinasan' => $request->kedinasan,
            'pinjaman_ke' => $request->pinjaman_ke,
            'pokok_pinjaman' => $request->pokok_pinjaman,
            'tenor' => $request->tenor,
            'cicilan' => $request->cicilan,
            'biaya_admin' => $request->biaya_admin,
            'biaya_layanan' => $request->biaya_layanan,
            'bunga' => $request->bunga,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.surat.kontrak')->with('success', 'Surat Kontrak Berhasil Dibuat');
    }

    public function generateNomor(Request $request, $type, NomorKontrakService $service)
    {
        try {
            Log::info("Generate nomor untuk type: " . $type);
            $data = $service->generate($type, $request->get('perusahaan'));
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error generate nomor: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $suratKontrak = SuratKontrak::find($id);
        $angsuran = $this->generateAngsuran($suratKontrak);
        $pdf = $this->generatePdf($suratKontrak, $angsuran);

        $filename = 'Kontrak an - ' . str_replace(['/', '\\', ','], '-', $suratKontrak->nama) . '.pdf';

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    private function generateAngsuran($suratKontrak)
    {
        $angsuran = [];
        $tanggalMulai = Carbon::parse($suratKontrak->tanggal_jatuh_tempo)->locale('id');

        if ($this->isBoronganType($suratKontrak->type)) {
            $angsuran = $this->generateBoronganAngsuran($suratKontrak, $tanggalMulai);
        } else {
            $angsuran = $this->generateBulananAngsuran($suratKontrak, $tanggalMulai);
        }

        return $angsuran;
    }

    private function isBoronganType($type)
    {
        return in_array($type, ['Borongan', 'Borongan BPJS']);
    }

    private function generateBoronganAngsuran($suratKontrak, $tanggalMulai)
    {
        $angsuran = [];
        $jatuhTempo = (int) $tanggalMulai->format('d');
        $tanggal = $tanggalMulai->copy();

        for ($i = 1; $i <= $suratKontrak->tenor; $i++) {
            $periode = $this->calculateBoronganPeriode($jatuhTempo, $i, $tanggal);

            $angsuran[] = [
                'tanggal_mulai' => $periode['mulai']->translatedFormat('d M Y'),
                'tanggal_selesai' => $periode['selesai']->translatedFormat('d M Y'),
                'cicilan' => $suratKontrak->cicilan
            ];

            // Update tanggal ke periode berikutnya
            $tanggal = $periode['selesai']->copy()->addDay();
        }

        return $angsuran;
    }

    private function calculateBoronganPeriode($jatuhTempo, $iterasi, $tanggal)
    {
        $tanggalMulai = null;
        $tanggalSelesai = null;

        if ($jatuhTempo === 27) {
            if ($iterasi % 2 == 1) {
                // Periode ganjil: 27 -> 10 (bulan berikutnya)
                $tanggalMulai = $tanggal->copy()->day(27);
                $tanggalSelesai = $tanggalMulai->copy()->addMonthNoOverflow()->day(10);
            } else {
                // Periode genap: 11 -> 26
                $tanggalMulai = $tanggal->copy()->day(11);
                $tanggalSelesai = $tanggalMulai->copy()->day(26);
            }
        } elseif ($jatuhTempo === 11) {
            if ($iterasi % 2 == 1) {
                // Periode ganjil: 11 -> 26
                $tanggalMulai = $tanggal->copy()->day(11);
                $tanggalSelesai = $tanggalMulai->copy()->day(26);
            } else {
                // Periode genap: 27 -> 10 (bulan berikutnya)
                $tanggalMulai = $tanggal->copy()->day(27);
                $tanggalSelesai = $tanggalMulai->copy()->addMonthNoOverflow()->day(10);
            }
        }

        return [
            'mulai' => $tanggalMulai,
            'selesai' => $tanggalSelesai
        ];
    }

    private function generateBulananAngsuran($suratKontrak, $tanggalMulai)
    {
        $angsuran = [];
        $currentDate = $tanggalMulai->copy();
        $originalDay = $tanggalMulai->day; // Simpan tanggal asli (misal: 30)

        // DEBUG: Log tanggal awal 
        // Log::info('Tanggal awal: ' . $currentDate->format('d-m-Y'));
        // Log::info('Original day: ' . $originalDay);

        for ($i = 1; $i <= $suratKontrak->tenor; $i++) {
            // add array list ke angsuran (penting biar februarinya ga keskip atau ditimpa)
            $angsuran[] = [
                'tanggal' => $currentDate->translatedFormat('d F Y'),
                'cicilan' => $suratKontrak->cicilan
            ];

            // DEBUG: Log Iterasi looping buat ngeliat februari bener-bener ada di iterasi atau engga (alias ga ketiban)
            // Log::info("Iterasi {$i}: " . $currentDate->format('d-m-Y'));

            // Lanjut ke Next Month
            $nextMonth = $currentDate->month + 1;
            $nextYear = $currentDate->year;

            // Handle pergantian tahun
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }

            // Adjust tanggal balik ke original variabel yang di input diawal
            // ini berfungsi biar next month ga ngecopy value bulan februari yang di adjust nilainya (mentok 28)
            $adjustedDay = $this->getAdjustedDay($originalDay, $nextMonth, $nextYear);

            // DEBUG: Log Semua kalkulasinya (nampilin seluruh data yang bisa dibilang udah clean)
            // Log::info("Next: {$nextYear}-{$nextMonth}, Adjusted day: {$adjustedDay}");

            $currentDate = Carbon::create($nextYear, $nextMonth, $adjustedDay)->locale('id');
        }

        // DEBUG: Log hasil akhir
        Log::info('Total angsuran: ' . count($angsuran));
        foreach ($angsuran as $key => $item) {
            Log::info("Angsuran " . ($key + 1) . ": " . $item['tanggal']);
        }

        return $angsuran;
    }

    private function getAdjustedDay($originalDay, $month, $year)
    {
        // Khusus Februari
        if ($month == 2) {
            $isLeapYear = $year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0);
            $maxDayInFeb = $isLeapYear ? 29 : 28;

            return min($originalDay, $maxDayInFeb);
        }

        // Bulan lain: pakai tanggal asli, tapi sesuaikan dengan max hari di bulan tersebut
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        return min($originalDay, $daysInMonth);
    }

    private function generatePdf($suratKontrak, $angsuran)
    {
        $nama_nasabah = $suratKontrak->nama;
        Log::info('Nama Nasabah: ' . $nama_nasabah); // Debug ke log

        $templateMap = [
            'Internal' => 'admin.kontrak.template-internal',
            'Internal Agunan' => 'admin.kontrak.template-internal-agunan',
            'Internal BPJS' => 'admin.kontrak.template-internal-bpjs',
            'Borongan' => 'admin.kontrak.template-borongan',
            'Borongan BPJS' => 'admin.kontrak.template-borongan-bpjs',
            'Kedinasan' => 'admin.kontrak.template-kedinasan',
            'Kedinasan & Agunan' => 'admin.kontrak.template-kedinasan-agunan',
            'Kedinasan & Taspen' => 'admin.kontrak.template-kedinasan-taspen',
            'PT Luar' => 'admin.kontrak.template-luar-sf',
            'PT Luar Agunan' => 'admin.kontrak.template-luar-sf-agunan',
            'PT Luar BPJS' => 'admin.kontrak.template-luar-sf-bpjs',
            'PT Luar BPJS Agunan' => 'admin.kontrak.template-luar-sf-bpjs-agunan',
            'PT SF' => 'admin.kontrak.template-luar-sf',
            'PT SF Agunan' => 'admin.kontrak.template-luar-sf-agunan',
            'PT SF BPJS' => 'admin.kontrak.template-luar-sf-bpjs',
            'PT SF BPJS Agunan' => 'admin.kontrak.template-luar-sf-bpjs-agunan',
            'UMKM' => 'admin.kontrak.template-umkm',
            'UMKM Agunan' => 'admin.kontrak.template-umkm-agunan',
        ];

        $template = $templateMap[$suratKontrak->type] ?? 'admin.template-kontrak';

        return Pdf::loadView($template, compact('suratKontrak', 'angsuran'));
    }

    public function update(Request $request, $id, NomorKontrakService $nomorKontrakService)
    {
        $gen = $nomorKontrakService->generate($request->type, $request->perusahaan);

        $request->validate([
            'nomor_kontrak' => 'required',
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string',
            'type' => 'required|string',
            'pokok_pinjaman' => 'required|numeric',
            'tenor' => 'required|numeric',
            'cicilan' => 'required|numeric',
            'biaya_admin' => 'required|numeric',
            'biaya_layanan' => 'required|numeric',
            'bunga' => 'required|numeric',
            'tanggal_jatuh_tempo' => 'required|date',
            'inisial_marketing' => 'required|string',
            'golongan' => 'nullable|string',
            'perusahaan' => 'nullable|string',
            'inisial_ca' => 'nullable|string',
            'id_card' => 'nullable|string',
            'kedinasan' => 'nullable|string',
            'pinjaman_ke' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $perusahaan = $request->perusahaan;

        if (in_array($request->type, ['Borongan', 'Borongan BPJS'])) {
            $perusahaan = $request->perusahaan_borongan;
        }

        $kontrak = SuratKontrak::find($id);
        $kontrak->update([
            'nomor_kontrak' => $request->nomor_kontrak,
            'nomor_urut' => $gen['nomor_urut'],
            'kelompok' => $gen['kelompok'],
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'type' => $request->type,
            'inisial_marketing' => $request->inisial_marketing,
            'golongan' => $request->golongan,
            'perusahaan' => $perusahaan,
            'inisial_ca' => $request->inisial_ca,
            'id_card' => $request->id_card,
            'kedinasan' => $request->kedinasan,
            'pinjaman_ke' => $request->pinjaman_ke,
            'pokok_pinjaman' => $request->pokok_pinjaman,
            'tenor' => $request->tenor,
            'cicilan' => $request->cicilan,
            'biaya_admin' => $request->biaya_admin,
            'biaya_layanan' => $request->biaya_layanan,
            'bunga' => $request->bunga,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'catatan' => $request->catatan,
        ]);

        return response()->json(['success' => true]);
        // return redirect()->route('admin.surat.kontrak')->with('success', 'Surat Kontrak Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $kontrak = SuratKontrak::find($id);
        $kontrak->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('admin.surat.kontrak')->with('success', 'Surat Kontrak Berhasil Dihapus');
    }

    public function download($id)
    {
        $suratKontrak = SuratKontrak::find($id);

        $angsuran = [];
        $tanggalMulai = Carbon::parse($suratKontrak->tanggal_jatuh_tempo)->locale('id');

        // Jika tipe kontrak adalah "Borongan" atau "Borongan BPJS", gunakan sistem per 2 minggu
        if (in_array($suratKontrak->type, ['Borongan', 'Borongan BPJS'])) {
            $jatuhTempo = (int) $tanggalMulai->format('d');
            $tanggal = $tanggalMulai->copy();

            for ($i = 1; $i <= $suratKontrak->tenor; $i++) {
                // Inisialisasi tanggal mulai dan selesai
                if ($jatuhTempo === 27) {
                    if ($i % 2 == 1) {
                        // Periode pertama atau ganjil: 27 -> 10 (bulan berikutnya)
                        $tanggalMulai = $tanggal->copy()->day(27);
                        $tanggalSelesai = $tanggalMulai->copy()->addMonthNoOverflow()->day(10);
                    } else {
                        // Periode genap: 11 -> 26
                        $tanggalMulai = $tanggal->copy()->day(11);
                        $tanggalSelesai = $tanggalMulai->copy()->day(26);
                    }
                } elseif ($jatuhTempo === 11) {
                    if ($i % 2 == 1) {
                        // Periode pertama atau ganjil: 11 -> 26
                        $tanggalMulai = $tanggal->copy()->day(11);
                        $tanggalSelesai = $tanggalMulai->copy()->day(26);
                    } else {
                        // Periode genap: 27 -> 10 (bulan berikutnya)
                        $tanggalMulai = $tanggal->copy()->day(27);
                        $tanggalSelesai = $tanggalMulai->copy()->addMonthNoOverflow()->day(10);
                    }
                }

                $angsuran[] = [
                    'tanggal_mulai' => $tanggalMulai->translatedFormat('d M Y'),
                    'tanggal_selesai' => $tanggalSelesai->translatedFormat('d M Y'),
                    'cicilan' => $suratKontrak->cicilan
                ];

                // Update $tanggal ke periode berikutnya
                $tanggal = $tanggalSelesai->copy()->addDay();
            }
        } else {
            // Jika bukan borongan, sistem bulanan
            for ($i = 1; $i <= $suratKontrak->tenor; $i++) {
                $angsuran[] = [
                    'tanggal' => $tanggalMulai->translatedFormat('d F Y'),
                    'cicilan' => $suratKontrak->cicilan
                ];
                $tanggalMulai->addMonth();
            }
        }

        $pdf = [];

        $type = $suratKontrak->type;

        switch ($type) {
            case 'Internal':
                $pdf = Pdf::loadView('admin.kontrak.template-internal', compact('suratKontrak', 'angsuran'));
                break;

            case 'Internal BPJS':
                $pdf = Pdf::loadView('admin.kontrak.template-internal-bpjs', compact('suratKontrak', 'angsuran'));
                break;

            case 'Borongan':
                $pdf = Pdf::loadView('admin.kontrak.template-borongan', compact('suratKontrak', 'angsuran'));
                break;

            case 'Borongan BPJS':
                $pdf = Pdf::loadView('admin.kontrak.template-borongan-bpjs', compact('suratKontrak', 'angsuran'));
                break;

            case 'Kedinasan & Taspen':
                $pdf = Pdf::loadView('admin.kontrak.template-kedinasan-taspen', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT Luar':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT Luar Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-agunan', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT Luar BPJS':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-bpjs', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT Luar BPJS Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-bpjs-agunan', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT SF':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT SF Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-agunan', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT SF BPJS':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-bpjs', compact('suratKontrak', 'angsuran'));
                break;

            case 'PT SF BPJS Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-luar-sf-bpjs-agunan', compact('suratKontrak', 'angsuran'));
                break;

            default:
                $pdf = Pdf::loadView('admin.kontrak.template-internal', compact('suratKontrak', 'angsuran'));
                break;
        }

        return $pdf->download('Kontrak an ' . $suratKontrak->nama . '.pdf');
    }
}
