@extends("layouts.master2")
@section("title", $quiz->title . " - OneHitPoint")

@section("content")
    <div class="container mt-4">
        <h2>{{ $quiz->title }}</h2>
        <p>{{ $quiz->description }}</p>

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            @foreach($quiz->questions as $question)
                <div class="mb-3">
                    <p><strong>{{ $loop->iteration }}. {{ $question->question_text }}</strong></p>
                    
                    @foreach($question->choices as $choice)
                        <div class="form-check">
                            <input 
                                type="radio" 
                                id="choice_{{ $choice->id }}" 
                                name="answers[{{ $question->id }}]" 
                                value="{{ $choice->id }}" 
                                class="form-check-input">
                            <label for="choice_{{ $choice->id }}" class="form-check-label">
                                {{ $choice->choice_text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
    </div>
@endsection
