<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Luar\NotificationSPV;
use App\Models\NotifactionSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSpvController extends Controller
{
    public function index()
    {
        $spvType = Auth::user()->type;

        $notif = [];

        if ($spvType == 'dalam') {
            $notif = NotifactionSupervisor::orderBy('created_at', 'desc')
                ->get();
        } elseif ($spvType == 'luar') {
            $notif = NotificationSPV::orderBy('created_at', 'desc')
                ->get();
        }

        return view('supervisor.notification-spv', compact('notif'));
    }

    public function read($id)
    {
        $notification = NotifactionSupervisor::findOrFail($id);

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
        NotifactionSupervisor::where('read', false)->update(['read' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
