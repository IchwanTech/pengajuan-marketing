<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ListUserController extends Controller
{
    public function index()
    {
        $user = User::where('usertype', '!=', 'root')
            ->orderByRaw("FIELD(usertype, 'head', 'credit', 'supervisor', 'admin', 'marketing')")
            ->leftJoin('nasabahs', function ($join) {
                $join->on('users.id', '=', 'nasabahs.marketing_id')
                    ->whereNull('nasabahs.deleted_at'); // Tambahkan kondisi ini untuk memfilter nasabah yang belum dihapus
            })
            ->leftJoin('pengajuans', 'nasabahs.id', '=', 'pengajuans.nasabah_id')
            ->leftJoin('nasabah_luars', function ($join) {
                $join->on('users.id', '=', 'nasabah_luars.marketing_id')
                    ->whereNull('nasabah_luars.deleted_at'); // Tambahkan kondisi ini untuk memfilter nasabah yang belum dihapus
            })
            ->leftJoin('pengajuan_nasabah_luars', function ($join) {
                $join->on('nasabah_luars.id', '=', 'pengajuan_nasabah_luars.nasabah_id')
                    ->whereNull('pengajuan_nasabah_luars.deleted_at'); // Tambahkan kondisi ini untuk memfilter pengajuan nasabah luar yang belum dihapus
            })
            ->select(
                'users.id',
                'users.email',
                'users.usertype',
                'users.type',
                'users.name',
                'users.kode_marketing',
                'users.is_active',
                // DB::raw('COUNT(DISTINCT CASE WHEN nasabahs.id IS NOT NULL THEN nasabahs.id ELSE NULL END) as total_nasabah'),
                // DB::raw('COUNT(DISTINCT CASE WHEN users.type = "luar" THEN nasabah_luars.id ELSE NULL END) as total_nasabah_luar'),
                // DB::raw('SUM(CASE WHEN pengajuans.status IN ("aproved head", "approved banding head") THEN 1 ELSE 0 END) as total_approved_hm'),
                // DB::raw('SUM(CASE WHEN pengajuans.status IN ("rejected head", "rejected banding head") THEN 1 ELSE 0 END) as total_rejected_hm'),
                // Menghitung total nasabah biasa dengan DISTINCT
                DB::raw('COUNT(DISTINCT nasabahs.id) as total_nasabah'),
                // Menghitung total approve HM untuk nasabah biasa
                DB::raw('COALESCE(SUM(CASE WHEN pengajuans.status IN ("aproved head", "approved banding head") THEN 1 ELSE 0 END), 0) as total_approved_hm'),
                // Menghitung total reject HM untuk nasabah biasa
                DB::raw('COALESCE(SUM(CASE WHEN pengajuans.status IN ("rejected head", "rejected banding head") THEN 1 ELSE 0 END), 0) as total_rejected_hm'),
                // Menghitung total nasabah luar (hanya untuk informasi tambahan, jika diperlukan)
                DB::raw('COUNT(DISTINCT nasabah_luars.id) as total_nasabah_luar'),
                // Menghitung total approve ca untuk nasabah luar
                DB::raw('COALESCE(SUM(CASE WHEN pengajuan_nasabah_luars.status_pengajuan IN ("aproved ca") THEN 1 ELSE 0 END), 0) as total_approved_ca'),
                // Menghitung total reject ca untuk nasabah luar
                DB::raw('COALESCE(SUM(CASE WHEN pengajuan_nasabah_luars.status_pengajuan IN ("rejected ca") THEN 1 ELSE 0 END), 0) as total_rejected_ca')
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.usertype',
                'users.type',
                'users.kode_marketing',
                'users.is_active'
            )
            ->get();

        // dd($user);

        return view('superAdmin.list-user', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string',
            'usertype' => 'required|string',
            'type' => 'nullable|string',
            'kode_marketing' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->usertype = $request->usertype;
        $user->type = $request->type;
        $user->kode_marketing = $request->kode_marketing;
        $user->is_active = $request->is_active ?? true; // Set default to true if not provided
        $user->save();

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string',
            'usertype' => 'nullable|string',
            'type' => 'nullable|string',
            'kode_marketing' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->usertype = $request->usertype;
        $user->type = $request->type;
        $user->kode_marketing = $request->kode_marketing;
        $user->is_active = $request->is_active ?? true; // Set default to true if not provided

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('superAdmin.list.user')->with('success', 'User deleted successfully.');
    }
}
