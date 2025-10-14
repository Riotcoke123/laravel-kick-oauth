use App\Http\Controllers\KickAuthController;

Route::get('/oauth/kick/redirect', [KickAuthController::class, 'redirect'])->name('kick.redirect');
Route::get('/oauth/kick/callback', [KickAuthController::class, 'callback'])->name('kick.callback');
