<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Luar\AlamatNasabahLuar;
use App\Models\Luar\FotoSurvey;
use App\Models\Luar\HasilSurveyPengajuan;
use App\Models\Luar\KontakDaruratNasabahLuar;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationCA;
use App\Models\Luar\PekerjaanNasabahLuar;
use App\Models\Luar\PengajuanBPJS;
use App\Models\Luar\PengajuanBpkb;
use App\Models\Luar\PengajuanKedinasan;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\Luar\PengajuanSHM;
use App\Models\Luar\PenjaminNasabahLuar;
use App\Models\Luar\PinjamanLainNasabahLuar;
use App\Models\Luar\TanggunganNasabahLuar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListPengajuanLuarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterTime = $request->input('filter_time');
        $month = $request->input('month');
        $year = $request->input('year');

        // Ambil tahun yang tersedia di tabel pengajuans
        $availableYears = NasabahLuar::whereHas('pengajuanLuar')
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $pengajuan = NasabahLuar::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuanLuar', function ($query) use ($filterTime, $month, $year) {
                // $query->whereIn('status_pengajuan', ['aproved ca', 'rejected ca']);
                // Filter berdasarkan waktu
                if ($filterTime == 'today') {
                    $query->whereDate('created_at', today());
                } elseif ($filterTime == 'this_month') {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                } elseif ($filterTime == 'this_year') {
                    $query->whereYear('created_at', now()->year);
                }

                // Filter bulan tertentu
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }

                // Filter tahun tertentu
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            })
            ->with([
                'user',
                'pengajuanLuar' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->get();

        return view('superAdmin.list-pengajuan-luar', compact('pengajuan', 'availableYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        $isSurvey = $pengajuan->hasilSurvey()->exists();

        return view('superAdmin.detail-pengajuan-luar', compact('pengajuan', 'isSurvey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        $isSurvey = $pengajuan->hasilSurvey()->exists();

        return view('superAdmin.form-edit-pengajuan-luar', compact('pengajuan', 'isSurvey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            'lama_tinggal' => 'required|string',
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

            'hubungan_penjamin' => 'required|string',
            'nama_penjamin' => 'required|string|max:255',
            'pekerjaan_penjamin' => 'required|string|max:255',
            'penghasilan_penjamin' => 'required|numeric',
            'no_hp_penjamin' => 'required|string|max:255',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'kondisi_tanggungan' => 'required|string|max:500',

            'cicilan_lain' => 'required|in:Ada,Tidak',
            'nama_pembiayaan.*' => 'nullable|required_if:cicilan_lain,Ada|string|max:255',
            'total_pinjaman.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'cicilan_perbulan.*' => 'nullable|required_if:cicilan_lain,Ada|numeric|min:0',
            'sisa_tenor_cicilan.*' => 'nullable|required_if:cicilan_lain,Ada|integer|min:0',

            'nama_kontak_darurat' => 'required|string|max:255',
            'hubungan_kontak_darurat' => 'required|string|max:255',
            'no_hp_kontak_darurat' => 'required|string|max:255',

            'status_pinjaman' => 'required|string',
            'jenis_pembiayaan' => 'required|string',
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|integer',

            // 'validasi_nasabah' => 'required|boolean',
            // 'validasi_alamat' => 'required|boolean',
            // 'validasi_pekerjaan' => 'required|boolean',
            // 'validasi_penjamin' => 'required|boolean',
            // 'validasi_tanggungan' => 'required|boolean',
            // 'validasi_kontak_darurat' => 'required|boolean',
            // 'validasi_pengajuan' => 'required|boolean',
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
            if (!Auth::user()->usertype == 'root') {
                $nasabah->marketing_id = $marketingId;
            }
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

            $pengajuan->status_pengajuan = $request->status_pengajuan;
            $pengajuan->save();

            switch ($request->jenis_pembiayaan) {
                case 'BPJS':
                    $request->validate([
                        'saldo_bpjs' => 'nullable|numeric',
                        'tanggal_bayar_terakhir' => 'nullable|date',
                        'username' => 'nullable|string',
                        'password' => 'nullable|string',
                        'foto_bukti_bpjs' => 'nullable|file|mimes:pdf|max:2048',
                        'foto_jaminan_tambahan' => 'nullable|file|mimes:pdf|max:2048',
                    ]);

                    $bpjs = PengajuanBPJS::updateOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        [
                            'saldo_bpjs' => $request->saldo_bpjs,
                            'tanggal_bayar_terakhir' => $request->tanggal_bayar_terakhir,
                            'username' => $request->username,
                            'password' => $request->password,
                        ]
                    );

                    if ($request->hasFile('foto_bukti_bpjs')) {
                        $bpjsFile = $request->file('foto_bukti_bpjs');
                        if ($bpjs->foto_bpjs) {
                            Storage::disk('public')->delete($bpjs->foto_bpjs);
                        }
                        $bpjsFileName = 'bukti_bpjs-' . $namaNasabah . '.' . $bpjsFile->getClientOriginalExtension();
                        $bpjsFile->storeAs($FolderDokumen, $bpjsFileName, 'public');
                        $bpjs->foto_bpjs = $bpjsFileName;
                    }

                    if ($request->hasFile('foto_jaminan_tambahan')) {
                        $jaminanTambahanFile = $request->file('foto_jaminan_tambahan');
                        if ($bpjs->foto_jaminan_tambahan) {
                            Storage::disk('public')->delete($bpjs->foto_jaminan_tambahan);
                        }
                        $jaminanTambahanFileName = 'jaminan_tambahan-' . $namaNasabah . '.' . $jaminanTambahanFile->getClientOriginalExtension();
                        $jaminanTambahanFile->storeAs($FolderDokumen, $jaminanTambahanFileName, 'public');
                        $bpjs->foto_jaminan_tambahan = $jaminanTambahanFileName;
                    }

                    $bpjs->save();
                    break;

                case 'SHM':
                    $request->validate([
                        'atas_nama_shm' => 'nullable|string',
                        'alamat_shm' => 'nullable|string',
                        'hubungan_shm' => 'nullable|string',
                        'luas_shm' => 'nullable|string',
                        'njop_shm' => 'nullable|string',
                        'foto_shm' => 'nullable|file|mimes:pdf|max:2048',
                        'foto_kk_pemilik_shm' => 'nullable|file|mimes:pdf|max:2048',
                        'foto_pbb' => 'nullable|file|mimes:pdf|max:2048',
                    ]);

                    $shm = PengajuanSHM::updateOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        [
                            'atas_nama_shm' => $request->atas_nama_shm,
                            'alamat_shm' => $request->alamat_shm,
                            'hubungan_shm' => $request->hubungan_shm,
                            'luas_shm' => $request->luas_shm,
                            'njop_shm' => $request->njop_shm,
                        ]
                    );

                    if ($request->hasFile('foto_kk_pemilik_shm')) {
                        $kkPemilikShmFile = $request->file('foto_kk_pemilik_shm');
                        if ($shm->foto_kk_pemilik_shm) {
                            Storage::disk('public')->delete($shm->foto_kk_pemilik_shm);
                        }
                        $kkPemilikShmFileName = 'kk_pemilik_shm-' . $namaNasabah . '.' . $kkPemilikShmFile->getClientOriginalExtension();
                        $kkPemilikShmFile->storeAs($FolderDokumen, $kkPemilikShmFileName, 'public');
                        $shm->foto_kk_pemilik_shm = $kkPemilikShmFileName;
                    }

                    if ($request->hasFile('foto_shm')) {
                        $shmFile = $request->file('foto_shm');
                        if ($shm->foto_shm) {
                            Storage::disk('public')->delete($shm->foto_shm);
                        }
                        $shmFileName = 'shm-' . $namaNasabah . '.' . $shmFile->getClientOriginalExtension();
                        $shmFile->storeAs($FolderDokumen, $shmFileName, 'public');
                        $shm->foto_shm = $shmFileName;
                    }

                    if ($request->hasFile('foto_pbb')) {
                        $pbbFile = $request->file('foto_pbb');
                        if ($shm->foto_pbb) {
                            Storage::disk('public')->delete($shm->foto_pbb);
                        }
                        $pbbFileName = 'pbb-' . $namaNasabah . '.' . $pbbFile->getClientOriginalExtension();
                        $pbbFile->storeAs($FolderDokumen, $pbbFileName, 'public');
                        $shm->foto_pbb = $pbbFileName;
                    }

                    $shm->save();
                    break;

                case 'BPKB':
                    $request->validate([
                        'no_stnk' => 'nullable|string',
                        'atas_nama_bpkb' => 'nullable|string',
                        'alamat_pemilik_bpkb' => 'nullable|string',
                        'type_kendaraan' => 'nullable|string',
                        'tahun_perakitan' => 'nullable|string',
                        'warna_kendaraan' => 'nullable|string',
                        'stransmisi' => 'nullable|string',
                        'no_rangka' => 'nullable|string',
                        'no_mesin' => 'nullable|string',
                        'no_bpkb' => 'nullable|string',
                        'foto_stnk' => 'nullable|file|mimes:pdf|max:2048',
                        'foto_kk_pemilik_bpkb' => 'nullable|file|mimes:pdf|max:2048',
                        'foto_motor' => 'nullable|file|mimes:pdf|max:2048',
                    ]);

                    $bpkb = PengajuanBpkb::updateOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        [
                            'no_stnk' => $request->no_stnk,
                            'atas_nama_bpkb' => $request->atas_nama_bpkb,
                            'alamat_pemilik_bpkb' => $request->alamat_pemilik_bpkb,
                            'type_kendaraan' => $request->type_kendaraan,
                            'tahun_perakitan' => $request->tahun_perakitan,
                            'warna_kendaraan' => $request->warna_kendaraan,
                            'stransmisi' => $request->stransmisi,
                            'no_rangka' => $request->no_rangka,
                            'no_mesin' => $request->no_mesin,
                            'no_bpkb' => $request->no_bpkb,
                        ]
                    );

                    if ($request->hasFile('foto_stnk')) {
                        $stnkFile = $request->file('foto_stnk');
                        if ($bpkb->foto_stnk) {
                            Storage::disk('public')->delete($bpkb->foto_stnk);
                        }
                        $stnkFileName = 'stnk-' . $namaNasabah . '.' . $stnkFile->getClientOriginalExtension();
                        $stnkFile->storeAs($FolderDokumen, $stnkFileName, 'public');
                        $bpkb->foto_stnk = $stnkFileName;
                    }

                    if ($request->hasFile('foto_kk_pemilik_bpkb')) {
                        $kkPemilikBpkbFile = $request->file('foto_kk_pemilik_bpkb');
                        if ($bpkb->foto_kk_pemilik_bpkb) {
                            Storage::disk('public')->delete($bpkb->foto_kk_pemilik_bpkb);
                        }
                        $kkPemilikBpkbFileName = 'kk_pemilik_bpkb-' . $namaNasabah . '.' . $kkPemilikBpkbFile->getClientOriginalExtension();
                        $kkPemilikBpkbFile->storeAs($FolderDokumen, $kkPemilikBpkbFileName, 'public');
                        $bpkb->foto_kk_pemilik_bpkb = $kkPemilikBpkbFileName;
                    }

                    if ($request->hasFile('foto_motor')) {
                        $motorFile = $request->file('foto_motor');
                        if ($bpkb->foto_motor) {
                            Storage::disk('public')->delete($bpkb->foto_motor);
                        }
                        $motorFileName = 'motor-' . $namaNasabah . '.' . $motorFile->getClientOriginalExtension();
                        $motorFile->storeAs($FolderDokumen, $motorFileName, 'public');
                        $bpkb->foto_motor = $motorFileName;
                    }

                    $bpkb->save();
                    break;

                case 'Kedinasan':
                    $request->validate([
                        'instansi' => 'nullable|string',
                        'surat_permohonan_kredit' => 'nullable|file|mimes:pdf|max:2048',
                        'surat_pernyataan_penjamin' => 'nullable|file|mimes:pdf|max:2048',
                        'surat_persetujuan_pimpinan' => 'nullable|file|mimes:pdf|max:2048',
                        'surat_keterangan_gaji' => 'nullable|file|mimes:pdf|max:2048',
                    ]);

                    $kedinasan = PengajuanKedinasan::updateOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        ['instansi' => $request->instansi]
                    );
                    if ($request->hasFile('surat_permohonan_kredit')) {
                        $suratPermohonanKreditFile = $request->file('surat_permohonan_kredit');
                        if ($kedinasan->surat_permohonan_kredit) {
                            Storage::disk('public')->delete($kedinasan->surat_permohonan_kredit);
                        }
                        $suratPermohonanKreditFileName = 'surat_permohonan_kredit-' . $namaNasabah . '.' . $suratPermohonanKreditFile->getClientOriginalExtension();
                        $suratPermohonanKreditFile->storeAs($FolderDokumen, $suratPermohonanKreditFileName, 'public');
                        $kedinasan->surat_permohonan_kredit = $suratPermohonanKreditFileName;
                    }

                    if ($request->hasFile('surat_pernyataan_penjamin')) {
                        $suratPernyataanPenjaminFile = $request->file('surat_pernyataan_penjamin');
                        if ($kedinasan->surat_pernyataan_penjamin) {
                            Storage::disk('public')->delete($kedinasan->surat_pernyataan_penjamin);
                        }
                        $suratPernyataanPenjaminFileName = 'surat_pernyataan_penjamin-' . $namaNasabah . '.' . $suratPernyataanPenjaminFile->getClientOriginalExtension();
                        $suratPernyataanPenjaminFile->storeAs($FolderDokumen, $suratPernyataanPenjaminFileName, 'public');
                        $kedinasan->surat_pernyataan_penjamin = $suratPernyataanPenjaminFileName;
                    }

                    if ($request->hasFile('surat_persetujuan_pimpinan')) {
                        $suratPersetujuanPimpinanFile = $request->file('surat_persetujuan_pimpinan');
                        if ($kedinasan->surat_persetujuan_pimpinan) {
                            Storage::disk('public')->delete($kedinasan->surat_persetujuan_pimpinan);
                        }
                        $suratPersetujuanPimpinanFileName = 'surat_persetujuan_pimpinan-' . $namaNasabah . '.' . $suratPersetujuanPimpinanFile->getClientOriginalExtension();
                        $suratPersetujuanPimpinanFile->storeAs($FolderDokumen, $suratPersetujuanPimpinanFileName, 'public');
                        $kedinasan->surat_persetujuan_pimpinan = $suratPersetujuanPimpinanFileName;
                    }

                    if ($request->hasFile('surat_keterangan_gaji')) {
                        $suratKeteranganGajiFile = $request->file('surat_keterangan_gaji');
                        if ($kedinasan->surat_keterangan_gaji) {
                            Storage::disk('public')->delete($kedinasan->surat_keterangan_gaji);
                        }
                        $suratKeteranganGajiFileName = 'surat_keterangan_gaji-' . $namaNasabah . '.' . $suratKeteranganGajiFile->getClientOriginalExtension();
                        $suratKeteranganGajiFile->storeAs($FolderDokumen, $suratKeteranganGajiFileName, 'public');
                        $kedinasan->surat_keterangan_gaji = $suratKeteranganGajiFileName;
                    }

                    $kedinasan->save();
                    break;

                default:
                    break;
            }

            if ($isRevisi) {
                NotificationCA::create([
                    'pengajuan_luar_id' => $pengajuan->id,
                    'pesan' => 'Pengajuan atas name ' . $nasabah->nama_lengkap . ' telah di Revisi oleh Marketing ' . Auth::user()->name,
                    'read' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('superAdmin.data.pengajuan.luar')->with('success', 'Pengajuan nasabah luar berhasil diedit.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating nasabah: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pengajuan = PengajuanNasabahLuar::findOrFail($id);
            $pengajuan->delete();
            // $pengajuanbpjs = PengajuanBPJS::where('pengajuan_id', $id)->first();
            // if ($pengajuanbpjs) {
            //     $pengajuanbpjs->delete();
            // }
            // $pengajuanbpkb = PengajuanBpkb::where('pengajuan_id', $id)->first();
            // if ($pengajuanbpkb) {
            //     $pengajuanbpkb->delete();
            // }
            // $pengajuanshm = PengajuanSHM::where('pengajuan_id', $id)->first();
            // if ($pengajuanshm) {
            //     $pengajuanshm->delete();
            // }
            // $pengajuanKedinasan = PengajuanKedinasan::where('pengajuan_id', $id)->first();
            // if ($pengajuanKedinasan) {
            //     $pengajuanKedinasan->delete();
            // }
            // $hasilSurvey = HasilSurveyPengajuan::where('pengajuan_id', $id)->first();
            // if ($hasilSurvey) {
            //     $hasilSurvey->delete();
            // }
            // $fotoHasilSurvey = FotoSurvey::where('pengajuan_id', $id)->first();
            // if ($fotoHasilSurvey) {
            //     $fotoHasilSurvey->delete();
            // }

            return redirect()->route('superAdmin.data.pengajuan.luar')->with('success', 'Pengajuan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('superAdmin.data.pengajuan.luar')->with('error', 'Pengajuan gagal dihapus');
        }
    }
}
