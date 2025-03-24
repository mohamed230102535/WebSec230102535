<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\web\ProductsController;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\QuizController;
use App\Mail\VerificationEmail;

Route::get('/', function () {
    return view('welcome');
});
        
Route::get('/ass1', function () {
    return view('ass1'); 
   });

// Route::get('/test/{number?}', function ($number = null) {

//     $j =   $number ?? 2;
//     return view('test', compact('j')); 
//    });

Route::get('/test', function (Request $request) {
$j =  $request->number??2;
dd($request->all());

return view('test', compact('j')); 
});

// Route::get('/test', function () {

//     return view('test'); 
//    });


   Route::get('/minitest', function () {
    $bill = [
        ['item' => 'Jam', 'quantity' => 2, 'price' => 11],
        ['item' => 'Milk', 'quantity' => 1, 'price' => 5],
        ['item' => 'Bread', 'quantity' => 3, 'price' => 2.5],
        ['item' => 'Eggs', 'quantity' => 1, 'price' => 3.40],
        ['item' => 'Rice', 'quantity' => 5, 'price' => 1.00],
        ['item' => 'Chicken', 'quantity' => 2, 'price' => 8.75]
    ];
    
    return view('minitest', ['bill' => $bill]); 
});

Route::get('/transcript', function () {
    $student = [
        'name' => 'John Doe',
        'id' => 'STU123456',
        'department' => 'Computer Science',
        'GPA' => '3.8'
    ];

    $courses = [
        [
            'course' => 'Mathematics', 'credits' => 3, 'grade' => 'A',
            'instructor' => 'Dr. Smith', 'schedule' => 'Mon & Wed 10:00 AM - 11:30 AM'
        ],
        [
            'course' => 'Physics', 'credits' => 4, 'grade' => 'B+',
            'instructor' => 'Dr. Brown', 'schedule' => 'Tue & Thu 2:00 PM - 3:30 PM'
        ],
        [
            'course' => 'Computer Science', 'credits' => 3, 'grade' => 'A-',
            'instructor' => 'Prof. Johnson', 'schedule' => 'Mon & Wed 1:00 PM - 2:30 PM'
        ],
        [
            'course' => 'English', 'credits' => 2, 'grade' => 'B',
            'instructor' => 'Ms. Davis', 'schedule' => 'Fri 9:00 AM - 10:30 AM'
        ],
        [
            'course' => 'History', 'credits' => 3, 'grade' => 'C+',
            'instructor' => 'Mr. Wilson', 'schedule' => 'Tue & Thu 11:00 AM - 12:30 PM'
        ]
    ];

    // GPA Calculation
    $gradePoints = [
        'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0,
        'C+' => 2.7, 'C' => 2.3, 'D' => 2.0, 'F' => 0.0
    ];
    
    $totalCredits = 0;
    $totalPoints = 0;
    
    foreach ($courses as $course) {
        $credits = $course['credits'];
        $grade = $course['grade'];
        $points = $gradePoints[$grade] ?? 0;
        
        $totalCredits += $credits;
        $totalPoints += $credits * $points;
    }

    $gpa = $totalCredits ? round($totalPoints / $totalCredits, 2) : 0.0;

    return view('transcript', [
        'student' => $student,
        'courses' => $courses,
        'gpa' => $gpa
    ]);
});





Route::get('products', [ProductsController::class, 'list'])->name('products_list');





Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('/users', [UsersController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');


//Web Authentication ===========================================================================================

Route::get('/WebAuthentication', [AuthController::class, 'index'])->name('WebAuthentication.index');

Route::get('/WebAuthentication/login', [AuthController::class, 'login'])->name('WebAuthentication.login');
Route::post('/WebAuthentication/login', [AuthController::class, 'doLogin'])->name('WebAuthentication.doLogin');
Route::get('/WebAuthentication/register', [AuthController::class, 'register'])->name('WebAuthentication.register');
Route::post('/WebAuthentication/register', [AuthController::class, 'doRegister'])->name('WebAuthentication.doRegister');
Route::post('/WebAuthentication/logout', [AuthController::class, 'doLogout'])->name('WebAuthentication.doLogout');
Route::get('/WebAuthentication', [AuthController::class, 'index'])->name('WebAuthentication.index');

///verify

// User Account Routes

Route::get('/WebAuthentication/userAccount', [AuthController::class, 'userAccount'])->name('WebAuthentication.userAccount');
Route::post('/WebAuthentication/updateUsername', [AuthController::class, 'updateUsername'])->name('WebAuthentication.updateUsername');
Route::post('/WebAuthentication/updatePassword', [AuthController::class, 'updatePassword'])->name('WebAuthentication.updatePassword');
Route::get('/WebAuthentication/forgotPassword', [AuthController::class, 'forgotPassword'])->name('WebAuthentication.forgotPassword');
Route::post('/WebAuthentication/doResetPassword', [AuthController::class, 'doResetPassword'])->name('WebAuthentication.doResetPassword');
Route::post('/WebAuthentication/addCredit', [AuthController::class, 'addCredit'])->name('WebAuthentication.addCredit');

// User Management Routes

Route::get('/WebAuthentication/dashboard', [AuthController::class, 'dashboard'])->name('WebAuthentication.dashboard');
Route::get('/WebAuthentication/users/create', [AuthController::class, 'createUser'])->name('WebAuthentication.createUser');
Route::post('/WebAuthentication/users', [AuthController::class, 'storeUser'])->name('WebAuthentication.storeUser');
Route::get('/WebAuthentication/users/edit/{id}', [AuthController::class, 'editUser'])->name('WebAuthentication.editUser');
Route::post('/WebAuthentication/update/{id}', [AuthController::class, 'updateUser'])->name('WebAuthentication.updateUser');
Route::get('/WebAuthentication/delate/{id}', [AuthController::class, 'deleteUser'])->name('WebAuthentication.deleteUser');

// Product Management Routes

Route::get('/WebAuthentication/products', [ProductsController::class, 'list'])->name('WebAuthentication.products');
Route::get('/WebAuthentication/products/create', [ProductsController::class, 'create'])->name('WebAuthentication.products.create');
Route::get('/WebAuthentication/products/edit/{product}', [ProductsController::class, 'edit'])->name('WebAuthentication.products.edit');
Route::post('/WebAuthentication/products/create', [ProductsController::class, 'doCreate'])->name('WebAuthentication.products.doCreate');
Route::get('/WebAuthentication/products/delete/{product}', [ProductsController::class, 'delete'])->name('WebAuthentication.products.delete');
Route::post('/WebAuthentication/products/edit/{product}', [ProductsController::class, 'doEdit'])->name('WebAuthentication.products.doEdit');
Route::post('/WebAuthentication/products/purchase/{product}', [ProductsController::class, 'purchase'])->name('WebAuthentication.products.purchase');
Route::get('/WebAuthentication/products/history', [ProductsController::class, 'purchaseHistory'])->name('WebAuthentication.products.history');
// Quiz Management Routes

Route::get('/WebAuthentication/quiz', [QuizController::class, 'quiz'])->name('WebAuthentication.quiz');
Route::get('/WebAuthentication/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/WebAuthentication/quiz/{id}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('/WebAuthentication/quiz/{id}/result', [QuizController::class, 'result'])->name('quiz.result');
Route::get('/WebAuthentication/myQuiz', [QuizController::class, 'myQuizzes'])->name('quiz.MyQuizzes');

// Quiz Management CRUD Routes

Route::get('/WebAuthentication/quizs/create', [QuizController::class, 'quizCreate'])->name('quiz.create'); // Show form to create a new quiz
Route::post('/WebAuthentication/quizs', [QuizController::class, 'store'])->name('quiz.store'); // Store a new quiz
Route::get('/WebAuthentication/quizs/{id}/edit', [QuizController::class, 'quizEdit'])->name('quiz.edit'); // Show form to edit an existing quiz
Route::post('/WebAuthentication/quizs/{id}', [QuizController::class, 'update'])->name('quiz.update'); // Update an existing quiz
Route::post('/WebAuthentication/quizs/{id}/delete', [QuizController::class, 'quizDestroy'])->name('quiz.destroy'); // Delete a quiz