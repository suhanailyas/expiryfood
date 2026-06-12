<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\NotificationController;

// Auth Routes
Route::get('/',           [AuthController::class, 'showLogin'])->name('login');
Route::get('/register',   [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',  [AuthController::class, 'register']);
Route::post('/login',     [AuthController::class, 'login']);
Route::post('/logout',    [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
    $userId = auth()->id();
    $fresh    = \App\Models\Item::where('user_id', $userId)->where('expiry_date', '>', now()->addDays(7))->count();
    $expiring = \App\Models\Item::where('user_id', $userId)->whereBetween('expiry_date', [now(), now()->addDays(7)])->count();
    $expired  = \App\Models\Item::where('user_id', $userId)->where('expiry_date', '<', now())->count();
    $total    = \App\Models\Item::where('user_id', $userId)->count();
    return view('dashboard', compact('fresh', 'expiring', 'expired', 'total'));
})->name('dashboard');

    Route::resource('items', ItemController::class);
    Route::get('/reports',        [ReportController::class, 'index'])->name('reports');
    Route::get('/shopping-list',  [ShoppingListController::class, 'index'])->name('shopping');
    Route::post('/shopping-list', [ShoppingListController::class, 'store'])->name('shopping.store');
    Route::post('/shopping-list/{id}/purchased', [ShoppingListController::class, 'markPurchased'])->name('shopping.purchased');
    Route::get('/family',         [FamilyController::class, 'index'])->name('family');
    Route::post('/family/invite', [FamilyController::class, 'invite'])->name('family.invite');
    Route::get('/notifications',  [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/',       [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users',  [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});