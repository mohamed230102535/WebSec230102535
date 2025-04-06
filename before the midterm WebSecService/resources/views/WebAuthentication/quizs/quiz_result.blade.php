@extends("layouts.master2")
@section("title", "Quiz Results - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-brain me-2"></i>{{ $quiz->title }} - Quiz Results
    </h1>
    <p class="lead" style="color: #ffffff;">
      Test Your Knowledge, Master Your Skills
    </p>
  </div>
</section>

<!-- Results Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
      <div class="card-header" style="background: #2c0b0e; border-bottom: 2px solid #d4a373;">
        <h4 class="text-center mb-0" style="color: #d4a373;">
          <i class="fas fa-trophy me-2"></i>Your Performance
        </h4>
      </div>
      <div class="card-body">
        <!-- Results Table -->
        <div class="table-responsive">
          <table class="table table-dark table-hover">
            <thead>
              <tr style="background: #4a1a1e;">
                <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Question</th>
                <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Your Answer</th>
                <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Correct Answer</th>
                <th style="color: #d4a373; border-bottom: 1px solid #d71818;">Result</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($userAnswers as $answer)
                <tr class="result-row">
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
                      <span class="badge bg-success">✅ Correct</span>
                    @else
                      <span class="badge bg-danger">❌ Wrong</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Results Summary -->
        <div class="mt-4 text-center">
          <h3 style="color: #d4a373; text-shadow: 0 0 10px rgba(212, 163, 115, 0.5);">Quiz Summary</h3>
          <div class="row justify-content-center g-3">
            <div class="col-md-3">
              <div class="p-3 rounded" style="background: #2c0b0e; border: 1px solid #d71818;">
                <h5 style="color: #d4a373;">Total Questions</h5>
                <p class="fs-4" style="color: #ffffff;">{{ $totalQuestions }}</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 rounded" style="background: #2c0b0e; border: 1px solid #d71818;">
                <h5 style="color: #d4a373;">Correct Answers</h5>
                <p class="fs-4" style="color: #ffffff;">{{ $correctAnswers }}</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 rounded" style="background: #2c0b0e; border: 1px solid #d71818;">
                <h5 style="color: #d4a373;">Wrong Answers</h5>
                <p class="fs-4" style="color: #ffffff;">{{ $wrongAnswers }}</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 rounded" style="background: #2c0b0e; border: 1px solid #d71818;">
                <h5 style="color: #d4a373;">Score</h5>
                <p class="fs-4" style="color: #ffffff;">{{ number_format($scorePercentage, 2) }}%</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
          <a href="{{ route('WebAuthentication.quiz') }}" class="btn btn-outline-warning btn-cool px-4">
            <i class="fas fa-arrow-left me-2"></i>Back to Main
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
.card {
  border: 1px solid #d4a373;
}

.table-dark {
  --bs-table-bg: #2c2c2c;
  --bs-table-hover-bg: #3a3a3a;
}

.table td {
  color: #ffffff;
  vertical-align: middle;
}

.result-row {
  transition: all 0.3s ease;
}

.result-row:hover {
  background-color: #3a3a3a;
  transform: translateY(-2px);
}

.btn-cool {
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.btn-cool:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(212, 163, 115, 0.3);
  color: #ffffff !important;
}

.btn-outline-warning {
  border-color: #d4a373;
  color: #d4a373;
}

.btn-outline-warning:hover {
  background-color: #d4a373;
  color: #2c0b0e;
}

.badge.bg-success {
  background-color: #d71818 !important; /* Red for correct */
}

.badge.bg-danger {
  background-color: #4a1a1e !important; /* Darker red for wrong */
}
</style>

<!-- Scripts -->
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Button ripple effect
  $('.btn-cool').on('click', function(e) {
    let x = e.pageX - $(this).offset().left;
    let y = e.pageY - $(this).offset().top;
    
    let ripple = $('<span/>');
    ripple
      .css({
        left: x,
        top: y,
        position: 'absolute',
        background: 'rgba(215, 24, 24, 0.2)', /* Japanese red */
        'border-radius': '50%',
        transform: 'scale(0)',
        'pointer-events': 'none'
      })
      .animate({
        scale: 2,
        opacity: 0
      }, 500, function() {
        $(this).remove();
      });
      
    $(this).append(ripple);
  });

  // Fade in animation on scroll
  $(window).scroll(function() {
    $('.result-row').each(function() {
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

@endsection