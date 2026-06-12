<?php
namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller {
    public function index() {
        $family = Family::where('owner_id', Auth::id())->with('members')->first();
        return view('family', compact('family'));
    }

    public function invite(Request $request) {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found!']);
        }
        $family = Family::firstOrCreate(
            ['owner_id' => Auth::id()],
            ['name'     => Auth::user()->name . "'s Family"]
        );
        FamilyMember::firstOrCreate([
            'family_id' => $family->id,
            'user_id'   => $user->id,
        ]);
        return back()->with('success', 'Member invited!');
    }
}