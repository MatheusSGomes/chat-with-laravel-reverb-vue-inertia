<?php

use App\Http\Controllers\ProfileController;
use App\Models\ChatMessage;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $users = User::where('id', '!=', auth()->id())->get();

    return Inertia::render('Dashboard', [
        'users' => $users,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chat/{friend}', function (User $friend) {
    return Inertia::render('Chat', [
        'friend' => $friend,
        'user' => auth()->user()
    ]);
})->middleware(['auth', 'verified'])->name('chat');;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/messages/{friend}', function (User $friend) {
    return ChatMessage::query()
        ->where(function ($query) use ($friend) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $friend->id);
        })
        ->orWhere(function ($query) use ($friend) {
            $query->where('sender_id', $friend->id)
                ->where('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver'])
        ->orderBy('id', 'asc')
        ->get();
})->middleware(['auth'])->name('chat');;

Route::post('/messages/{friend}', function (User $friend) {
    return ChatMessage::create([
        'receiver_id' => $friend->id,
        'sender_id' => auth()->id(),
        'text' => request()->input('message'),
    ]);
});

require __DIR__.'/auth.php';
