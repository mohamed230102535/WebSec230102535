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
use Illuminate\Support\Facades\DB;
// Authentication Routes

    // Regular Login/Register
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::post('login', [UsersController::class, 'doLogin'])
->middleware('throttle:5,1')
->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('logout');
// Auth Routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('auth/github', [AuthController::class, 'redirectToGithub'])->name('github.login');
Route::get('auth/github/callback', [AuthController::class, 'handleGithubCallback'])->name('github.callback');

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
Route::post('/password/reset', [UsersController::class, 'sendResetLink'])->middleware('throttle:3,1')->name('password.reset');
Route::get('ShowRestForm', [UsersController::class, 'showResetLink'])->name('ShowRestForm');
Route::post('/reset-password', [UsersController::class, 'resetPassword'])
    ->middleware('throttle:3,1')
    ->name('password.update');

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');

// Rate-limited product actions (10 requests per minute per user/IP)
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
    Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
    Route::post('/purchase/{productId}', [ProductsController::class, 'purchaseProduct'])->name('purchase_product');
});


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

Route::get('/cryptography', function (Request $request) {
    $data = $request->data ?? "Welcome to Cryptography";
    $action = $request->action ?? "Encrypt";
    $result = $request->result ?? "";
    $status = "Failed";

    try {
        if($action == "Encrypt") {
            $temp = openssl_encrypt($data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
            if($temp) {
                $status = 'Encrypted Successfully';
                $result = base64_encode($temp);
            }
        }
        else if($action == "Decrypt") {
            $temp = base64_decode($data);
            $result = openssl_decrypt($temp, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
            if($result) $status = 'Decrypted Successfully';
        }
        else if($action == "Hash") {
            $temp = hash('sha256', $data);
            $result = base64_encode($temp);
            $status = 'Hashed Successfully';
        }
        else if($action == "Sign") {
            $path = storage_path('app/certificates/useremail@domain.com.pfx');
            $password = '12345678';
            $certificates = [];
            if (file_exists($path)) {
                $pfx = file_get_contents($path);
                if(openssl_pkcs12_read($pfx, $certificates, $password)) {
                    $privateKey = $certificates['pkey'];
                    $signature = '';
                    if(openssl_sign($data, $signature, $privateKey, 'sha256')) {
                        $result = base64_encode($signature);
                        $status = 'Signed Successfully';
                    }
                } else {
                    $status = 'Failed to read PFX file';
                }
            } else {
                $status = 'PFX file not found';
            }
        }
        else if($action == "Verify") {
            $signature = base64_decode($request->result);
            $path = storage_path('app/certificates/useremail@domain.com.crt');
            if (file_exists($path)) {
                $publicKey = file_get_contents($path);
                if(openssl_verify($data, $signature, $publicKey, 'sha256')) {
                    $status = 'Verified Successfully';
                }
            } else {
                $status = 'Certificate file not found';
            }
        }
        else if($action == "KeySend") {
            $path = storage_path('app/certificates/useremail@domain.com.crt');
            if (file_exists($path)) {
                $publicKey = file_get_contents($path);
                $temp = '';
                if(openssl_public_encrypt($data, $temp, $publicKey)) {
                    $result = base64_encode($temp);
                    $status = 'Key is Encrypted Successfully';
                }
            } else {
                $status = 'Certificate file not found';
            }
        }
        else if($action == "KeyRecive") {
            $path = storage_path('app/certificates/useremail@domain.com.pfx');
            $password = '12345678';
            $certificates = [];
            if (file_exists($path)) {
                $pfx = file_get_contents($path);
                if(openssl_pkcs12_read($pfx, $certificates, $password)) {
                    $privateKey = $certificates['pkey'];
                    $encryptedKey = base64_decode($data);
                    $result = '';
                    if(openssl_private_decrypt($encryptedKey, $result, $privateKey)) {
                        $status = 'Key is Decrypted Successfully';
                    }
                } else {
                    $status = 'Failed to read PFX file';
                }
            } else {
                $status = 'PFX file not found';
            }
        }
    } catch (\Exception $e) {
        $status = 'Error: ' . $e->getMessage();
    }

    return view('cryptography', compact('data', 'result', 'action', 'status'));
})->name('cryptography');

// Authenticated routes
Route::middleware(['web', 'auth'])->group(function() {
    Route::prefix('app')->group(function() {
        Route::get('/', [PagesController::class, 'app'])->name('app');
    });
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});



// Route::get('sqli', function (Request $request) {
//    $table = $request->query('table');
//    DB::unprepared("DROP TABLE IF EXISTS {$table}");
//    return redirect('/');
// });

// Route::get('collect', function(Request $request){
//    $name = $request->query('name');
//    $credit = $request->query('credit');
//    return response("data collected", 200)
//        ->header('Access-Control-Allow-Origin', '*')
//        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
//        ->header('Access-control-Allow-Headers', 'X-Requested-With, Content-Type, Accept');
// });