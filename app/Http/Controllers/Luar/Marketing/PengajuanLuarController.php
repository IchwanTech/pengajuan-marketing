<?php

namespace App\Http\Controllers\Luar\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Luar\AlamatNasabahLuar;
use App\Models\Luar\KontakDaruratNasabahLuar;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationCA;
use App\Models\Luar\NotificationSPV;
use App\Models\Luar\PekerjaanNasabahLuar;
use App\Models\Luar\PengajuanBPJS;
use App\Models\Luar\PengajuanBpkb;
use App\Models\Luar\PengajuanKedinasan;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Luar\PengajuanSHM;
use App\Models\Luar\PenjaminNasabahLuar;
use App\Models\Luar\PinjamanLainNasabahLuar;
use App\Models\Luar\TanggunganNasabahLuar;
use App\Models\Pengajuan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\ElseIf_;

class PengajuanLuarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $marketing = User::where('usertype', 'marketing')
            ->where('id', $user->id)
            ->get();
        $pengajuan = NasabahLuar::where('marketing_id', $user->id)
            ->with([
                'alamatLuar',
                'pekerjaanLuar',
                'penjaminLuar',
                'tanggunganLuar',
                'pinjamanLainLuar',
                'kontakDarurat',
                'pengajuanLuar' => function ($query) {
                    $query->orderBy('created_at', 'asc'); // atau 'desc'
                }
            ])
            ->get();

        // Mengambil validasi untuk semua data
        $dataValidasi = $pengajuan->map(function ($item) {
            return [
                'validasi_nasabah' => $item->validasi_nasabah,
                'validasi_alamat' => $item->alamatLuar->validasi_alamat ?? null,
                'validasi_pekerjaan' => $item->pekerjaanLuar->validasi_pekerjaan ?? null,
                'validasi_penjamin' => $item->penjaminLuar->validasi_penjamin ?? null,
                'validasi_tanggungan' => $item->tanggunganLuar->validasi_tanggungan ?? null,
                'validasi_pengajuan' => $item->pengajuanLuar->map(function ($pengajuanItem) {
                    return $pengajuanItem->validasi_pengajuan; // Iterasi untuk one-to-many
                }),
                'validasi_kontak_darurat' => $item->kontakDarurat->validasi_kontak_darurat ?? null,
            ];
        });

        // dd($dataValidasi);
        return view('pengajuan-luar.marketing-luar.manajemen-nasabah-luar', compact('pengajuan', 'marketing'));
    }

    public function form()
    {
        return view('pengajuan-luar.marketing-luar.form-pengajuan-luar');
    }

    public function validateNik(Request $request)
    {
        $nik = $request->get('nik');
        $exists = NasabahLuar::where('nik', $nik)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function validateNoKk(Request $request)
    {
        $no_kk = $request->get('no_kk');
        $exists = NasabahLuar::where('no_kk', $no_kk)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        $marketingId = Auth::user()->id;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'no_kk' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_nikah' => 'required|string',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf|max:2048',

            'alamat_ktp' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:10',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'status_rumah' => 'nullable|string',
            'domisili' => 'nullable|string',
            'lama_tinggal' => 'nullable|string|max:255',
            'atas_nama_listrik' => 'nullable|string|max:255',
            'hubungan_rek_listrik' => 'nullable|string|max:255',
            'foto_meteran_listrik' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'share_loc_link' => 'nullable|string',

            'perusahaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string|max:255',
            'kontak_perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'lama_kerja' => 'nullable|string|max:255',
            'status_karyawan' => 'nullable|string',
            'lama_kontrak' => 'nullable|required_if:status_karyawan,kontrak|string|max:255',
            'pendapatan_perbulan' => 'nullable|numeric',
            'foto_id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_slip_gaji' => 'nullable|file|mimes:pdf|max:2048',
            'norek' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'hubungan_penjamin' => 'nullable|string',
            'nama_penjamin' => 'nullable|string|max:255',
            'pekerjaan_penjamin' => 'nullable|string|max:255',
            'penghasilan_penjamin' => 'nullable|numeric',
            'no_hp_penjamin' => 'nullable|string|max:255',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'kondisi_tanggungan' => 'nullable|string',

            'cicilan_lain' => 'nullable|in:Ada,Tidak',
            'nama_pembiayaan.*' => 'nullable|required_if:cicilan_lain,Ada|string|max:255',
            'total_pinjaman.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'cicilan_perbulan.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'sisa_tenor_cicilan.*' => 'nullable|required_if:cicilan_lain,Ada|integer|min:0',

            'nama_kontak_darurat' => 'nullable|string|max:255',
            'hubungan_kontak_darurat' => 'nullable|string|max:255',
            'no_hp_kontak_darurat' => 'nullable|string|max:255',

            'status_pinjaman' => 'required|string',
            'jenis_pembiayaan' => 'required|string',
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|integer',
            'berkas_jaminan' => 'required|file|mimes:pdf|max:2048',
            'catatan_marketing' => 'required|string',
        ]);

        // if ($request->hasFile('foto_jaminan_tambahan')) {
        //     dd($request->file('foto_jaminan_tambahan')->getMimeType());
        // }

        // Log::info('Request Data:', $request->all());

        DB::beginTransaction();

        try {
            // Store Nasabah Luar
            $nasabah = new NasabahLuar();
            $nasabah->marketing_id = $marketingId;
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->nik = $request->nik;
            $nasabah->no_kk = $request->no_kk;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->tempat_lahir = $request->tempat_lahir;
            $nasabah->tanggal_lahir = $request->tanggal_lahir;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            $nasabah->status_nikah = $request->status_nikah;
            $nasabah->save();

            $namaNasabah = str_replace(' ', '-', strtolower($request->nama_lengkap));
            $FolderDokumen = 'dokumen_pendukung_luar/' . $namaNasabah . '-' . $nasabah->id;

            // Simpan foto dokumen ke folder "dokumen_pendukung"
            if ($request->hasFile('foto_ktp')) {
                $ktpFile = $request->file('foto_ktp');
                $ktpFileName = 'ktp-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $nasabah->foto_ktp = $ktpFileName;
            }
            if ($request->hasFile('foto_kk')) {
                $kkFile = $request->file('foto_kk');
                $kkFileName = 'kk-' . $namaNasabah . '.' . $kkFile->getClientOriginalExtension();
                $kkFile->storeAs($FolderDokumen, $kkFileName, 'public');
                $nasabah->foto_kk = $kkFileName;
            }
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumenFile = $request->file('dokumen_pendukung');
                $dokumenFileName = 'dokumen-pendukung-' . $namaNasabah . '.' . $dokumenFile->getClientOriginalExtension();
                $dokumenFile->storeAs($FolderDokumen, $dokumenFileName, 'public');
                $nasabah->dokumen_pendukung = $dokumenFileName;
            }
            $nasabah->save();

            // Store Alamat
            $alamat = new AlamatNasabahLuar();
            $alamat->nasabah_id = $nasabah->id;
            $alamat->alamat_ktp = $request->alamat_ktp;
            $alamat->kelurahan = $request->kelurahan;
            $alamat->rt_rw = $request->rt_rw;
            $alamat->kecamatan = $request->kecamatan;
            $alamat->kota = $request->kota;
            $alamat->provinsi = $request->provinsi;
            $alamat->status_rumah = $request->status_rumah;
            if ($alamat->status_rumah == 'kontrak bulanan') {
                $alamat->biaya_perbulan = $request->biaya_perbulan;
            } elseif ($alamat->status_rumah == 'kontrak tahunan') {
                $alamat->biaya_pertahun = $request->biaya_pertahun;
            }
            $alamat->domisili = $request->domisili;
            if ($alamat->domisili == 'tidak sesuai ktp') {
                $alamat->alamat_domisili = $request->alamat_domisili;
                $alamat->rumah_domisili = $request->rumah_domisili;
                if ($alamat->rumah_domisili == 'kontrak bulanan') {
                    $alamat->biaya_perbulan_domisili = $request->biaya_perbulan_domisili;
                } elseif ($alamat->rumah_domisili == 'kontrak tahunan') {
                    $alamat->biaya_pertahun_domisili = $request->biaya_pertahun_domisili;
                }
            }
            $alamat->lama_tinggal = $request->lama_tinggal;
            $alamat->atas_nama_listrik = $request->atas_nama_listrik;
            $alamat->hubungan = $request->hubungan_rek_listrik;
            if ($request->hasFile('foto_meteran_listrik')) {
                $listrikFile = $request->file('foto_meteran_listrik');
                $listrikFileName = 'listrik-' . $namaNasabah . '.' . $listrikFile->getClientOriginalExtension();
                $listrikFile->storeAs($FolderDokumen, $listrikFileName, 'public');
                $alamat->foto_meteran_listrik = $listrikFileName;
            }
            $alamat->share_loc_link = $request->share_loc_link;
            $alamat->save();

            // Store Pekerjaan
            $pekerjaan = new PekerjaanNasabahLuar();
            $pekerjaan->nasabah_id = $nasabah->id;
            $pekerjaan->perusahaan = $request->perusahaan;
            $pekerjaan->alamat_perusahaan = $request->alamat_perusahaan;
            $pekerjaan->kontak_perusahaan = $request->kontak_perusahaan;
            $pekerjaan->jabatan = $request->jabatan;
            $pekerjaan->lama_kerja = $request->lama_kerja;
            $pekerjaan->status_karyawan = $request->status_karyawan;
            if ($request->status_karyawan == 'kontrak') {
                $pekerjaan->lama_kontrak = $request->lama_kontrak;
            }
            $pekerjaan->pendapatan_perbulan = $request->pendapatan_perbulan;
            if ($request->hasFile('foto_slip_gaji')) {
                $slipFile = $request->file('foto_slip_gaji');
                $slipFileName = 'slip-gaji-' . $namaNasabah . '.' . $slipFile->getClientOriginalExtension();
                $slipFile->storeAs($FolderDokumen, $slipFileName, 'public');
                $pekerjaan->slip_gaji = $slipFileName;
            }
            if ($request->hasFile('foto_id_card')) {
                $idCardFile = $request->file('foto_id_card');
                $idCardFileName = 'id_card-' . $namaNasabah . '.' . $idCardFile->getClientOriginalExtension();
                $idCardFile->storeAs($FolderDokumen, $idCardFileName, 'public');
                $pekerjaan->id_card = $idCardFileName;
            }
            if ($request->hasFile('norek')) {
                $norekFile = $request->file('norek');
                $norekFileName = 'norek-' . $namaNasabah . '.' . $norekFile->getClientOriginalExtension();
                $norekFile->storeAs($FolderDokumen, $norekFileName, 'public');
                $pekerjaan->norek = $norekFileName;
            }
            $pekerjaan->save();

            // Store Penjamin
            $penjamin = new PenjaminNasabahLuar();
            $penjamin->nasabah_id = $nasabah->id;
            $penjamin->hubungan_penjamin = $request->hubungan_penjamin;
            $penjamin->nama_penjamin = $request->nama_penjamin;
            $penjamin->pekerjaan_penjamin = $request->pekerjaan_penjamin;
            $penjamin->penghasilan_penjamin = $request->penghasilan_penjamin;
            $penjamin->no_hp_penjamin = $request->no_hp_penjamin;
            if ($request->hasFile('foto_ktp_penjamin')) {
                $ktpPenjaminFile = $request->file('foto_ktp_penjamin');
                $ktpPenjaminFileName = 'ktp_penjamin-' . $namaNasabah . '.' . $ktpPenjaminFile->getClientOriginalExtension();
                $ktpPenjaminFile->storeAs($FolderDokumen, $ktpPenjaminFileName, 'public');
                $penjamin->foto_ktp_penjamin = $ktpPenjaminFileName;
            }
            $penjamin->save();

            // Store Tanggungan
            $tanggungan = new TanggunganNasabahLuar();
            $tanggungan->nasabah_id = $nasabah->id;
            $tanggungan->kondisi_tanggungan = $request->kondisi_tanggungan;
            $tanggungan->save();

            // Store Pinjaman Lain
            // Loop untuk menyimpan data cicilan ke tabel yang sama
            if ($request->cicilan_lain === 'Ada') {
                // Simpan untuk setiap cicilan lain
                if (
                    is_array($request->nama_pembiayaan) && is_array($request->cicilan_perbulan) && is_array($request->sisa_tenor_cicilan)
                ) {
                    foreach ($request->nama_pembiayaan as $index => $namaPembiayaan) {
                        $pinjamanLain = new PinjamanLainNasabahLuar();
                        $pinjamanLain->nasabah_id = $nasabah->id;
                        $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                        $pinjamanLain->nama_pembiayaan = $namaPembiayaan ?? null;
                        $pinjamanLain->total_pinjaman = $request->total_pinjaman[$index] ?? null;
                        $pinjamanLain->cicilan_perbulan = $request->cicilan_perbulan[$index] ?? null;
                        $pinjamanLain->sisa_tenor = $request->sisa_tenor_cicilan[$index] ?? null;

                        //Log::info('Saving Data:', $pinjamanLain->toArray());
                        $pinjamanLain->save();
                    }
                }
            } else {
                // Simpan satu record untuk kasus "Tidak"
                $pinjamanLain = new PinjamanLainNasabahLuar();
                $pinjamanLain->nasabah_id = $nasabah->id;
                $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                $pinjamanLain->nama_pembiayaan = null; // Tidak ada cicilan lain
                $pinjamanLain->total_pinjaman = null;
                $pinjamanLain->cicilan_perbulan = null;
                $pinjamanLain->sisa_tenor = null;

                //Log::info('Saving Data:', $pinjamanLain->toArray());
                $pinjamanLain->save();
            }

            // Store Kontak Darurat
            $kontakDarurat = new KontakDaruratNasabahLuar();
            $kontakDarurat->nasabah_id = $nasabah->id;
            $kontakDarurat->nama_kontak_darurat = $request->nama_kontak_darurat;
            $kontakDarurat->hubungan_kontak_darurat = $request->hubungan_kontak_darurat;
            $kontakDarurat->no_hp_kontak_darurat = $request->no_hp_kontak_darurat;
            $kontakDarurat->save();

            // Store Pengajuan
            $pengajuan = new PengajuanNasabahLuar();
            $pengajuan->nasabah_id = $nasabah->id;
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->status_pinjaman = $request->status_pinjaman;
            if ($request->status_pinjaman === 'lama') {
                $pengajuan->pinjaman_ke = $request->pinjaman_ke;
                $pengajuan->pinjaman_terakhir = $request->pinjaman_terakhir;
                $pengajuan->sisa_pinjaman = $request->sisa_pinjaman;
                $pengajuan->realisasi_pinjaman = $request->realisasi_pinjaman;
                $pengajuan->cicilan_perbulan = $request->cicilan_perbulan_pinjaman;
            }
            $pengajuan->jenis_pembiayaan = $request->jenis_pembiayaan;
            $pengajuan->status_pengajuan = 'pending';
            if ($request->hasFile('berkas_jaminan')) {
                $berkasJaminan = $request->file('berkas_jaminan');
                $berkasJaminanName = 'berkas_jaminan-' . $namaNasabah . '.' . $berkasJaminan->getClientOriginalExtension();
                $berkasJaminan->storeAs($FolderDokumen, $berkasJaminanName, 'public');
                $pengajuan->berkas_jaminan = $berkasJaminanName;
            }
            $pengajuan->catatan_marketing = $request->catatan_marketing;
            $pengajuan->save();

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan baru telah ditambahkan oleh Marketing ' . Auth::user()->name,
                'read' => false,
            ]);

            DB::commit();

            Log::info("Berhasil input", [
                'data' => $request->all(),
            ]);

            return redirect()->route('marketingLuar.pengajuan')->with('success', 'Pengajuan nasabah luar berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan data:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        };
    }

    public function edit($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);

        return view('pengajuan-luar.marketing-luar.form-edit-pengajuan-luar', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $marketingId = Auth::user()->id;

        // dd($request->all());

        $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:255',
            'no_kk' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_nikah' => 'nullable|string',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf|max:2048',

            'alamat_ktp' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:10',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'status_rumah' => 'nullable|string',
            'domisili' => 'nullable|string',
            'lama_tinggal' => 'nullable|string',
            'atas_nama_listrik' => 'nullable|string|max:255',
            'hubungan_rek_listrik' => 'nullable|string|max:255',
            'foto_meteran_listrik' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'share_loc_link' => 'nullable|string',

            'perusahaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string|max:255',
            'kontak_perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'lama_kerja' => 'nullable|string|max:255',
            'status_karyawan' => 'nullable|string',
            'lama_kontrak' => 'nullable|required_if:status_karyawan,kontrak|string|max:255',
            'pendapatan_perbulan' => 'nullable|numeric',
            'foto_id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_slip_gaji' => 'nullable|file|mimes:pdf|max:2048',
            'norek' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'hubungan_penjamin' => 'nullable|string',
            'nama_penjamin' => 'nullable|string|max:255',
            'pekerjaan_penjamin' => 'nullable|string|max:255',
            'penghasilan_penjamin' => 'nullable|numeric',
            'no_hp_penjamin' => 'nullable|string|max:255',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'kondisi_tanggungan' => 'nullable|string|max:500',

            'cicilan_lain' => 'nullable|in:Ada,Tidak',
            'nama_pembiayaan.*' => 'nullable|required_if:cicilan_lain,Ada|string|max:255',
            'total_pinjaman.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'cicilan_perbulan.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'sisa_tenor_cicilan.*' => 'nullable|required_if:cicilan_lain,Ada|integer|min:0',

            'nama_kontak_darurat' => 'nullable|string|max:255',
            'hubungan_kontak_darurat' => 'nullable|string|max:255',
            'no_hp_kontak_darurat' => 'nullable|string|max:255',

            'status_pinjaman' => 'nullable|string',
            'jenis_pembiayaan' => 'nullable|string',
            'nominal_pinjaman' => 'nullable|numeric',
            'tenor' => 'nullable|integer',
            'berkas_jaminan' => 'nullable|file|mimes:pdf|max:2048',
            'catatan_marketing' => 'nullable|string',
        ]);

        $isRevisi = (
            $request->validasi_nasabah == 0 ||
            $request->validasi_alamat == 0 ||
            $request->validasi_pekerjaan == 0 ||
            $request->validasi_penjamin == 0 ||
            $request->validasi_tanggungan == 0 ||
            $request->validasi_kontak_darurat == 0 ||
            $request->validasi_pengajuan == 0
        );

        // dd($request->file('foto_slip_gaji'));

        DB::beginTransaction();

        try {
            // Temukan Nasabah Luar berdasarkan ID
            $nasabah = NasabahLuar::findOrFail($id);
            $nasabah->marketing_id = $marketingId;
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->nik = $request->nik;
            $nasabah->no_kk = $request->no_kk;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->tempat_lahir = $request->tempat_lahir;
            $nasabah->tanggal_lahir = $request->tanggal_lahir;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            $nasabah->status_nikah = $request->status_nikah;

            // // Handle file uploads
            $namaNasabah = str_replace(' ', '-', strtolower($request->nama_lengkap));
            $FolderDokumen = 'dokumen_pendukung_luar/' . $namaNasabah . '-' . $nasabah->id;

            // Update foto jika ada file baru
            if ($request->hasFile('foto_ktp')) {
                // Hapus foto lama jika ada
                if ($nasabah->foto_ktp) {
                    Storage::disk('public')->delete($nasabah->foto_ktp);
                }
                $ktpFile = $request->file('foto_ktp');
                $ktpFileName = 'ktp-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $nasabah->foto_ktp = $ktpFileName;
            }

            if ($request->hasFile('foto_kk')) {
                if ($nasabah->foto_kk) {
                    Storage::disk('public')->delete($nasabah->foto_kk);
                }
                $kkFile = $request->file('foto_kk');
                $kkFileName = 'kk-' . $namaNasabah . '.' . $kkFile->getClientOriginalExtension();
                $kkFile->storeAs($FolderDokumen, $kkFileName, 'public');
                $nasabah->foto_kk = $kkFileName;
            }

            if ($request->hasFile('dokumen_pendukung')) {
                if ($nasabah->dokumen_pendukung) {
                    Storage::disk('public')->delete($nasabah->dokumen_pendukung);
                }
                $dokumenFile = $request->file('dokumen_pendukung');
                $dokumenFileName = 'dokumen-' . $namaNasabah . '.' . $dokumenFile->getClientOriginalExtension();
                $dokumenFile->storeAs($FolderDokumen, $dokumenFileName, 'public');
                $nasabah->dokumen_pendukung = $dokumenFileName;
            }
            $nasabah->save();

            $alamat = AlamatNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $alamat->alamat_ktp = $request->alamat_ktp;
            $alamat->kelurahan = $request->kelurahan;
            $alamat->rt_rw = $request->rt_rw;
            $alamat->kecamatan = $request->kecamatan;
            $alamat->kota = $request->kota;
            $alamat->provinsi = $request->provinsi;
            $alamat->status_rumah = $request->status_rumah;
            if ($alamat->status_rumah == 'kontrak bulanan') {
                $alamat->biaya_perbulan = $request->biaya_perbulan;
            } elseif ($alamat->status_rumah == 'kontrak tahunan') {
                $alamat->biaya_pertahun = $request->biaya_pertahun;
            }
            $alamat->domisili = $request->domisili;
            if ($alamat->domisili == 'tidak sesuai ktp') {
                $alamat->alamat_domisili = $request->alamat_domisili;
                $alamat->rumah_domisili = $request->rumah_domisili;
                if ($alamat->rumah_domisili == 'kontrak bulanan') {
                    $alamat->biaya_perbulan_domisili = $request->biaya_perbulan_domisili;
                } elseif ($alamat->rumah_domisili == 'kontrak tahunan') {
                    $alamat->biaya_pertahun_domisili = $request->biaya_pertahun_domisili;
                }
            }
            $alamat->lama_tinggal = $request->lama_tinggal;
            $alamat->atas_nama_listrik = $request->atas_nama_listrik;
            $alamat->hubungan = $request->hubungan_rek_listrik;
            if ($request->hasFile('foto_meteran_listrik')) {
                if ($alamat->foto_meteran_listrik) {
                    Storage::disk('public')->delete($alamat->foto_meteran_listrik);
                }
                $listrikFile = $request->file('foto_meteran_listrik');
                $listrikFileName = 'listrik-' . $namaNasabah . '.' . $listrikFile->getClientOriginalExtension();
                $listrikFile->storeAs($FolderDokumen, $listrikFileName, 'public');
                $alamat->foto_meteran_listrik = $listrikFileName;
            }
            $alamat->share_loc_link = $request->share_loc_link;
            $alamat->save();

            $pekerjaan = PekerjaanNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $pekerjaan->perusahaan = $request->perusahaan;
            $pekerjaan->alamat_perusahaan = $request->alamat_perusahaan;
            $pekerjaan->kontak_perusahaan = $request->kontak_perusahaan;
            $pekerjaan->jabatan = $request->jabatan;
            $pekerjaan->lama_kerja = $request->lama_kerja;
            $pekerjaan->status_karyawan = $request->status_karyawan;
            if ($request->status_karyawan == 'kontrak') {
                $pekerjaan->lama_kontrak = $request->lama_kontrak;
            }
            $pekerjaan->pendapatan_perbulan = $request->pendapatan_perbulan;
            if ($request->hasFile('foto_slip_gaji')) {
                $slipFile = $request->file('foto_slip_gaji');
                if ($pekerjaan->slip_gaji) {
                    Storage::disk('public')->delete($pekerjaan->slip_gaji);
                }
                $slipFileName = 'slip-gaji-' . $namaNasabah . '.' . $slipFile->getClientOriginalExtension();
                $slipFile->storeAs($FolderDokumen, $slipFileName, 'public');
                $pekerjaan->slip_gaji = $slipFileName;
            }
            if ($request->hasFile('foto_id_card')) {
                $idCardFile = $request->file('foto_id_card');
                if ($pekerjaan->id_card) {
                    Storage::disk('public')->delete($pekerjaan->id_card);
                }
                $idCardFileName = 'id_card-' . $namaNasabah . '.' . $idCardFile->getClientOriginalExtension();
                $idCardFile->storeAs($FolderDokumen, $idCardFileName, 'public');
                $pekerjaan->id_card = $idCardFileName;
            }
            if ($request->hasFile('norek')) {
                $norekFile = $request->file('norek');
                if ($pekerjaan->norek) {
                    Storage::disk('public')->delete($pekerjaan->norek);
                }
                $norekFileName = 'norek-' . $namaNasabah . '.' . $norekFile->getClientOriginalExtension();
                $norekFile->storeAs($FolderDokumen, $norekFileName, 'public');
                $pekerjaan->norek = $norekFileName;
            }
            $pekerjaan->save();

            $penjamin = PenjaminNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $penjamin->hubungan_penjamin = $request->hubungan_penjamin;
            $penjamin->nama_penjamin = $request->nama_penjamin;
            $penjamin->pekerjaan_penjamin = $request->pekerjaan_penjamin;
            $penjamin->penghasilan_penjamin = $request->penghasilan_penjamin;
            $penjamin->no_hp_penjamin = $request->no_hp_penjamin;
            if ($request->hasFile('foto_ktp_penjamin')) {
                $ktpFile = $request->file('foto_ktp_penjamin');
                if ($penjamin->foto_ktp_penjamin) {
                    Storage::disk('public')->delete($penjamin->foto_ktp_penjamin);
                }
                $ktpFileName = 'ktp_penjamin-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $penjamin->foto_ktp_penjamin = $ktpFileName;
            }
            $penjamin->save();

            $tanggungan = TanggunganNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $tanggungan->kondisi_tanggungan = $request->kondisi_tanggungan;
            $tanggungan->save();

            if ($request->cicilan_lain === 'Ada') {
                PinjamanLainNasabahLuar::where('nasabah_id', $nasabah->id)->delete();
                if (
                    is_array($request->nama_pembiayaan) && is_array($request->cicilan_perbulan) && is_array($request->sisa_tenor_cicilan)
                ) {
                    foreach ($request->nama_pembiayaan as $index => $namaPembiayaan) {
                        $pinjamanLain = new PinjamanLainNasabahLuar();
                        $pinjamanLain->nasabah_id = $nasabah->id;
                        $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                        $pinjamanLain->nama_pembiayaan = $namaPembiayaan ?? null;
                        $pinjamanLain->total_pinjaman = $request->total_pinjaman[$index] ?? null;
                        $pinjamanLain->cicilan_perbulan = $request->cicilan_perbulan[$index] ?? null;
                        $pinjamanLain->sisa_tenor = $request->sisa_tenor_cicilan[$index] ?? null;
                        $pinjamanLain->save();
                    }
                }
            } else {
                PinjamanLainNasabahLuar::where('nasabah_id', $nasabah->id)->delete();

                $pinjamanLain = new PinjamanLainNasabahLuar();
                $pinjamanLain->nasabah_id = $nasabah->id;
                $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                $pinjamanLain->nama_pembiayaan = null;
                $pinjamanLain->cicilan_perbulan = null;
                $pinjamanLain->sisa_tenor = null;
                $pinjamanLain->save();
            }

            $kontakDarurat = KontakDaruratNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $kontakDarurat->nama_kontak_darurat = $request->nama_kontak_darurat;
            $kontakDarurat->hubungan_kontak_darurat = $request->hubungan_kontak_darurat;
            $kontakDarurat->no_hp_kontak_darurat = $request->no_hp_kontak_darurat;
            $kontakDarurat->save();

            $pengajuan = PengajuanNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->status_pinjaman = $request->status_pinjaman;
            if ($request->status_pinjaman === 'lama') {
                $pengajuan->pinjaman_ke = $request->pinjaman_ke;
                $pengajuan->pinjaman_terakhir = $request->pinjaman_terakhir;
                $pengajuan->sisa_pinjaman = $request->sisa_pinjaman;
                $pengajuan->realisasi_pinjaman = $request->realisasi_pinjaman;
                $pengajuan->cicilan_perbulan = $request->cicilan_perbulan_pinjaman;
            }
            $pengajuan->jenis_pembiayaan = $request->jenis_pembiayaan;
            $pengajuan->catatan_marketing = $request->catatan_marketing;
            if ($request->hasFile('berkas_jaminan')) {
                if ($pengajuan->berkas_jaminan) {
                    Storage::disk('public')->delete($pengajuan->berkas_jaminan);
                }
                $berkasJaminan = $request->file('berkas_jaminan');
                $berkasJaminanName = 'berkas_jaminan-' . $namaNasabah . '.' . $berkasJaminan->getClientOriginalExtension();
                $berkasJaminan->storeAs($FolderDokumen, $berkasJaminanName, 'public');
                $pengajuan->berkas_jaminan = $berkasJaminanName;
            }
            $pengajuan->status_pengajuan = $pengajuan->status_pengajuan ?? 'verifikasi';
            $pengajuan->save();

            if ($pengajuan->status_pengajuan === 'revisi spv') {
                NotificationSPV::create([
                    'pengajuan_luar_id' => $pengajuan->id,
                    'pesan' => 'Pengajuan atas nama ' . $nasabah->nama_lengkap . ' telah di Revisi oleh Marketing ' . Auth::user()->name,
                    'read' => false,
                ]);
            } elseif ($isRevisi) {
                NotificationCA::create([
                    'pengajuan_luar_id' => $pengajuan->id,
                    'pesan' => 'Pengajuan atas nama ' . $nasabah->nama_lengkap . ' telah di Revisi oleh Marketing ' . Auth::user()->name,
                    'read' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('marketingLuar.pengajuan')->with('success', 'Pengajuan nasabah luar berhasil diedit.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating nasabah: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // $nasabah = NasabahLuar::with('alamatLuar', 'pekerjaanLuar', 'penjaminLuar', 'tanggunganLuar', 'pinjamanLainLuar', 'kontakDarurat', 'pengajuanLuar')->findOrFail($id);
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        // dd($nasabah);
        return view('pengajuan-luar.marketing-luar.detail-pengajuan-luar', compact('pengajuan'));
    }

    public function topUp($id)
    {
        $nasabah = NasabahLuar::with('alamatLuar', 'pekerjaanLuar', 'penjaminLuar', 'tanggunganLuar', 'pinjamanLainLuar', 'kontakDarurat', 'pengajuanLuar')->findOrFail($id);
        return view('pengajuan-luar.marketing-luar.form-topup-pengajuan', compact('nasabah'));
    }

    public function topUpStore(Request $request, $id)
    {
        $marketingId = Auth::user()->id;

        // dd($request->nama_lengkap, $request->foto_ktp, $request->norek, $request->berkas_jaminan, $request->file('berkas_jaminan'), $request->catatan_marketing);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'no_kk' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_nikah' => 'required|string',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf|max:2048',

            'alamat_ktp' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:100',
            'rt_rw' => 'required|string|max:10',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'status_rumah' => 'required|string',
            'domisili' => 'required|string',
            'lama_tinggal' => 'required|string|max:255',
            'atas_nama_listrik' => 'required|string|max:255',
            'hubungan_rek_listrik' => 'required|string|max:255',
            'foto_meteran_listrik' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'share_loc_link' => 'nullable|string',

            'perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'kontak_perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'lama_kerja' => 'required|string|max:255',
            'status_karyawan' => 'required|string',
            'lama_kontrak' => 'nullable|required_if:status_karyawan,kontrak|string|max:255',
            'pendapatan_perbulan' => 'required|numeric',
            'foto_id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_slip_gaji' => 'nullable|file|mimes:pdf|max:2048',
            'norek' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',

            'hubungan_penjamin' => 'nullable|string',
            'nama_penjamin' => 'nullable|string|max:255',
            'pekerjaan_penjamin' => 'nullable|string|max:255',
            'penghasilan_penjamin' => 'nullable|numeric',
            'no_hp_penjamin' => 'nullable|string|max:255',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'kondisi_tanggungan' => 'required|string',

            'cicilan_lain' => 'nullable|in:Ada,Tidak',
            'nama_pembiayaan.*' => 'nullable|required_if:cicilan_lain,Ada|string|max:255',
            'total_pinjaman.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'cicilan_perbulan.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'sisa_tenor_cicilan.*' => 'nullable|required_if:cicilan_lain,Ada|integer|min:0',

            'nama_kontak_darurat' => 'nullable|string|max:255',
            'hubungan_kontak_darurat' => 'nullable|string|max:255',
            'no_hp_kontak_darurat' => 'nullable|string|max:255',

            'status_pinjaman' => 'required|string',
            'jenis_pembiayaan' => 'required|string',
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|integer',
            'berkas_jaminan' => 'required|file|mimes:pdf|max:2048',
            'catatan_marketing' => 'nullable|string',
        ]);


        DB::beginTransaction();

        try {
            // Temukan Nasabah Luar berdasarkan ID
            $nasabah = NasabahLuar::findOrFail($id);
            $nasabah->marketing_id = $marketingId;
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->nik = $request->nik;
            $nasabah->no_kk = $request->no_kk;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->tempat_lahir = $request->tempat_lahir;
            $nasabah->tanggal_lahir = $request->tanggal_lahir;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            $nasabah->status_nikah = $request->status_nikah;

            // // Handle file uploads
            $namaNasabah = str_replace(' ', '-', strtolower($request->nama_lengkap));
            $FolderDokumen = 'dokumen_pendukung_luar/' . $namaNasabah . '-' . $nasabah->id;

            // Update foto jika ada file baru
            if ($request->hasFile('foto_ktp')) {
                // Hapus foto lama jika ada
                if ($nasabah->foto_ktp) {
                    Storage::disk('public')->delete($nasabah->foto_ktp);
                }
                $ktpFile = $request->file('foto_ktp');
                $ktpFileName = 'ktp-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $nasabah->foto_ktp = $ktpFileName;
            }

            if ($request->hasFile('foto_kk')) {
                if ($nasabah->foto_kk) {
                    Storage::disk('public')->delete($nasabah->foto_kk);
                }
                $kkFile = $request->file('foto_kk');
                $kkFileName = 'kk-' . $namaNasabah . '.' . $kkFile->getClientOriginalExtension();
                $kkFile->storeAs($FolderDokumen, $kkFileName, 'public');
                $nasabah->foto_kk = $kkFileName;
            }

            if ($request->hasFile('dokumen_pendukung')) {
                if ($nasabah->dokumen_pendukung) {
                    Storage::disk('public')->delete($nasabah->dokumen_pendukung);
                }
                $dokumenFile = $request->file('dokumen_pendukung');
                $dokumenFileName = 'dokumen-pendukung-' . $namaNasabah . '.' . $dokumenFile->getClientOriginalExtension();
                $dokumenFile->storeAs($FolderDokumen, $dokumenFileName, 'public');
                $nasabah->dokumen_pendukung = $dokumenFileName;
            }
            $nasabah->save();

            $alamat = AlamatNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $alamat->alamat_ktp = $request->alamat_ktp;
            $alamat->kelurahan = $request->kelurahan;
            $alamat->rt_rw = $request->rt_rw;
            $alamat->kecamatan = $request->kecamatan;
            $alamat->kota = $request->kota;
            $alamat->provinsi = $request->provinsi;
            $alamat->status_rumah = $request->status_rumah;
            if ($alamat->status_rumah == 'kontrak bulanan') {
                $alamat->biaya_perbulan = $request->biaya_perbulan;
            } elseif ($alamat->status_rumah == 'kontrak tahunan') {
                $alamat->biaya_pertahun = $request->biaya_pertahun;
            }
            $alamat->domisili = $request->domisili;
            if ($alamat->domisili == 'tidak sesuai ktp') {
                $alamat->alamat_domisili = $request->alamat_domisili;
                $alamat->rumah_domisili = $request->rumah_domisili;
                if ($alamat->rumah_domisili == 'kontrak bulanan') {
                    $alamat->biaya_perbulan_domisili = $request->biaya_perbulan_domisili;
                } elseif ($alamat->rumah_domisili == 'kontrak tahunan') {
                    $alamat->biaya_pertahun_domisili = $request->biaya_pertahun_domisili;
                }
            }
            $alamat->lama_tinggal = $request->lama_tinggal;
            $alamat->atas_nama_listrik = $request->atas_nama_listrik;
            $alamat->hubungan = $request->hubungan_rek_listrik;
            if ($request->hasFile('foto_meteran_listrik')) {
                if ($alamat->foto_meteran_listrik) {
                    Storage::disk('public')->delete($alamat->foto_meteran_listrik);
                }
                $listrikFile = $request->file('foto_meteran_listrik');
                $listrikFileName = 'listrik-' . $namaNasabah . '.' . $listrikFile->getClientOriginalExtension();
                $listrikFile->storeAs($FolderDokumen, $listrikFileName, 'public');
                $alamat->foto_meteran_listrik = $listrikFileName;
            }
            $alamat->share_loc_link = $request->share_loc_link;
            $alamat->save();

            $pekerjaan = PekerjaanNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $pekerjaan->perusahaan = $request->perusahaan;
            $pekerjaan->alamat_perusahaan = $request->alamat_perusahaan;
            $pekerjaan->kontak_perusahaan = $request->kontak_perusahaan;
            $pekerjaan->jabatan = $request->jabatan;
            $pekerjaan->lama_kerja = $request->lama_kerja;
            $pekerjaan->status_karyawan = $request->status_karyawan;
            if ($request->status_karyawan == 'kontrak') {
                $pekerjaan->lama_kontrak = $request->lama_kontrak;
            }
            $pekerjaan->pendapatan_perbulan = $request->pendapatan_perbulan;
            if ($request->hasFile('foto_slip_gaji')) {
                $slipFile = $request->file('foto_slip_gaji');
                if ($pekerjaan->slip_gaji) {
                    Storage::disk('public')->delete($pekerjaan->slip_gaji);
                }
                $slipFileName = 'slip-gaji-' . $namaNasabah . '.' . $slipFile->getClientOriginalExtension();
                $slipFile->storeAs($FolderDokumen, $slipFileName, 'public');
                $pekerjaan->slip_gaji = $slipFileName;
            }
            if ($request->hasFile('foto_id_card')) {
                $idCardFile = $request->file('foto_id_card');
                if ($pekerjaan->id_card) {
                    Storage::disk('public')->delete($pekerjaan->id_card);
                }
                $idCardFileName = 'id_card-' . $namaNasabah . '.' . $idCardFile->getClientOriginalExtension();
                $idCardFile->storeAs($FolderDokumen, $idCardFileName, 'public');
                $pekerjaan->id_card = $idCardFileName;
            }
            if ($request->hasFile('norek')) {
                $norekFile = $request->file('norek');
                if ($pekerjaan->norek) {
                    Storage::disk('public')->delete($pekerjaan->norek);
                }
                $norekFileName = 'norek-' . $namaNasabah . '.' . $norekFile->getClientOriginalExtension();
                $norekFile->storeAs($FolderDokumen, $norekFileName, 'public');
                $pekerjaan->norek = $norekFileName;
            }
            $pekerjaan->save();

            $penjamin = PenjaminNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $penjamin->hubungan_penjamin = $request->hubungan_penjamin;
            $penjamin->nama_penjamin = $request->nama_penjamin;
            $penjamin->pekerjaan_penjamin = $request->pekerjaan_penjamin;
            $penjamin->penghasilan_penjamin = $request->penghasilan_penjamin;
            $penjamin->no_hp_penjamin = $request->no_hp_penjamin;
            if ($request->hasFile('foto_ktp_penjamin')) {
                $ktpFile = $request->file('foto_ktp_penjamin');
                if ($penjamin->foto_ktp_penjamin) {
                    Storage::disk('public')->delete($penjamin->foto_ktp_penjamin);
                }
                $ktpFileName = 'ktp_penjamin-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $penjamin->foto_ktp_penjamin = $ktpFileName;
            }
            $penjamin->save();

            $tanggungan = TanggunganNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $tanggungan->kondisi_tanggungan = $request->kondisi_tanggungan;
            $tanggungan->save();

            if ($request->cicilan_lain === 'Ada') {
                PinjamanLainNasabahLuar::where('nasabah_id', $nasabah->id)->delete();
                if (
                    is_array($request->nama_pembiayaan) && is_array($request->cicilan_perbulan) && is_array($request->sisa_tenor_cicilan)
                ) {
                    foreach ($request->nama_pembiayaan as $index => $namaPembiayaan) {
                        $pinjamanLain = new PinjamanLainNasabahLuar();
                        $pinjamanLain->nasabah_id = $nasabah->id;
                        $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                        $pinjamanLain->nama_pembiayaan = $namaPembiayaan ?? null;
                        $pinjamanLain->total_pinjaman = $request->total_pinjaman[$index] ?? null;
                        $pinjamanLain->cicilan_perbulan = $request->cicilan_perbulan[$index] ?? null;
                        $pinjamanLain->sisa_tenor = $request->sisa_tenor_cicilan[$index] ?? null;
                        $pinjamanLain->save();
                    }
                }
            } else {
                PinjamanLainNasabahLuar::where('nasabah_id', $nasabah->id)->delete();

                $pinjamanLain = new PinjamanLainNasabahLuar();
                $pinjamanLain->nasabah_id = $nasabah->id;
                $pinjamanLain->cicilan_lain = $request->cicilan_lain;
                $pinjamanLain->nama_pembiayaan = null;
                $pinjamanLain->cicilan_perbulan = null;
                $pinjamanLain->sisa_tenor = null;
                $pinjamanLain->save();
            }

            $kontakDarurat = KontakDaruratNasabahLuar::where('nasabah_id', $nasabah->id)->first();
            $kontakDarurat->nama_kontak_darurat = $request->nama_kontak_darurat;
            $kontakDarurat->hubungan_kontak_darurat = $request->hubungan_kontak_darurat;
            $kontakDarurat->no_hp_kontak_darurat = $request->no_hp_kontak_darurat;
            $kontakDarurat->save();

            // Store Pengajuan
            $pengajuan = new PengajuanNasabahLuar();
            $pengajuan->nasabah_id = $nasabah->id;
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->status_pinjaman = $request->status_pinjaman;
            if ($request->status_pinjaman === 'lama') {
                $pengajuan->pinjaman_ke = $request->pinjaman_ke;
                $pengajuan->pinjaman_terakhir = $request->pinjaman_terakhir;
                $pengajuan->sisa_pinjaman = $request->sisa_pinjaman;
                $pengajuan->realisasi_pinjaman = $request->realisasi_pinjaman;
                $pengajuan->cicilan_perbulan = $request->cicilan_perbulan_pinjaman;
            }
            $pengajuan->jenis_pembiayaan = $request->jenis_pembiayaan;
            $pengajuan->status_pengajuan = 'pending';
            if ($request->hasFile('berkas_jaminan')) {
                $berkasJaminan = $request->file('berkas_jaminan');
                $berkasJaminanName = 'berkas_jaminan-' . $namaNasabah . '.' . $berkasJaminan->getClientOriginalExtension();
                $berkasJaminan->storeAs($FolderDokumen, $berkasJaminanName, 'public');
                $pengajuan->berkas_jaminan = $berkasJaminanName;
            }
            $pengajuan->catatan_marketing = $request->catatan_marketing;
            $pengajuan->save();

            NotificationSPV::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan Topup untuk nasabah ' . $nasabah->nama_lengkap . ' dengan jenis pembiayaan ' . $pengajuan->jenis_pembiayaan . ' telah ditambahkan oleh Marketing ' . Auth::user()->name,
                'read' => false,
            ]);

            DB::commit();

            return redirect()->route('marketingLuar.pengajuan')->with('success', 'Pengajuan Top Up nasabah luar berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error topup nasabah: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function banding(Request $request, $id)
    {
        $request->validate([
            'alasan_banding' => 'required|string',
        ]);

        $pengajuan = PengajuanNasabahLuar::findOrFail($id);
        $pengajuan->status_pengajuan = 'banding';
        $pengajuan->is_banding = 1; // Tandai sedang banding
        $pengajuan->alasan_banding = $request->alasan_banding;
        $pengajuan->save();

        NotificationCA::create([
            'pengajuan_luar_id' => $pengajuan->id,
            'pesan' => Auth::user()->name . ' telah mengajukan banding untuk pengajuan ' . $pengajuan->nasabahLuar->nama_lengkap . ', dengan alasan banding: ' . $request->alasan_banding . '.',
            'read' => false
        ]);

        return redirect()->route('marketingLuar.pengajuan')->with('success', 'Pengajuan banding berhasil diajukan.');
    }

    public function clear($id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);
        $pengajuan->is_banding = 2;
        $pengajuan->save();

        return redirect()->route('marketingLuar.pengajuan')->with('success', 'Pengajuan selesai.');
    }
}
