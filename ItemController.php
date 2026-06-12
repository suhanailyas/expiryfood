<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller {

    public function index(Request $request) {
        $query = Item::where('user_id', Auth::id())->with('category');
        if ($request->search)
            $query->where('name', 'like', '%'.$request->search.'%');
        if ($request->category)
            $query->where('category_id', $request->category);
        if ($request->status) {
            $today = now()->toDateString();
            if ($request->status == 'fresh')
                $query->where('expiry_date', '>', now()->addDays(7));
            elseif ($request->status == 'expiring')
                $query->whereBetween('expiry_date', [now(), now()->addDays(7)]);
            elseif ($request->status == 'expired')
                $query->where('expiry_date', '<', $today);
        }
        $items = $query->latest()->paginate(10);
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'quantity'    => 'required|integer',
            'expiry_date' => 'required|date',
            'price'       => 'nullable|numeric',
            'image'       => 'nullable|image|max:2048',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }
        Item::create($data);
        return redirect()->route('items.index')->with('success', 'Item added!');
    }

    public function edit(Item $item) {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item) {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'quantity'    => 'required|integer',
            'expiry_date' => 'required|date',
        ]);
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }
        $item->update($data);
        return redirect()->route('items.index')->with('success', 'Item updated!');
    }

    public function destroy(Item $item) {
        $item->delete();
        return back()->with('success', 'Item deleted!');
    }
}