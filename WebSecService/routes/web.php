<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Auth\PagesController;

// Regular Authentication Routes
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// GitHub Authentication Routes
Route::get('auth/github', [AuthController::class, 'redirectToGithub'])->name('github.login');
Route::get('auth/github/callback', [AuthController::class, 'handleGithubCallback'])->name('github.callback');

// User Management Routes
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::get('/users/create', [UsersController::class, 'create'])->name('users_create');
Route::post('/users/store', [UsersController::class, 'store'])->name('users_store');

Route::get('verify', [UsersController::class, 'verify'])->name('verify');

Route::get('/forgot-password', [UsersController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/reset', [UsersController::class, 'sendResetLink'])->name('password.reset');
Route::get('ShowRestForm', [UsersController::class, 'showResetLink'])->name('ShowRestForm');
Route::post('/reset-password', [UsersController::class, 'resetPassword'])->name('password.update');

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
Route::post('/purchase/{productId}', [ProductsController::class, 'purchaseProduct'])->name('purchase_product'); //buy product


Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/test-email', function () {
    try {
        $config = config('mail');
        $debug = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'from' => config('mail.from.address'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'password_set' => !empty(config('mail.mailers.smtp.password'))
        ];
        
        try {
            Mail::raw('Test email from Laravel at ' . now(), function($message) {
                $message->to('m7md1hp@gmail.com')
                        ->subject('Test Email ' . now());
            });
            return 'Email sent successfully! Debug info: ' . json_encode($debug, JSON_PRETTY_PRINT);
        } catch (\Swift_TransportException $e) {
            return 'SMTP Error: ' . $e->getMessage() . '\nDebug info: ' . json_encode($debug, JSON_PRETTY_PRINT);
        }
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});





// Authenticated routes
Route::middleware(['web', 'auth'])->group(function() {
    Route::prefix('app')->group(function() {
        Route::get('/', [PagesController::class, 'app'])->name('app');
    });
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
