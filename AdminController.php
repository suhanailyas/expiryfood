<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index() {
        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalExpired = Item::where('expiry_date', '<', now())->count();
        return view('admin.dashboard', compact('totalUsers', 'totalItems', 'totalExpired'));
    }

    public function users() {
        $users = User::with('role')->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user) {
        $user->delete();
        return back()->with('success', 'User deleted!');
    }
}