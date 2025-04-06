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
        
            // Calculate the score
            $totalQuestions = $questions->count();
            $scorePercentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
        
            return view("WebAuthentication.quizs.quiz_result", [
                'quiz' => $quiz,
                'correctAnswers' => $correctAnswers,
                'wrongAnswers' => count($wrongAnswers), // Convert array to a count
                'userAnswers' => $userAnswers,
                'totalQuestions' => $questions->count(),
                'scorePercentage' => ($correctAnswers / max(1, $questions->count())) * 100, // Avoid division by zero
            ]);        }
        

            public function myQuizzes(Request $request)
            {
                // Get the authenticated user (assuming Onehitpoint model)
                $user = auth()->user();
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Please log in to view your quizzes.');
                }
        
                // Fetch quizzes the user has attempted via user_answers
                $attemptedQuizIds = UserAnswer::where('user_id', $user->id)
                    ->distinct()
                    ->pluck('question_id')
                    ->map(function ($questionId) {
                        return \App\Models\question::find($questionId)->quiz_id;
                    })
                    ->unique()
                    ->values();
        
                $quizzes = quiz::whereIn('id', $attemptedQuizIds)->get();
        
                // Fetch submissions and calculate scores
                $submissions = [];
                $scores = [];
                
                foreach ($quizzes as $quiz) {
                    $userAnswers = UserAnswer::where('user_id', $user->id)
                        ->whereIn('question_id', $quiz->questions->pluck('id'))
                        ->with('question.choices') // Eager load relationships
                        ->get();
        
                    $submissions[$quiz->id] = $userAnswers;
        
                    // Calculate score
                    $totalQuestions = $quiz->questions->count();
                    $correctAnswers = $userAnswers->filter(function ($answer) {
                        return $answer->choice && $answer->choice->is_correct;
                    })->count();
                    
                    $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
                    $scores[$quiz->id] = [
                        'correct' => $correctAnswers,
                        'total' => $totalQuestions,
                        'percentage' => round($score, 2),
                    ];
                }
        
                return view('WebAuthentication.quizs.myQuiz', [ 
                    'quizzes' => $quizzes,
                    'submissions' => $submissions,
                    'scores' => $scores,
                ]);
            }
//========================================================================================================
public function quizCreate()
{
    return view("WebAuthentication.quizs.quizCreate");
}

public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string|max:65535',
            'questions.*.choices' => 'required|array|min:2',
            'questions.*.choices.*.choice_text' => 'required|string|max:65535',
            'questions.*.correct_choice' => 'required|integer|min:0',
        ]);

        try {
            $quiz = quiz::create([
                'title' => $request->title,
                'description' => $request->description,
                'created_by' => auth()->id(), // Assumes Onehitpoint model with auth
            ]);

            foreach ($request->questions as $questionData) {
                $question = question::create([
                    'question_text' => $questionData['question_text'],
                    'quiz_id' => $quiz->id,
                ]);

                foreach ($questionData['choices'] as $index => $choiceData) {
                    choices::create([
                        'choice_text' => $choiceData['choice_text'],
                        'is_correct' => ($index == $questionData['correct_choice']) ? 1 : 0,
                        'question_id' => $question->id,
                    ]);
                }
            }

            return redirect()->route('WebAuthentication.quiz')->with('success', 'Quiz created successfully!');
        } catch (\Exception $e) {
            \Log::error('Quiz creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create quiz: ' . $e->getMessage())->withInput();
        }
    }
    // Show the form for editing a quiz
    public function quizEdit($id)
    {
        $quiz = quiz::findOrFail($id);
        return view("WebAuthentication.quizs.quizEdit", compact('quiz'));
    }

    public function update(Request $request, $id)
{
    // Validate request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'questions' => 'required|array|min:1',
        'questions.*.question_text' => 'required|string|max:65535', // TEXT field max length
        'questions.*.choices' => 'required|array|min:2',
        'questions.*.choices.*.choice_text' => 'required|string|max:65535', // TEXT field max length
        'questions.*.correct_choice' => 'required|integer|min:0', // Ensure valid index
    ]);

    try {
        // Find quiz
        $quiz = quiz::find($id);
        if (!$quiz) {
            return back()->with('error', 'Quiz not found!');
        }

        // Update quiz details
        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => auth()->id(), // Maintain created_by; adjust if not using auth
        ]);

        // Get existing question IDs from the request
        $requestQuestionIds = collect($request->questions)->pluck('id')->filter()->all();

        // Delete questions not in the request (optional cleanup)
        question::where('quiz_id', $quiz->id)
            ->whereNotIn('id', $requestQuestionIds)
            ->delete(); // CASCADE will handle choices

        // Process questions
        foreach ($request->questions as $questionData) {
            if (isset($questionData['id']) && $questionData['id']) {
                $question = question::find($questionData['id']);
                if (!$question || $question->quiz_id !== $quiz->id) {
                    return back()->with('error', 'One or more questions not found or invalid!');
                }
                $question->update([
                    'question_text' => $questionData['question_text'],
                ]);
            } else {
                $question = question::create([
                    'question_text' => $questionData['question_text'],
                    'quiz_id' => $quiz->id,
                ]);
            }

            // Get existing choice IDs from the request for this question
            $requestChoiceIds = collect($questionData['choices'])->pluck('id')->filter()->all();

            // Delete choices not in the request for this question (optional cleanup)
            choices::where('question_id', $question->id)
                ->whereNotIn('id', $requestChoiceIds)
                ->delete();

            // Process choices
            foreach ($questionData['choices'] as $index => $choiceData) {
                if (isset($choiceData['id']) && $choiceData['id']) {
                    $choice = choices::find($choiceData['id']);
                    if (!$choice || $choice->question_id !== $question->id) {
                        return back()->with('error', 'One or more choices not found or invalid!');
                    }
                    $choice->update([
                        'choice_text' => $choiceData['choice_text'],
                        'is_correct' => ($index == $questionData['correct_choice']) ? 1 : 0,
                    ]);
                } else {
                    choices::create([
                        'choice_text' => $choiceData['choice_text'],
                        'is_correct' => ($index == $questionData['correct_choice']) ? 1 : 0,
                        'question_id' => $question->id,
                    ]);
                }
            }
        }

        return redirect()->route('WebAuthentication.quiz', $quiz->id)
            ->with('success', 'Quiz updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Quiz update failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to update quiz: ' . $e->getMessage())->withInput();
    }
}

    // Delete a quiz from the database
    public function quizDestroy($id)
    {
        try {
            $quiz = quiz::findOrFail($id);
            $quiz->delete(); // CASCADE will handle questions, choices, and user_answers
    
            return redirect()->route('quiz.index')
                ->with('success', 'Quiz deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Quiz deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete quiz: ' . $e->getMessage());
        }
    }   
}
