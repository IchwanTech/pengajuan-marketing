<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKontrak;
use App\Models\User;
use App\Services\NomorKontrakService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            case 'Kedinasan':
                $pdf = Pdf::loadView('admin.kontrak.template-kedinasan', compact('suratKontrak', 'angsuran'));
                break;

            case 'Kedinasan & Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-kedinasan-agunan', compact('suratKontrak', 'angsuran'));
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

            case 'UMKM':
                $pdf = Pdf::loadView('admin.kontrak.template-umkm', compact('suratKontrak', 'angsuran'));
                break;

            case 'UMKM Agunan':
                $pdf = Pdf::loadView('admin.kontrak.template-umkm-agunan', compact('suratKontrak', 'angsuran'));
                break;

            default:
                $pdf = Pdf::loadView('admin.template-kontrak', compact('suratKontrak', 'angsuran'));
                break;
        }

        return $pdf->stream('Kontrak an ' . $suratKontrak->nama . '.pdf');
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
