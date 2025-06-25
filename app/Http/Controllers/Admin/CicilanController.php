<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CicilanImport;
use App\Models\DataCicilan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CicilanController extends Controller
{
    public function index()
    {
        $cicilans = DataCicilan::select(
            'id',
            'id_pinjam',
            'nama_konsumen',
            'pinjaman_ke',
            'tgl_pencairan',
            'pokok_pinjaman',
            'jumlah_tenor_seharusnya',
            'divisi',
            'cicilan_perbulan',
            'sisa_pinjaman',
            'sisa_tenor'
        )->get();

        return view('admin.cicilan', compact('cicilans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sisa_tenor' => 'required|numeric',
            'sisa_pinjaman' => 'required|numeric',
            'divisi' => 'required|string',
        ]);

        $cicilan = DataCicilan::findOrFail($id);
        $cicilan->update([
            'divisi' => $request->divisi,
            'sisa_tenor' => $request->sisa_tenor,
            'sisa_pinjaman' => $request->sisa_pinjaman,
        ]);

        return response()->json(['message' => 'Data cicilan berhasil diperbarui!']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CicilanImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data cicilan berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function truncate()
    {
        DataCicilan::truncate();
        return redirect()->back()->with('success', 'Data cicilan berhasil dihapus!');
    }

    public function bulkDeleteCicilan(Request $request)
    {
        $id = $request->id;

        if (empty($id)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 400);
        }

        $cicilans = DataCicilan::whereIn('id', $id)->get();

        foreach ($cicilans as $cicilan) {
            $cicilan->delete();
        }

        return response()->json(['message' => 'Data cicilan berhasil dihapus.']);
    }
}
