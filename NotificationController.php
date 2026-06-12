<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()->get();
        return view('notifications', compact('notifications'));
    }

    public function markRead($id) {
        Notification::where('id', $id)->update(['is_read' => true]);
        return back()->with('success', 'Marked as read!');
    }
}