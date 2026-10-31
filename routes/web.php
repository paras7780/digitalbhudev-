<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInstagramController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BhudevDashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PanditWebsiteController;
use App\Http\Controllers\PublicBhudevController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::get('/language/{locale}', [LocalizationController::class, 'switchLocale'])->name('locale.switch');
Route::get('/lang/{locale}', [LocalizationController::class, 'switchLocale']);
Route::get('/bhudevs',[PublicBhudevController::class,'index'])->name('bhudevs.index');
Route::get('/bhudevs/{slug}',[PublicBhudevController::class,'show'])->name('bhudevs.show');
Route::view('/services','pages.services')->name('services.index');
Route::get('/panchang',[ContentController::class,'panchang'])->name('panchang.index');
Route::get('/rashifal',[ContentController::class,'rashifal'])->name('rashifal.index');
Route::get('/samagri',[ContentController::class,'samagri'])->name('samagri.index');
Route::get('/samagri/card/{card}',[ContentController::class,'showSamagriCard'])->name('samagri.card.show');
Route::get('/samagri/{slug}',[ContentController::class,'samagri'])->name('samagri.show');
Route::get('/muhurat',[ContentController::class,'muhurat'])->name('muhurat.index');
Route::view('/about','pages.about')->name('about');
Route::view('/contact','pages.contact')->name('contact');
Route::post('/contact',[ContactController::class,'store'])->middleware('throttle:5,1')->name('contact.store');
Route::post('/booking',[ContactController::class,'booking'])->middleware('throttle:5,1')->name('booking.store');
Route::view('/privacy','pages.privacy')->name('privacy');
Route::view('/terms','pages.terms')->name('terms');
Route::post('/pandit-inquiry',[PanditWebsiteController::class,'handleInquiry'])->name('pandit.inquiry.handle');

Route::middleware('guest')->group(function(){
 Route::get('/login',[AuthController::class,'showLogin'])->name('login');
 Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('login.store');
 Route::get('/register',fn()=>view('pages.register'))->name('register');
 Route::post('/register',[AuthController::class,'register'])->middleware('throttle:3,1')->name('register.store');
 Route::get('/forgot-password',[AuthController::class,'forgotForm'])->name('password.request');
 Route::post('/forgot-password',[AuthController::class,'forgot'])->middleware('throttle:3,1')->name('password.email');
 Route::get('/reset-password/{token}',[AuthController::class,'resetForm'])->name('password.reset');
 Route::post('/reset-password',[AuthController::class,'reset'])->middleware('throttle:3,1')->name('password.update');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function(){
 Route::get('/email/verify',fn()=>view('auth.verify-email'))->name('verification.notice');
 Route::post('/email/verification-notification',function(Request $request){
     $request->user()->sendEmailVerificationNotification();
     return back()->with('status','A fresh verification link has been sent to your email address.');
 })->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}',function(Request $request, $id, $hash){
    $user = \App\Models\User::findOrFail($id);
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid email verification link.');
    }
    if (! $request->hasValidSignature()) {
        abort(403, 'The verification link has expired. Please log in and request a new one.');
    }
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }
    if (! \Illuminate\Support\Facades\Auth::check()) {
        \Illuminate\Support\Facades\Auth::login($user);
    }
    $request->session()->regenerate();
    $target = $user->isAdmin() ? 'admin.dashboard' : 'bhudev.dashboard';
    return redirect()->route($target)->with('status','Your email has been verified successfully!');
})->middleware(['signed','throttle:6,1'])->name('verification.verify');
Route::prefix('admin')->name('admin.')->middleware(['auth','verified','role:admin'])->group(function(){
 Route::get('/',[AdminController::class,'dashboard'])->name('dashboard');
 Route::get('/bhudevs',[AdminController::class,'bhudevs'])->name('bhudevs');
 Route::get('/bhudevs/create',[AdminController::class,'createBhudevForm'])->name('bhudevs.create');
 Route::post('/bhudevs',[AdminController::class,'createBhudev'])->name('bhudevs.store');
 Route::get('/bhudevs/{profile}',[AdminController::class,'showBhudev'])->name('bhudevs.show');
 Route::get('/bhudevs/{profile}/edit',[AdminController::class,'editBhudev'])->name('bhudevs.edit');
 Route::put('/bhudevs/{profile}',[AdminController::class,'updateBhudev'])->name('bhudevs.update');
 Route::delete('/bhudevs/{profile}',[AdminController::class,'deleteBhudev'])->name('bhudevs.delete');
 Route::patch('/bhudevs/{profile}/approve',[AdminController::class,'approveBhudev'])->name('bhudevs.approve');
 Route::patch('/bhudevs/{profile}/reject',[AdminController::class,'rejectBhudev'])->name('bhudevs.reject');
 Route::patch('/bhudevs/{profile}/suspend',[AdminController::class,'suspendBhudev'])->name('bhudevs.suspend');
 Route::patch('/bhudevs/{profile}/reactivate',[AdminController::class,'reactivateBhudev'])->name('bhudevs.reactivate');
 Route::patch('/bhudevs/{profile}/toggle-visibility',[AdminController::class,'toggleVisibility'])->name('bhudevs.toggle-visibility');
 Route::patch('/bhudevs/{profile}/status',[AdminController::class,'setStatus'])->name('bhudevs.status');
 Route::post('/revisions/{revision}/review',[AdminController::class,'reviewRevision'])->name('revisions.review');
 Route::get('/rashifal',[AdminController::class,'rashifals'])->name('rashifals');
 Route::post('/rashifal',[AdminController::class,'saveRashifal'])->name('rashifals.save');
 Route::delete('/rashifal/{rashifal}',[AdminController::class,'deleteRashifal'])->name('rashifals.delete');
 Route::get('/panchang',[AdminController::class,'panchang'])->name('panchang');
 Route::post('/panchang',[AdminController::class,'savePanchang'])->name('panchang.save');
 Route::delete('/panchang/{entry}',[AdminController::class,'deletePanchang'])->name('panchang.delete');
 Route::post('/astro/sync',[AdminController::class,'syncAstro'])->name('astro.sync');
 Route::get('/inquiries',[AdminController::class,'inquiries'])->name('inquiries');
 Route::patch('/inquiries/{inquiry}',[AdminController::class,'updateInquiry'])->name('inquiries.update');
 Route::get('/domains',[AdminController::class,'domains'])->name('domains.index');
 Route::post('/domains',[AdminController::class,'assignDomain'])->name('domains.store');
 Route::patch('/domains/{domain}/status',[AdminController::class,'updateDomainStatus'])->name('domains.update-status');
 Route::put('/domains/{domain}',[AdminController::class,'updateDomainDetails'])->name('domains.update');
 Route::delete('/domains/{domain}',[AdminController::class,'deleteDomain'])->name('domains.delete');
 Route::get('/audit-logs',[AdminController::class,'auditLogs'])->name('audit-logs');
 // Instagram Integration routes
 Route::get('/instagram', [AdminInstagramController::class, 'index'])->name('instagram.index');
 Route::get('/bhudevs/{profile}/instagram', [AdminInstagramController::class, 'show'])->name('instagram.show');
 Route::post('/bhudevs/{profile}/instagram/toggle', [AdminInstagramController::class, 'toggle'])->name('instagram.toggle');
 Route::post('/bhudevs/{profile}/instagram/urls', [AdminInstagramController::class, 'storeUrl'])->name('instagram.urls.store');
 Route::delete('/bhudevs/{profile}/instagram/urls/{post}', [AdminInstagramController::class, 'destroyUrl'])->name('instagram.urls.destroy');
 Route::post('/bhudevs/{profile}/instagram/connect-api', [AdminInstagramController::class, 'connectApi'])->name('instagram.connect-api');
 Route::post('/bhudevs/{profile}/instagram/disconnect', [AdminInstagramController::class, 'disconnect'])->name('instagram.disconnect');
 Route::post('/bhudevs/{profile}/instagram/request-website', [AdminInstagramController::class, 'requestWebsite'])->name('instagram.request-website');
 Route::post('/bhudevs/{profile}/instagram/upload-video', [AdminInstagramController::class, 'uploadVideo'])->name('instagram.upload-video');
 Route::post('/bhudevs/{profile}/instagram/autoplay', [AdminInstagramController::class, 'toggleAutoplay'])->name('instagram.autoplay');
});
Route::prefix('bhudev')->name('bhudev.')->middleware(['auth','verified','role:bhudev'])->group(function(){
 Route::get('/',[BhudevDashboardController::class,'dashboard'])->name('dashboard');
 Route::get('/website',[BhudevDashboardController::class,'websiteSettings'])->name('website');
 Route::post('/website/request',[BhudevDashboardController::class,'submitDomainRequest'])->name('website.request');
 Route::post('/website/content',[BhudevDashboardController::class,'updateWebsiteContent'])->name('website.content');
 Route::get('/instagram',[BhudevDashboardController::class,'instagram'])->name('instagram');
 Route::post('/instagram/toggle',[BhudevDashboardController::class,'toggleInstagram'])->name('instagram.toggle');
 Route::post('/instagram/autoplay',[BhudevDashboardController::class,'toggleAutoplay'])->name('instagram.autoplay');
 Route::post('/instagram/upload-video',[BhudevDashboardController::class,'uploadInstagramVideo'])->name('instagram.upload-video');
 Route::post('/instagram/urls',[BhudevDashboardController::class,'storeInstagramUrl'])->name('instagram.urls.store');
 Route::delete('/instagram/urls/{post}',[BhudevDashboardController::class,'destroyInstagramUrl'])->name('instagram.urls.destroy');
 Route::post('/instagram/connect-api',[BhudevDashboardController::class,'connectInstagramApi'])->name('instagram.connect-api');
 Route::post('/instagram/disconnect',[BhudevDashboardController::class,'disconnectInstagram'])->name('instagram.disconnect');
 Route::post('/instagram/request-website',[BhudevDashboardController::class,'requestInstagramWebsite'])->name('instagram.request-website');
 Route::get('/profile/edit',[BhudevDashboardController::class,'edit'])->name('profile.edit');
 Route::put('/profile',[BhudevDashboardController::class,'update'])->name('profile.update');
 Route::get('/gallery',[BhudevDashboardController::class,'gallery'])->name('gallery');
 Route::post('/gallery',[BhudevDashboardController::class,'uploadGallery'])->name('gallery.upload');
 Route::delete('/gallery/{image}',[BhudevDashboardController::class,'deleteImage'])->name('gallery.delete');
 Route::get('/password',[BhudevDashboardController::class,'passwordForm'])->name('password');
 Route::put('/password',[BhudevDashboardController::class,'updatePassword'])->name('password.update');
 Route::get('/history',[BhudevDashboardController::class,'history'])->name('history');
 Route::get('/samagri-studio',[BhudevDashboardController::class,'samagriStudio'])->name('samagri-studio');
 Route::post('/samagri-studio/save',[BhudevDashboardController::class,'saveSamagriCard'])->name('samagri-studio.save');
 Route::delete('/samagri-studio/{card}',[BhudevDashboardController::class,'deleteSamagriCard'])->name('samagri-studio.delete');
});
