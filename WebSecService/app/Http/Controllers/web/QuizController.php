<?php
namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Models\quiz;
use App\Models\question;
use App\Models\UserAnswer;
use App\Models\choices;

class QuizController extends Controller {
    public function quiz(Request $request) {
        $quizzes = quiz::all();
        return view("WebAuthentication.quizs.quiz", compact('quizzes'));
        }
        public function show($id)
        {
            $quiz = quiz::with('questions.choices')->findOrFail($id);
            return view("WebAuthentication.quizs.quiz_details", compact('quiz'));
        }
          
        public function submit(Request $request, $id) {
            $quiz = quiz::findOrFail($id);
            $questions = question::where('quiz_id', $id)->get();
            $userId = auth()->id();
        
            $correctAnswers = 0;
            $wrongAnswers = [];
        
            // Fetch correct answers for all questions in one query
            $correctChoices = choices::whereIn('question_id', $questions->pluck('id'))
                                    ->where('is_correct', true)
                                    ->get()
                                    ->keyBy('question_id'); 
        
            // Get last attempt number
            $lastAttempt = UserAnswer::where('user_id', $userId)
                ->whereHas('question', function ($query) use ($id) {
                    $query->where('quiz_id', $id);
                })
                ->max('attempt_number');
        
            // Increase attempt number if it's a new submission
            $currentAttempt = $lastAttempt ? $lastAttempt + 1 : 1;
        
            $userAnswers = [];
        
            foreach ($questions as $question) {
                $selectedChoice = $request->input("answers." . $question->id);
        
                // Check if the user has already answered this question in this quiz
                $existingAnswer = UserAnswer::where('user_id', $userId)
                    ->where('question_id', $question->id)
                    ->latest('attempt_number')
                    ->first();
        
                if ($existingAnswer) {
                    // Update existing answer instead of creating a new one
                    $existingAnswer->update([
                        'choice_id' => $selectedChoice,
                        'attempt_number' => $currentAttempt
                    ]);
                    $userAnswer = $existingAnswer;
                } else {
                    // Create a new answer if it doesn't exist
                    $userAnswer = UserAnswer::create([
                        'question_id' => $question->id,
                        'user_id' => $userId,
                        'choice_id' => $selectedChoice,
                        'attempt_number' => $currentAttempt
                    ]);
                }
        
                // Store user answers for the view
                $userAnswers[] = $userAnswer->load('choice');
        
                // Get the correct choice
                $correctChoice = $correctChoices[$question->id] ?? null;
        
                if ($correctChoice && $selectedChoice == $correctChoice->id) {
                    $correctAnswers++;
                } else {
                    $wrongAnswers[] = [
                        'question' => $question->question_text,
                        'your_answer' => $userAnswer->choice ? $userAnswer->choice->choice_text : 'No answer selected',
                        'correct_answer' => $correctChoice->choice_text ?? 'No correct answer available'
                    ];
                }
            }
        
            return view("WebAuthentication.quizs.quiz_result", compact('quiz', 'correctAnswers', 'wrongAnswers', 'userAnswers'));
        }
        

        public function myQuizzes()
        {
            $userId = auth()->id();
        
            // Fetch quizzes the student has either submitted answers for or can attempt
            $quizzes = quiz::whereHas('questions.answers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->orWhereDoesntHave('questions.answers')->get();
        
            // Fetch user's submissions grouped by quiz
            $submissions = UserAnswer::where('user_id', $userId)
                ->with(['question.quiz', 'choice'])
                ->get()
                ->groupBy(fn($answer) => $answer->question->quiz->id);
        
            return view('WebAuthentication.quizs.myQuiz', compact('quizzes', 'submissions'));
        }
        
        
}
