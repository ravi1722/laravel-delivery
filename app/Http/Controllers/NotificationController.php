<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);
        // Mark all as read when viewing
        // Auth::user()->unreadNotifications->markAsRead();

        return view('customer.notifications', compact('notifications'));
    }
    public function unread()
    {
        return response()->json([
            'count'         => Auth::user()->unreadNotifications()->count(),
            'notifications' => Auth::user()
                ->unreadNotifications()
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($n) => [
                    'id'      => $n->id,
                    'title'   => $n->data['title'],
                    'message' => $n->data['message'],
                    'icon'    => $n->data['icon'] ?? 'bi-bell',
                    'color'   => $n->data['color'] ?? 'primary',
                    'url'     => $n->data['url'] ?? '#',
                    'time'    => $n->created_at->diffForHumans(),
                ]),
        ]);
    }
    public function markAsRead(string $id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        return response()->json(['success' => true]);
    }
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
    public function destroy(string $id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
