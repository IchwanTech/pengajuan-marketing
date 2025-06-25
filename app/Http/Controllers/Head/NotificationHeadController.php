<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\NotifactionHeadMarketing;
use Illuminate\Http\Request;

class NotificationHeadController extends Controller
{
    public function index()
    {
        $notif = NotifactionHeadMarketing::orderBy('created_at', 'desc')
            ->get();
        return view('headMarketing.notification-hm', compact('notif'));
    }

    public function read($id)
    {
        $notification = NotifactionHeadMarketing::findOrFail($id);

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
        NotifactionHeadMarketing::where('read', false)->update(['read' => true]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
