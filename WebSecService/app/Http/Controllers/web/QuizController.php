<?php
namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class QuizController extends Controller {
    public function quiz(Request $request) {
        
         return view("WebAuthentication.quiz");
        }
          
}
