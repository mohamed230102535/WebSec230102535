@extends("layouts.master2")
@section("title", "My Quizzes - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-book me-2"></i>My Quizzes
    </h1>
    <p class="lead" style="color: #ffffff;">
      Review Your Quiz Journey
    </p>
  </div>
</section>

<!-- Quizzes Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    @forelse ($quizzes as $quiz)
      <div class="card quiz-card mb-4 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
        <div class="card-header" style="background: #2c0b0e; border-bottom: 2px solid #d4a373;">
          <h2 class="quiz-title" style="color: #d4a373;">{{ $quiz->title }}</h2>
          <p class="score-text" style="color: #ffffff; margin: 0;">
            <strong>Score:</strong> 
            {{ $scores[$quiz->id]['correct'] }} / {{ $scores[$quiz->id]['total'] }} 
            ({{ $scores[$quiz->id]['percentage'] }}%)
          </p>
        </div>
        <div class="card-body">
          @if (isset($submissions[$quiz->id]) && !$submissions[$quiz->id]->isEmpty())
            <h3 class="submission-title" style="color: #d4a373;">Your Submissions:</h3>
            <ul class="submission-list">
              @foreach ($submissions[$quiz->id] as $submission)
                <li class="submission-item">
                  <p><strong class="text-gold">Question:</strong> {{ $submission->question->question_text }}</p>
                  <p>
                    <strong class="text-gold">Your Answer:</strong> 
                    @if($submission->choice)
                      <span class="answer-text {{ $submission->choice->is_correct ? 'correct-answer' : '' }}">
                        {{ $submission->choice->choice_text }}
                      </span>
                    @else
                      <span class="no-answer">No answer selected</span>
                    @endif
                  </p>
                  <p>
                    <strong class="text-gold">Correct Answer:</strong> 
                    @php
                      $correctChoice = $submission->question->choices->where('is_correct', true)->first();
                    @endphp
                    <span class="correct-answer">{{ $correctChoice ? $correctChoice->choice_text : 'No correct answer available' }}</span>
                  </p>
                </li>
              @endforeach
            </ul>
          @else
            <p class="text-muted">You have not submitted any answers for this quiz yet.</p>
          @endif
        </div>
      </div>
    @empty
      <div class="text-center text-white">
        <h3>No quizzes attempted yet.</h3>
        <p>Start taking quizzes to see your results here!</p>
      </div>
    @endforelse
  </div>
</section>

<!-- Custom Styles -->
<style>
/* OneHitPoint Theme */
.text-gold {
  color: #d4a373; /* Gold for highlights */
}

/* Quiz Card */
.quiz-card {
  background: #2c2c2c; /* Dark gray background */
  border: 1px solid #d4a373; /* Gold border */
  border-radius: 10px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4); /* Deeper shadow */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.quiz-card:hover {
  transform: translateY(-5px); /* Lift on hover */
  box-shadow: 0 10px 25px rgba(212, 163, 115, 0.2); /* Gold-tinted shadow */
}

/* Quiz Title */
.quiz-title {
  font-size: 28px;
  font-weight: bold;
  margin: 0;
  text-shadow: 0 0 5px rgba(212, 163, 115, 0.3); /* Subtle glow */
}

/* Score Text */
.score-text {
  font-size: 16px;
}

/* Submission Title */
.submission-title {
  font-size: 22px;
  margin-bottom: 15px;
}

/* Submission List */
.submission-list {
  list-style: none;
  padding: 0;
}
.submission-item {
  background: #3a3a3a; /* Slightly lighter gray */
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
  transition: transform 0.3s ease, background 0.3s ease;
}
.submission-item:hover {
  transform: translateX(5px); /* Slight shift on hover */
  background: #4a4a4a; /* Even lighter gray */
}

/* Submission Text */
.submission-item p {
  margin: 5px 0;
  color: #ffffff;
}
.answer-text {
  color: #d4a373; /* Gold for user answers */
}
.answer-text.correct-answer {
  color: #00cc00; /* Green for correct user answers */
}
.no-answer {
  color: #d71818; /* Red for no answer */
  font-style: italic;
}
.correct-answer {
  color: #aaaaaa; /* Muted gray for correct answers */
}

/* Text Muted */
.text-muted {
  color: #aaaaaa; /* Muted gray for no submissions */
}
</style>

<!-- Scripts -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Fade in animation on scroll for submission items
  $(window).scroll(function() {
    $('.submission-item').each(function() {
      let top_of_element = $(this).offset().top;
      let bottom_of_window = $(window).scrollTop() + $(window).height();
      
      if (bottom_of_window > top_of_element) {
        $(this).addClass('animate__animated animate__fadeInUp');
      }
    });
  });
});
</script>


@endsection