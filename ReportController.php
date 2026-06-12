<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller {
    public function index() {
        $userId = Auth::id();
        $expiredItems = Item::where('user_id', $userId)
            ->where('expiry_date', '<', now())
            ->get();
        $totalLoss = $expiredItems->sum('price');
        $totalExpired = $expiredItems->count();
        return view('reports', compact('expiredItems', 'totalLoss', 'totalExpired'));
    }
}