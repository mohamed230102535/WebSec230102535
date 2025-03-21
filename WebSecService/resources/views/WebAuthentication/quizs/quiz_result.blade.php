@extends("layouts.master2")
@section("title", "Quiz Results - OneHitPoint")

@section("content")
    <div class="container">
        <h2>{{ $quiz->title }} - Your Quiz Results</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct Answer</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($userAnswers as $answer)
    <tr>
        <td>{{ $answer->question->question_text }}</td>
        <td>{{ $answer->choice ? $answer->choice->choice_text : "No answer selected" }}</td>
        <td>
            @php
                $correctChoice = \App\Models\choices::where('question_id', $answer->question_id)
                    ->where('is_correct', true)
                    ->first();
            @endphp
            {{ $correctChoice ? $correctChoice->choice_text : "N/A" }}
        </td>
        <td>
            @if ($correctChoice && $answer->choice && $answer->choice->id == $correctChoice->id)
                ✅ Correct
            @else
                ❌ Wrong
            @endif
        </td>
    </tr>
@endforeach

            </tbody>
        </table>

        <a href="{{ route('WebAuthentication.quiz') }}" class="btn btn-primary">Back to Main</a>
    </div>
@endsection
