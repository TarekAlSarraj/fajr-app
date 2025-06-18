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
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':admin'])->name('admin.dashboard');
    Route::get('user/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('user.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('user/mark_attendance', [AttendanceController::class, 'showAttendanceForm'])->middleware(['auth', 'verified', EnsureUserHasRole::class.':user'])->name('attendance.form');
    Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.submit');

    Route::middleware('auth')->get('/attendance/qr/{event}', [AttendanceController::class, 'markAttendanceViaQR'])
    ->name('attendance.qr');

    Route::get('user/leaderboard', [AttendanceController::class, 'leaderboard'])->name('leaderboard');
    Route::get('user/challenges', [ChallengeController::class, 'index'])->name('challenges');
    Route::post('user/challenges/{challenge}/submit', [ChallengeController::class, 'submit'])->name('challenges.submit');
    Route::get('user/shop', [ShopController::class, 'index'])->name('shop');
    Route::post('user/shop/buy/{item}', [ShopController::class, 'buyItem'])->name('items.buy');
    Route::post('user/shop/freeze/{item}', [ShopController::class, 'freezeStreak'])->name('streak.freeze');

});


require __DIR__.'/auth.php';
