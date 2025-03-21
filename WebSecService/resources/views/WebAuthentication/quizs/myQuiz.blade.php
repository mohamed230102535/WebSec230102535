@extends("layouts.master2")
@section("title"," My quizes- OneHitPoint")

@section("content")
<div class="container">
    <h1>My Quizzes</h1>

    @foreach ($quizzes as $quiz)
        <div class="card mb-4">
            <div class="card-header">
                <h2>{{ $quiz->title }}</h2>
            </div>
            <div class="card-body">
                @if (isset($submissions[$quiz->id]))
                    <h3>Your Submissions:</h3>
                    <ul>
                        @foreach ($submissions[$quiz->id] as $submission)
                            <li>
                                <strong>Question:</strong> {{ $submission->question->question_text }}<br>
                                <strong>Your Answer:</strong> 
                                @if($submission->choice)
                                    {{ $submission->choice->choice_text }}
                                @else
                                    <span style="color: red;">No answer selected</span>
                                @endif
                                <br>

                                <strong>Correct Answer:</strong> 
                                @php
                                    $correctChoice = $submission->question->choices->where('is_correct', true)->first();
                                @endphp
                                {{ $correctChoice ? $correctChoice->choice_text : 'No correct answer available' }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>You have not submitted any answers for this quiz yet.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection