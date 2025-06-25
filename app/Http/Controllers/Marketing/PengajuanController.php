<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Jaminan;
use App\Models\Keluarga;
use App\Models\Kerabat;
use App\Models\Nasabah;
use App\Models\NotifactionCreditAnalyst;
use App\Models\NotifactionHeadMarketing;
use App\Models\NotifactionSupervisor;
use App\Models\Pekerjaan;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengajuanController extends Controller
{
    public function index()
    {
        // dd(Route::currentRouteName());

        $user = Auth::user();

        $marketing = User::where('usertype', 'marketing')
            ->where('id', $user->id)
            ->get();

        $pengajuan = Nasabah::where('marketing_id', $user->id)
            ->with('alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval')
            ->get();

        return view('marketing.manajemen-nasabah', compact('marketing', 'pengajuan'));
    }

    public function form()
    {
        return view('marketing.form-pengajuan');
    }

    public function store(Request $request)
    {
        $marketing_id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status_nikah' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',

            'alamat_ktp' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:100',
            'rt_rw' => 'required|string|max:10',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'domisili' => 'required|string',
            'status_rumah_ktp' => 'required|string',
            'status_rumah' => 'nullable|string',

            'hubungan' => 'required|string',
            'nama_keluarga' => 'required|string|max:255',
            'bekerja' => 'required|string',
            'no_hp_keluarga' => 'required|string|max:15',
            'kerabat_kerja' => 'required|string',

            'perusahaan' => 'required|string',
            'divisi' => 'required|string|max:100',
            'lama_kerja_tahun' => 'required|integer',
            'lama_kerja_bulan' => 'required|integer',
            'golongan' => 'required|string',
            'nama_atasan' => 'required|string|max:255',
            'nama_hrd' => 'required|string|max:255',
            'absensi' => 'required|string|max:255',
            'bukti_absensi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'status_pinjaman' => 'required|string',
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|integer',
            'keperluan' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            'jaminan_hrd' => 'required|string|max:255',
            'jaminan_cg' => 'required|string|max:255',
            'penjamin' => 'nullable|string',

            'foto_id_card' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_rekening' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'no_rekening' => 'nullable|string|max:255',
            'foto_id_card_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi tambahan untuk file yang tidak valid
        $files = ['foto_ktp', 'foto_id_card', 'foto_kk'];
        foreach ($files as $file) {
            if ($request->hasFile($file) && !$request->file($file)->isValid()) {
                return redirect()->back()->withErrors([$file => "Upload $file gagal, silakan coba lagi."])->withInput();
            }
        }

        // Log::info('Request data:', $request->all());

        // dd($request->all());

        DB::beginTransaction();

        try {
            $nasabah = new Nasabah();
            $nasabah->marketing_id = $marketing_id;
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->no_ktp = $request->no_ktp;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->tempat_lahir = $request->tempat_lahir;
            $nasabah->tanggal_lahir = $request->tanggal_lahir;
            $nasabah->status_nikah = $request->status_nikah;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            $nasabah->save();

            $namaNasabah = str_replace(' ', '-', strtolower($request->nama_lengkap));
            $FolderDokumen = 'dokumen_pendukung/' . $namaNasabah . '-' . $nasabah->id;

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

            if ($request->hasFile('foto_id_card')) {
                $idCardFile = $request->file('foto_id_card');
                $idCardFileName = 'id-card-' . $namaNasabah . '.' . $idCardFile->getClientOriginalExtension();
                $idCardFile->storeAs($FolderDokumen, $idCardFileName, 'public');
                $nasabah->foto_id_card = $idCardFileName;
            }

            if ($request->hasFile('foto_rekening')) {
                $rekeningFile = $request->file('foto_rekening');
                $rekeningFileName = 'rekening-' . $namaNasabah . '.' . $rekeningFile->getClientOriginalExtension();
                $rekeningFile->storeAs($FolderDokumen, $rekeningFileName, 'public');
                $nasabah->foto_rekening = $rekeningFileName;
            }
            $nasabah->no_rekening = $request->no_rekening;
            $nasabah->save();

            // Simpan data alamat
            $alamat = new Alamat();
            $alamat->nasabah_id = $nasabah->id;
            $alamat->alamat_ktp = $request->alamat_ktp;
            $alamat->kelurahan = $request->kelurahan;
            $alamat->rt_rw = $request->rt_rw;
            $alamat->kecamatan = $request->kecamatan;
            $alamat->kota = $request->kota;
            $alamat->provinsi = $request->provinsi;
            $alamat->status_rumah_ktp = $request->status_rumah_ktp;
            $alamat->domisili = $request->domisili;
            if ($request->domisili === 'tidak sesuai ktp') {
                $alamat->alamat_lengkap = $request->alamat_lengkap;
                $alamat->status_rumah = $request->status_rumah;
            }
            $alamat->save();

            // Simpan data keluarga
            $keluarga = new Keluarga();
            $keluarga->nasabah_id = $nasabah->id;
            $keluarga->hubungan = $request->hubungan;
            $keluarga->nama = $request->nama_keluarga;
            $keluarga->bekerja = $request->bekerja;
            $keluarga->no_hp = $request->no_hp_keluarga;
            if ($request->bekerja === 'ya') {
                $keluarga->nama_perusahaan = $request->nama_perusahaan;
                $keluarga->jabatan = $request->jabatan;
                $keluarga->penghasilan = $request->penghasilan;
                $keluarga->alamat_kerja = $request->alamat_kerja;
            }
            $keluarga->save();

            $kerabat = new Kerabat();
            $kerabat->nasabah_id = $nasabah->id;
            $kerabat->kerabat_kerja = $request->kerabat_kerja;
            if ($request->kerabat_kerja === 'ya') {
                $kerabat->nama = $request->nama_kerabat;
                $kerabat->alamat = $request->alamat_kerabat;
                $kerabat->no_hp = $request->no_hp_kerabat;
                $kerabat->status_hubungan = $request->status_hubungan_kerabat;
                $kerabat->nama_perusahaan = $request->nama_perusahaan_kerabat;
            }
            $kerabat->save();

            $pekerjaan = new Pekerjaan();
            $pekerjaan->nasabah_id = $nasabah->id;
            $pekerjaan->perusahaan = $request->perusahaan;
            $pekerjaan->divisi = $request->divisi;
            $pekerjaan->lama_kerja_bulan = $request->lama_kerja_bulan;
            $pekerjaan->lama_kerja_tahun = $request->lama_kerja_tahun;
            $pekerjaan->golongan = $request->golongan;
            if ($request->golongan === 'Borongan') {
                $pekerjaan->yayasan = $request->yayasan;
            }
            $pekerjaan->nama_atasan = $request->nama_atasan;
            $pekerjaan->nama_hrd = $request->nama_hrd;
            $pekerjaan->absensi = $request->absensi;
            if ($request->hasFile('bukti_absensi')) {
                $buktiAbsensi = $request->file('bukti_absensi');
                $buktiAbsensiName = 'bukti-absensi-' . $namaNasabah . '.' . $buktiAbsensi->getClientOriginalExtension();
                $buktiAbsensi->storeAs($FolderDokumen, $buktiAbsensiName, 'public');
                $pekerjaan->bukti_absensi = $buktiAbsensiName;
            }
            $pekerjaan->save();

            $pengajuan = new Pengajuan();
            $pengajuan->nasabah_id = $nasabah->id;
            $pengajuan->status_pinjaman = $request->status_pinjaman;
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->keperluan = $request->keperluan;
            if ($request->status_pinjaman === 'lama') {
                $pengajuan->pinjaman_ke = $request->pinjaman_ke;
                $pengajuan->riwayat_nominal = $request->riwayat_nominal;
                $pengajuan->riwayat_tenor = $request->riwayat_tenor;
                $pengajuan->sisa_pinjaman = $request->sisa_pinjaman;
            }
            $pengajuan->notes = $request->notes;
            $pengajuan->save();

            $jaminan = new Jaminan();
            $jaminan->nasabah_id = $nasabah->id;
            $jaminan->jaminan_hrd = $request->jaminan_hrd;
            $jaminan->jaminan_cg = $request->jaminan_cg;
            $jaminan->penjamin = $request->penjamin;
            if ($request->penjamin === 'ada') {
                $jaminan->nama_penjamin = $request->nama_penjamin;
                $jaminan->bagian = $request->bagian;
                $jaminan->lama_kerja_penjamin = $request->lama_kerja_penjamin;
                $jaminan->absensi = $request->absensi_penjamin;
                $jaminan->status_hubungan_penjamin = $request->status_hubungan_penjamin;
                $jaminan->riwayat_pinjam_penjamin = $request->riwayat_pinjam_penjamin;
                if ($request->riwayat_pinjam_penjamin === 'ada') {
                    $jaminan->riwayat_nominal_penjamin = $request->riwayat_nominal_penjamin;
                    $jaminan->riwayat_tenor_penjamin = $request->riwayat_tenor_penjamin;
                    $jaminan->sisa_pinjaman_penjamin = $request->sisa_pinjaman_penjamin;
                    $jaminan->jaminan_cg_penjamin = $request->jaminan_cg_penjamin;
                }

                if ($request->hasFile('foto_id_card_penjamin')) {
                    $idCardPenjaminFile = $request->file('foto_id_card_penjamin');
                    $idCardPenjaminFileName = 'id-card-penjamin-' . $namaNasabah . '.' . $idCardPenjaminFile->getClientOriginalExtension();
                    $idCardPenjaminFile->storeAs($FolderDokumen, $idCardPenjaminFileName, 'public');
                    $jaminan->foto_id_card_penjamin = $idCardPenjaminFileName;
                }

                if ($request->hasFile('foto_ktp_penjamin')) {
                    $ktpPenjaminFile = $request->file('foto_ktp_penjamin');
                    $ktpPenjaminFileName = 'ktp-penjamin-' . $namaNasabah . '.' . $ktpPenjaminFile->getClientOriginalExtension();
                    $ktpPenjaminFile->storeAs($FolderDokumen, $ktpPenjaminFileName, 'public');
                    $jaminan->foto_ktp_penjamin = $ktpPenjaminFileName;
                }
            }
            $jaminan->save();

            NotifactionSupervisor::create([
                'pengajuan_id' => $pengajuan->id,
                'pesan' => 'Pengajuan baru telah ditambahkan oleh Marketing ' . Auth::user()->name,
                'read' => false
            ]);

            DB::commit();

            Log::info('Data nasabah berhasil ditambahkan:', $nasabah->toArray());

            return redirect()->route('marketing.data.pengajuan')->with('success', 'Data Nasabah Berhasil Ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan data:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('marketing.data.pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('marketing.form-edit-pengajuan', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $marketing_id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status_nikah' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'alamat_ktp' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:100',
            'rt_rw' => 'required|string|max:10',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'domisili' => 'required|string',
            'status_rumah_ktp' => 'required|string',
            'status_rumah' => 'nullable|string',
            'hubungan' => 'required|string',
            'nama_keluarga' => 'required|string|max:255',
            'bekerja' => 'required|string',
            'no_hp_keluarga' => 'required|string|max:15',
            'kerabat_kerja' => 'required|string',
            'perusahaan' => 'required|string',
            'divisi' => 'required|string|max:100',
            'lama_kerja_tahun' => 'required|integer',
            'lama_kerja_bulan' => 'required|integer',
            'golongan' => 'required|string',
            'nama_atasan' => 'required|string|max:255',
            'nama_hrd' => 'required|string|max:255',
            'absensi' => 'required|string|max:255',
            'bukti_absensi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_pinjaman' => 'required|string',
            'nominal_pinjaman' => 'required|numeric',
            'tenor' => 'required|integer',
            'keperluan' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'jaminan_hrd' => 'required|string|max:255',
            'jaminan_cg' => 'required|string|max:255',
            'penjamin' => 'nullable|string',

            'foto_id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_rekening' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'no_rekening' => 'nullable|string|max:255',
            'foto_id_card_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp_penjamin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Log::info('Request data edit:', $request->all());

        DB::beginTransaction();

        try {
            // Update data nasabah
            $nasabah = Nasabah::findOrFail($id);
            $nasabah->marketing_id = $marketing_id;
            $nasabah->nama_lengkap = $request->nama_lengkap;
            $nasabah->no_ktp = $request->no_ktp;
            $nasabah->jenis_kelamin = $request->jenis_kelamin;
            $nasabah->tempat_lahir = $request->tempat_lahir;
            $nasabah->tanggal_lahir = $request->tanggal_lahir;
            $nasabah->status_nikah = $request->status_nikah;
            $nasabah->no_hp = $request->no_hp;
            $nasabah->email = $request->email;
            if ($nasabah->enable_edit = 1) {
                $nasabah->enable_edit = 0;
            }
            $nasabah->save();

            $namaNasabah = str_replace(' ', '-', strtolower($request->nama_lengkap));
            $FolderDokumen = 'dokumen_pendukung/' . $namaNasabah . '-' . $nasabah->id;

            // Update foto KTP
            if ($request->hasFile('foto_ktp')) {
                if ($nasabah->foto_ktp && Storage::exists($FolderDokumen . '/' . $nasabah->foto_ktp)) {
                    Storage::delete($FolderDokumen . '/' . $nasabah->foto_ktp);
                }
                $ktpFile = $request->file('foto_ktp');
                $ktpFileName = 'ktp-' . $namaNasabah . '.' . $ktpFile->getClientOriginalExtension();
                $ktpFile->storeAs($FolderDokumen, $ktpFileName, 'public');
                $nasabah->foto_ktp = $ktpFileName;
            }

            if ($request->hasFile('foto_kk')) {
                if ($nasabah->foto_kk && Storage::exists($FolderDokumen . '/' . $nasabah->foto_kk)) {
                    Storage::delete($FolderDokumen . '/' . $nasabah->foto_kk);
                }
                $kkFile = $request->file('foto_kk');
                $kkFileName = 'kk-' . $namaNasabah . '.' . $kkFile->getClientOriginalExtension();
                $kkFile->storeAs($FolderDokumen, $kkFileName, 'public');
                $nasabah->foto_kk = $kkFileName;
            }

            if ($request->hasFile('foto_id_card')) {
                if ($nasabah->foto_id_card && Storage::exists($FolderDokumen . '/' . $nasabah->foto_id_card)) {
                    Storage::delete($FolderDokumen . '/' . $nasabah->foto_id_card);
                }
                $idCardFile = $request->file('foto_id_card');
                $idCardFileName = 'id-card-' . $namaNasabah . '.' . $idCardFile->getClientOriginalExtension();
                $idCardFile->storeAs($FolderDokumen, $idCardFileName, 'public');
                $nasabah->foto_id_card = $idCardFileName;
            }

            if ($request->hasFile('foto_rekening')) {
                if ($nasabah->foto_rekening && Storage::exists($FolderDokumen . '/' . $nasabah->foto_rekening)) {
                    Storage::delete($FolderDokumen . '/' . $nasabah->foto_rekening);
                }
                $rekeningFile = $request->file('foto_rekening');
                $rekeningFileName = 'rekening-' . $namaNasabah . '.' . $rekeningFile->getClientOriginalExtension();
                $rekeningFile->storeAs($FolderDokumen, $rekeningFileName, 'public');
                $nasabah->foto_rekening = $rekeningFileName;
            }
            $nasabah->no_rekening = $request->no_rekening;
            $nasabah->save();

            // Update data alamat
            $alamat = Alamat::where('nasabah_id', $nasabah->id)->first();
            $alamat->alamat_ktp = $request->alamat_ktp;
            $alamat->kelurahan = $request->kelurahan;
            $alamat->rt_rw = $request->rt_rw;
            $alamat->kecamatan = $request->kecamatan;
            $alamat->kota = $request->kota;
            $alamat->provinsi = $request->provinsi;
            $alamat->status_rumah_ktp = $request->status_rumah_ktp;
            $alamat->domisili = $request->domisili;
            if ($request->domisili === 'tidak sesuai ktp') {
                $alamat->alamat_lengkap = $request->alamat_lengkap;
                $alamat->status_rumah = $request->status_rumah;
            }
            $alamat->save();

            // Update data keluarga
            $keluarga = Keluarga::where('nasabah_id', $nasabah->id)->first();
            $keluarga->hubungan = $request->hubungan;
            $keluarga->nama = $request->nama_keluarga;
            $keluarga->bekerja = $request->bekerja;
            $keluarga->no_hp = $request->no_hp_keluarga;
            if ($request->bekerja === 'ya') {
                $keluarga->nama_perusahaan = $request->nama_perusahaan;
                $keluarga->jabatan = $request->jabatan;
                $keluarga->penghasilan = $request->penghasilan;
                $keluarga->alamat_kerja = $request->alamat_kerja;
            }
            $keluarga->save();

            // Update data kerabat
            $kerabat = Kerabat::where('nasabah_id', $nasabah->id)->first();
            $kerabat->kerabat_kerja = $request->kerabat_kerja;
            if ($request->kerabat_kerja === 'ya') {
                $kerabat->nama = $request->nama_kerabat;
                $kerabat->alamat = $request->alamat_kerabat;
                $kerabat->no_hp = $request->no_hp_kerabat;
                $kerabat->status_hubungan = $request->status_hubungan_kerabat;
            }
            $kerabat->save();

            // Update data pekerjaan
            $pekerjaan = Pekerjaan::where('nasabah_id', $nasabah->id)->first();
            $pekerjaan->perusahaan = $request->perusahaan;
            $pekerjaan->divisi = $request->divisi;
            $pekerjaan->lama_kerja_tahun = $request->lama_kerja_tahun;
            $pekerjaan->lama_kerja_bulan = $request->lama_kerja_bulan;
            $pekerjaan->golongan = $request->golongan;
            if ($request->golongan === 'Borongan') {
                $pekerjaan->yayasan = $request->yayasan;
            }
            $pekerjaan->nama_atasan = $request->nama_atasan;
            $pekerjaan->nama_hrd = $request->nama_hrd;
            $pekerjaan->absensi = $request->absensi;
            if ($request->hasFile('bukti_absensi')) {
                if ($pekerjaan->bukti_absensi && Storage::exists($FolderDokumen . '/' . $pekerjaan->bukti_absensi)) {
                    Storage::delete($FolderDokumen . '/' . $pekerjaan->bukti_absensi);
                }
                $buktiAbsensi = $request->file('bukti_absensi');
                $buktiAbsensiName = 'bukti-absensi-' . $namaNasabah . '.' . $buktiAbsensi->getClientOriginalExtension();
                $buktiAbsensi->storeAs($FolderDokumen, $buktiAbsensiName, 'public');
                $pekerjaan->bukti_absensi = $buktiAbsensiName;
            }
            $pekerjaan->save();

            // Update data pengajuan
            $pengajuan = Pengajuan::where('nasabah_id', $nasabah->id)->first();
            $pengajuan->status_pinjaman = $request->status_pinjaman;
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->keperluan = $request->keperluan;
            if ($request->status_pinjaman === 'lama') {
                $pengajuan->pinjaman_ke = $request->pinjaman_ke;
                $pengajuan->riwayat_nominal = $request->riwayat_nominal;
                $pengajuan->riwayat_tenor = $request->riwayat_tenor;
                $pengajuan->sisa_pinjaman = $request->sisa_pinjaman;
            }
            $pengajuan->notes = $request->notes;
            $pengajuan->save();

            // Update data jaminan
            $jaminan = Jaminan::where('nasabah_id', $nasabah->id)->first();
            $jaminan->jaminan_hrd = $request->jaminan_hrd;
            $jaminan->jaminan_cg = $request->jaminan_cg;
            $jaminan->penjamin = $request->penjamin;
            if ($request->penjamin === 'ada') {
                $jaminan->nama_penjamin = $request->nama_penjamin;
                $jaminan->bagian = $request->bagian;
                $jaminan->lama_kerja_penjamin = $request->lama_kerja_penjamin;
                $jaminan->absensi = $request->absensi_penjamin;
                $jaminan->status_hubungan_penjamin = $request->status_hubungan_penjamin;
                $jaminan->riwayat_pinjam_penjamin = $request->riwayat_pinjam_penjamin;
                if ($request->riwayat_pinjam_penjamin === 'ada') {
                    $jaminan->riwayat_nominal_penjamin = $request->riwayat_nominal_penjamin;
                    $jaminan->riwayat_tenor_penjamin = $request->riwayat_tenor_penjamin;
                    $jaminan->sisa_pinjaman_penjamin = $request->sisa_pinjaman_penjamin;
                    $jaminan->jaminan_cg_penjamin = $request->jaminan_cg_penjamin;
                }

                if ($request->hasFile('foto_id_card_penjamin')) {
                    if ($jaminan->foto_id_card_penjamin && Storage::exists($FolderDokumen . '/' . $jaminan->foto_id_card_penjamin)) {
                        Storage::delete($FolderDokumen . '/' . $jaminan->foto_id_card_penjamin);
                    }
                    $idCardPenjaminFile = $request->file('foto_id_card_penjamin');
                    $idCardPenjaminFileName = 'id-card-penjamin-' . $namaNasabah . '.' . $idCardPenjaminFile->getClientOriginalExtension();
                    $idCardPenjaminFile->storeAs($FolderDokumen, $idCardPenjaminFileName, 'public');
                    $jaminan->foto_id_card_penjamin = $idCardPenjaminFileName;
                }

                if ($request->hasFile('foto_ktp_penjamin')) {
                    if ($jaminan->foto_ktp_penjamin && Storage::exists($FolderDokumen . '/' . $jaminan->foto_ktp_penjamin)) {
                        Storage::delete($FolderDokumen . '/' . $jaminan->foto_ktp_penjamin);
                    }
                    $ktpPenjaminFile = $request->file('foto_ktp_penjamin');
                    $ktpPenjaminFileName = 'ktp-penjamin-' . $namaNasabah . '.' . $ktpPenjaminFile->getClientOriginalExtension();
                    $ktpPenjaminFile->storeAs($FolderDokumen, $ktpPenjaminFileName, 'public');
                    $jaminan->foto_ktp_penjamin = $ktpPenjaminFileName;
                }
            }
            $jaminan->save();

            DB::commit();

            Log::info('Data nasabah berhasil diperbarui:', $nasabah->toArray());

            return redirect()->route('marketing.data.pengajuan')->with('success', 'Data Nasabah Berhasil Diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan data edit:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('marketing.data.pengajuan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Cari data nasabah beserta relasinya
        $nasabah = Nasabah::with(['user', 'alamat', 'jaminan', 'keluarga', 'kerabat', 'pekerjaan', 'pengajuan.approval'])
            ->findOrFail($id);

        // Return view dengan data nasabah
        return view('marketing.detail-pengajuan-marketing', compact('nasabah'));
    }

    public function banding(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'alasan_banding' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'banding';
        $pengajuan->is_banding = 1; // Tandai sedang banding
        $pengajuan->alasan_banding = $request->alasan_banding;
        $pengajuan->save();

        NotifactionCreditAnalyst::create([
            'pengajuan_id' => $pengajuan->id,
            'pesan' => $user->name . ' telah mengajukan banding untuk pengajuan ' . $pengajuan->nasabah->nama_lengkap . ', dengan alasan banding: ' . $request->alasan_banding . '.',
            'read' => false
        ]);

        return redirect()->back()->with('success', 'Alasan banding berhasil diajukan.');
    }

    public function clear($id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $pengajuan = Pengajuan::where('nasabah_id', $nasabah->id)->first();
        $pengajuan->is_banding = 2;
        $pengajuan->save();

        return redirect()->route('marketing.data.pengajuan')->with('success', 'Pengajuan Selesai');
    }
}
