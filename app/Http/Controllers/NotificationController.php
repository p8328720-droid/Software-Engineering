<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    public function markAllAsRead()
    {
        Auth::user()->markAllNotificationsAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    public function getUnreadCount()
    {
        return response()->json(['count' => Auth::user()->unread_notifications_count]);
    }

    public function getLatest()
    {
        $notifications = Auth::user()->notifications()->latest()->limit(10)->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unread_notifications_count,
        ]);
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus');
    }
}
