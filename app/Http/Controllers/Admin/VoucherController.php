<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\VouchersImport;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.voucher', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'kode_voucher' => 'required|string|max:255',
            'kadaluarsa' => 'required|date',
            'type' => 'required|in:Doorprize,Produksi',
            'saldo' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $voucher = new Voucher();
        $voucher->nama = $request->nama;
        $voucher->nik = $request->nik;
        $voucher->kode_voucher = $request->kode_voucher;
        $voucher->kadaluarsa = $request->kadaluarsa;
        $voucher->type = $request->type;
        $voucher->saldo = $request->type === 'Produksi' ? $request->saldo : null;

        if ($request->hasFile('voucher')) {
            $namaVoucher = str_replace(' ', '-', strtolower($voucher->nama));
            $fileVoucher = $request->file('voucher');
            $fileName = $namaVoucher . '-' . $voucher->id . '.' . $fileVoucher->getClientOriginalExtension();
            $path = $request->file('voucher')->storeAs('vouchers', $fileName, 'public');
            $voucher->voucher = $fileName;
        }

        $voucher->save();

        return redirect()->route('admin.voucher')->with('success', 'Data voucher berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'kode_voucher' => 'required|string|max:255' . $id,
            'kadaluarsa' => 'required|date',
            'type' => 'required|in:Doorprize,Produksi',
            'saldo' => 'nullable|numeric|min:0',
            'voucher' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $voucher->nama = $request->nama;
        $voucher->nik = $request->nik;
        $voucher->kode_voucher = $request->kode_voucher;
        $voucher->kadaluarsa = $request->kadaluarsa;
        $voucher->type = $request->type;
        $voucher->saldo = $request->type === 'Produksi' ? $request->saldo : null;

        if ($request->hasFile('voucher')) {
            if ($voucher->voucher) {
                Storage::disk('public')->delete('vouchers/' . $voucher->voucher);
            }

            $namaVoucher = str_replace(' ', '-', strtolower($voucher->nama));
            $fileVoucher = $request->file('voucher');
            $fileName = $namaVoucher . '-' . $voucher->id . '.' . $fileVoucher->getClientOriginalExtension();
            $fileVoucher->storeAs('vouchers', $fileName, 'public');
            $voucher->voucher = $fileName;
        }

        $voucher->save();

        return response()->json([
            'id' => $voucher->id,
            'nama' => $voucher->nama,
            'nik' => $voucher->nik,
            'kode_voucher' => $voucher->kode_voucher,
            'kadaluarsa' => $voucher->kadaluarsa, // Pastikan format date
            'type' => $voucher->type,
            'is_claim' => $voucher->is_claim, // Tambahkan ini
            'voucher_url' => $voucher->voucher ? asset('storage/vouchers/' . $voucher->voucher) : null,
            'saldo' => $voucher->saldo // Tambahkan jika diperlukan
        ]);
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        if ($voucher->voucher) {
            Storage::disk('public')->delete('vouchers/' . $voucher->voucher);
        }

        $voucher->delete();

        return redirect()->route('admin.voucher')->with('success', 'Voucher berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new VouchersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data voucher berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'voucher' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $voucher = Voucher::findOrFail($id);
        $namaVoucher = str_replace(' ', '-', strtolower($voucher->nama));

        if ($request->hasFile('voucher')) {
            // Hapus voucher lama jika ada
            if ($voucher->voucher) {
                Storage::disk('public')->delete('vouchers/' . $voucher->voucher);
            }

            // Upload voucher baru
            $fileVoucher = $request->file('voucher');
            $fileName = $namaVoucher . '-' . $voucher->id . '.' . $fileVoucher->getClientOriginalExtension();
            $fileVoucher->storeAs('vouchers', $fileName, 'public');

            // Update database
            $voucher->voucher = $fileName;
            $voucher->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diupload!',
            'voucher_url' => asset('storage/vouchers/' . $voucher->voucher),
            'type' => $voucher->type,
            'is_claim' => $voucher->is_claim,
            'id' => $voucher->id
        ]);
    }

    public function claim($id)
    {
        $voucher = Voucher::findOrFail($id);

        if ($voucher->type !== 'Produksi') {
            return redirect()->back()->with('error', 'Voucher ini tidak dapat diklaim.');
        }

        if ($voucher->is_claim) {
            return redirect()->back()->with('info', 'Voucher ini sudah diklaim.');
        }

        $voucher->is_claim = 1;
        $voucher->save();

        return redirect()->back()->with('success', 'Voucher berhasil diklaim!');
    }

    public function bulkDeleteExpired(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada voucher yang dipilih.'], 400);
        }

        $vouchers = Voucher::whereIn('id', $ids)->get();

        foreach ($vouchers as $voucher) {
            // Hapus file dari storage jika ada
            if ($voucher->voucher && Storage::disk('public')->exists('vouchers/' . $voucher->voucher)) {
                Storage::disk('public')->delete('vouchers/' . $voucher->voucher);
            }

            // Hapus data dari database
            $voucher->delete();
        }

        return response()->json(['message' => 'Voucher kadaluarsa berhasil dihapus.']);
    }
}
