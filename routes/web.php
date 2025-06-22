<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ChallengeController;


Route::get('/', function () {
    $user = auth()->user();
    if ($user) {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return view('auth.login');
});


Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':admin'])->name('admin.dashboard');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('user.dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attendance', [AttendanceController::class, 'showAttendanceForm'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('attendance.form');
    Route::post('/attendance', [AttendanceController::class, 'submit'])->name('attendance.submit');

    Route::get('user/leaderboard', [AttendanceController::class, 'leaderboard'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('leaderboard');
    Route::get('user/challenges', [ChallengeController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('challenges');
    Route::post('user/challenges/{challenge}/submit', [ChallengeController::class, 'submit'])->name('challenges.submit');
    Route::get('user/shop', [ShopController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('shop');
    Route::post('user/shop/buy/{item}', [ShopController::class, 'buyItem'])->name('items.buy');
    Route::post('user/shop/freeze/{item}', [ShopController::class, 'freezeStreak'])->name('streak.freeze');

    Route::post('/streak/claim/{days}', [UserDashboardController::class, 'claimReward'])->name('streak.claim');

    Route::post('/admin/challenges/{userChallenge}/approve', [AdminDashboardController::class, 'approve_challenge'])
    ->name('admin.challenges.approve')
    ->middleware(['auth', 'verified', EnsureUserHasRole::class.':admin']);
    
});


require __DIR__.'/auth.php';
