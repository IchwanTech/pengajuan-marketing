<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\NotifactionMarketing;
use App\Models\NotifactionSupervisor;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationMarketingController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        $notif = NotifactionMarketing::with(['pengajuan.nasabah', 'pengajuanLuar.nasabahLuar'])
            ->where(function ($query) use ($user) {
                // Jika marketing_id berasal dari tabel pengajuans
                $query->whereHas('pengajuan.nasabah', function ($subQuery) use ($user) {
                    $subQuery->where('marketing_id', $user);
                });
            })
            ->orWhere(function ($query) use ($user) {
                // Jika marketing_id berasal dari tabel pengajuan_nasabah_luars
                $query->whereHas('pengajuanLuar.nasabahLuar', function ($subQuery) use ($user) {
                    $subQuery->where('marketing_id', $user);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $unRead = $notif->where('read', false)->count();

        return view('marketing.notification-marketing', compact('notif', 'unRead'));
    }

    public function read($id)
    {
        $notification = NotifactionMarketing::findOrFail($id);

        // Update kolom read jika belum dibaca
        if (!$notification->read) {
            $notification->read = true;
            $notification->save();
        }

        return redirect()->back()
            ->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    public function readAll()
    {
        NotifactionMarketing::where('read', false)->update(['read' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
