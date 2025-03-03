<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\web\ProductsController;
use App\Http\Controllers\web\UsersController;

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