<?php

namespace App\Http\Controllers\Luar\CreditAnalyst;

use App\Http\Controllers\Controller;
use App\Models\Luar\ApprovalLuar;
use App\Models\Luar\NasabahLuar;
use App\Models\Luar\NotificationSurveyor;
use App\Models\Luar\PengajuanNasabahLuar;
use App\Models\NotifactionHeadMarketing;
use App\Models\NotifactionMarketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalLuarController extends Controller
{
    public function index()
    {
        $pengajuan = NasabahLuar::whereHas('user', function ($query) {
            $query->where('usertype', 'marketing');
        })
            ->whereHas('pengajuanLuar', function ($query) {
                $query->whereIn('status_pengajuan', ['checked by spv', 'banding', 'revisi', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai']);
            })
            ->with([
                'user',
                'alamatLuar',
                'pekerjaanLuar',
                'penjaminLuar',
                'tanggunganLuar',
                'pinjamanLainLuar',
                'kontakDarurat',
                'pengajuanLuar' => function ($query) {
                    $query->whereIn('status_pengajuan', ['checked by spv', 'banding', 'revisi', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai']);
                }
            ])
            ->withCount('pengajuanLuar as total_pengajuan')
            ->get();

        return view('pengajuan-luar.ca-luar.approval-pengajuan-luar', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat')
            ->findOrFail($id);
        // dd($pengajuan);
        return view('pengajuan-luar.ca-luar.detail-pengajuan-luar', compact('pengajuan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'validasi_nasabah' => 'required|boolean',
            'catatan_nasabah' => 'nullable|string',
            'validasi_alamat' => 'required|boolean',
            'catatan_alamat' => 'nullable|string',
            'validasi_pekerjaan' => 'required|boolean',
            'catatan_pekerjaan' => 'nullable|string',
            'validasi_penjamin' => 'required|boolean',
            'catatan_penjamin' => 'nullable|string',
            'validasi_tanggungan' => 'required|boolean',
            'catatan_tanggungan' => 'nullable|string',
            'validasi_kontak_darurat' => 'required|boolean',
            'catatan_kontak_darurat' => 'nullable|string',
            'validasi_pengajuan' => 'required|boolean',
            'catatan_pengajuan' => 'nullable|string',
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

        // dd($request->all(), $isRevisi);

        $pengajuan->nasabahLuar->validasi_nasabah = $request->validasi_nasabah;
        $pengajuan->nasabahLuar->catatan = $request->catatan_nasabah;
        $pengajuan->nasabahLuar->save();

        $pengajuan->nasabahLuar->alamatLuar->validasi_alamat = $request->validasi_alamat;
        $pengajuan->nasabahLuar->alamatLuar->catatan = $request->catatan_alamat;
        $pengajuan->nasabahLuar->alamatLuar->save();

        $pengajuan->nasabahLuar->pekerjaanLuar->validasi_pekerjaan = $request->validasi_pekerjaan;
        $pengajuan->nasabahLuar->pekerjaanLuar->catatan = $request->catatan_pekerjaan;
        $pengajuan->nasabahLuar->pekerjaanLuar->save();

        $pengajuan->nasabahLuar->penjaminLuar->validasi_penjamin = $request->validasi_penjamin;
        $pengajuan->nasabahLuar->penjaminLuar->catatan = $request->catatan_penjamin;
        $pengajuan->nasabahLuar->penjaminLuar->save();

        $pengajuan->nasabahLuar->tanggunganLuar->validasi_tanggungan = $request->validasi_tanggungan;
        $pengajuan->nasabahLuar->tanggunganLuar->catatan = $request->catatan_tanggungan;
        $pengajuan->nasabahLuar->tanggunganLuar->save();

        $pengajuan->nasabahLuar->kontakDarurat->validasi_kontak_darurat = $request->validasi_kontak_darurat;
        $pengajuan->nasabahLuar->kontakDarurat->catatan = $request->catatan_kontak_darurat;
        $pengajuan->nasabahLuar->kontakDarurat->Save();

        // $pengajuan = $nasabah->pengajuanLuar->sortByDesc('created_at')->first(); // Pilih pengajuan terbaru
        // dd($pengajuan);

        if ($pengajuan) {
            $pengajuan->validasi_pengajuan = $request->validasi_pengajuan;
            $pengajuan->catatan = $request->catatan_pengajuan;

            if ($isRevisi) {
                $pengajuan->status_pengajuan = 'revisi';
            } else {
                $pengajuan->status_pengajuan = 'checked by spv';
            }

            $pengajuan->save();
        }

        if ($isRevisi) {
            NotifactionMarketing::create([
                'pengajuan_id' => null,
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' sudah diverifikasi oleh Credit Analyst, dan perlu untuk diperbaiki oleh Marketing.',
                'read' => false
            ]);
            return redirect()->route('creditAnalystLuar.pengajuan')->with('success', 'Verifikasi berhasil, menunggu perbaikan oleh marketing.');
        } else {
            return redirect()->route('creditAnalystLuar.survey.form', $pengajuan->id);
        }
    }

    public function surveyForm($id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        return view('pengajuan-luar.ca-luar.form-survey', compact('pengajuan'));
    }

    public function survey(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'is_survey' => 'required|boolean',
        ]);

        // Loop melalui setiap pengajuan dan perbarui statusnya
        $pengajuan->status_pengajuan = $request->is_survey == 1 ? 'perlu survey' : 'tidak perlu survey';
        $pengajuan->save();

        if ($request->is_survey == 1) {
            NotifactionMarketing::create([
                'pengajuan_id' => null,
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan anda untuk nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' sudah diverifikasi oleh Credit Analyst, dan perlu untuk disurvey. Saat ini petugas survey sudah ditugaskan.',
                'read' => false
            ]);

            NotificationSurveyor::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Kami informasikan bahwa Anda memiliki tugas baru untuk melakukan survei terhadap nasabah atas nama ' . $pengajuan->nasabahLuar->nama_lengkap . '. Mohon segera melakukan survei.',
                'read' => false
            ]);
        }

        if ($request->is_survey == 1) {
            return redirect()->route('creditAnalystLuar.pengajuan')->with('success', 'Petugas Survey sudah ditugaskan');
        } else {
            return redirect()->route('creditAnalystLuar.approval.form', $pengajuan->id);
        }
    }

    public function approvalForm($id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        return view('pengajuan-luar.ca-luar.form-approval', compact('pengajuan'));
    }


    public function approval(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'nominal_pinjaman' => 'nullable|numeric',
            'tenor' => 'nullable|numeric',
            'analisa' => 'required',
            'catatan_approve' => 'nullable|string',
            'catatan_reject' => 'nullable|string',
            'is_approve' => 'required|boolean',
        ]);

        // dd($nasabah, $request->all());

        // Ambil koleksi pengajuanLuar
        // $pengajuan = $nasabah->pengajuanLuar;

        if ($request->is_approve == 1) {
            $pengajuan->status_pengajuan = 'aproved ca';
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'ca',
                'analisa' => $request->analisa,
                'nominal_pinjaman' => $request->nominal_pinjaman,
                'tenor' => $request->tenor,
                'catatan' => $request->catatan_approve,
                'status' => 'approved'
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan dari nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Credit Analyst dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' Bulan , dengan keterangan: ' . $request->catatan_approve,
                'read' => false
            ]);
        } else {
            $pengajuan->status_pengajuan = 'rejected ca';
            $pengajuan->save();
            // $pengajuan->catatan_reject = $request->catatan_reject;

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'ca',
                'analisa' => $request->analisa,
                'pengajuan_id' => $pengajuan->id,
                'catatan' => $request->catatan_reject,
                'status' => 'rejected'
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan dari nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Credit Analyst dengan keterangan: ' . $request->catatan_reject,
                'read' => false
            ]);
        }
        // $pengajuan->save();

        $message = $request->is_approve == 1
            ? 'Pengajuan berhasil di Approve'
            : 'Pengajuan berhasil di Reject';

        return redirect()->route('creditAnalystLuar.pengajuan')->with('success', $message);
    }

    public function detailHasilSurvey($id)
    {
        $nasabah = PengajuanNasabahLuar::with([
            'hasilSurvey',
            'hasilSurvey.fotoHasilSurvey'
        ])
            ->findOrFail($id);
        return view('pengajuan-luar.ca-luar.detail-hasil-survey', compact('nasabah'));
    }

    public function showBanding($id)
    {
        $pengajuan = PengajuanNasabahLuar::with('nasabahLuar.alamatLuar', 'nasabahLuar.pekerjaanLuar', 'nasabahLuar.penjaminLuar', 'nasabahLuar.tanggunganLuar', 'nasabahLuar.pinjamanLainLuar', 'nasabahLuar.kontakDarurat', 'hasilSurvey')->findOrFail($id);
        $isSurvey = $pengajuan->whereHas('hasilSurvey', function ($query) use ($pengajuan) {
            $query->where('pengajuan_id', $pengajuan->first()->id);
        })
            ->exists();

        // dd($pengajuan->approval()->where('role', 'hm')->first());

        return view('pengajuan-luar.ca-luar.detail-banding-luar', compact('pengajuan', 'isSurvey'));
    }

    public function approvalBanding(Request $request, $id)
    {
        $pengajuan = PengajuanNasabahLuar::findOrFail($id);

        $request->validate([
            'nominal_pinjaman' => 'nullable|numeric',
            'tenor' => 'nullable|numeric',
            'analisa' => 'required',
            'catatan_approve' => 'nullable|string',
            'catatan_reject' => 'nullable|string',
            'is_approve' => 'required|boolean',
        ]);

        // dd($nasabah, $request->all());

        // Ambil koleksi pengajuanLuar
        // $pengajuan = $nasabah->pengajuanLuar;

        if ($request->is_approve == 1) {
            $pengajuan->status_pengajuan = 'approved banding ca';
            $pengajuan->nominal_pinjaman = $request->nominal_pinjaman;
            $pengajuan->tenor = $request->tenor;
            $pengajuan->save();

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'ca',
                'analisa' => $request->analisa,
                'nominal_pinjaman' => $request->nominal_pinjaman,
                'tenor' => $request->tenor,
                'catatan' => $request->catatan_approve,
                'status' => 'approved',
                'is_banding' => 1
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Approve oleh Credit Analyst dengan nominal pinjaman Rp. ' . number_format($request->nominal_pinjaman, 0, ',', '.') . ' dan tenor ' . $request->tenor . ' Bulan , dengan keterangan: ' . $request->catatan_approve,
                'read' => false
            ]);
        } else {
            $pengajuan->status_pengajuan = 'rejected banding ca';
            $pengajuan->save();
            // $pengajuan->catatan_reject = $request->catatan_reject;

            ApprovalLuar::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::user()->id,
                'role' => 'ca',
                'analisa' => $request->analisa,
                'pengajuan_id' => $pengajuan->id,
                'catatan' => $request->catatan_reject,
                'status' => 'rejected',
                'is_banding' => 1
            ]);

            NotifactionHeadMarketing::create([
                'pengajuan_luar_id' => $pengajuan->id,
                'pesan' => 'Pengajuan banding nasabah ' . $pengajuan->nasabahLuar->nama_lengkap . ' telah di Reject oleh Credit Analyst dengan keterangan: ' . $request->catatan_reject,
                'read' => false
            ]);
        }
        // $pengajuan->save();

        $message = $request->is_approve == 1
            ? 'Pengajuan berhasil di Approve'
            : 'Pengajuan berhasil di Reject';

        return redirect()->route('creditAnalystLuar.pengajuan')->with('success', $message);
    }
}
