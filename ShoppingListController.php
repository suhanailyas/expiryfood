<?php
namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingListController extends Controller {
    public function index() {
        $items = ShoppingList::where('user_id', Auth::id())->latest()->get();
        return view('shopping', compact('items'));
    }

    public function store(Request $request) {
        $request->validate(['item_name' => 'required']);
        ShoppingList::create([
            'user_id'   => Auth::id(),
            'item_name' => $request->item_name,
        ]);
        return back()->with('success', 'Item added to shopping list!');
    }

    public function markPurchased($id) {
        $item = ShoppingList::findOrFail($id);
        $item->update(['purchased' => true]);
        return back()->with('success', 'Marked as purchased!');
    }
}