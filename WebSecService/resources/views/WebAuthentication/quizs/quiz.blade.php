@extends("layouts.master2")
@section("title", "Quiz - OneHitPoint")

@section("content")
    <div class="container mt-4">
        <h2>Available Quizzes</h2>
        <ul>
            @foreach($quizzes as $quiz)
                <li>
                    <a href="{{ route('quiz.show', $quiz->id) }}">{{ $quiz->title }}</a>
                </li>
            @endforeach

        </ul>
    </div>
@endsection
