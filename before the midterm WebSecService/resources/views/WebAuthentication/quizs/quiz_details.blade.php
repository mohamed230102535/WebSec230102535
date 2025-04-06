@extends("layouts.master2")
@section("title", $quiz->title . " - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-scroll me-2"></i>{{ $quiz->title }}
    </h1>
    <p class="lead animate__animated animate__fadeIn" style="color: #ffffff; animation-delay: 0.2s;">
      {{ $quiz->description ?? 'Embark on this knowledge journey!' }}
    </p>
  </div>
</section>

<!-- Quiz Form Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
      @csrf
      @foreach($quiz->questions as $question)
        <div class="quiz-card mb-4 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
          <p class="question-text"><strong>{{ $loop->iteration }}. {{ $question->question_text }}</strong></p>
          @foreach($question->choices as $choice)
            <div class="choice-container">
              <input 
                type="radio" 
                id="choice_{{ $choice->id }}" 
                name="answers[{{ $question->id }}]" 
                value="{{ $choice->id }}" 
                class="form-check-input choice-input">
              <label for="choice_{{ $choice->id }}" class="form-check-label choice-label">
                {{ $choice->choice_text }}
              </label>
              <span class="ripple-effect"></span>
            </div>
          @endforeach
        </div>
      @endforeach

      <div class="text-center">
        <button type="submit" class="btn btn-red btn-cool animate__animated animate__pulse animate__infinite">
          <i class="fas fa-check me-2"></i>Submit Quiz
        </button>
      </div>
    </form>
  </div>
</section>

<!-- Custom Styles -->
<style>
/* OneHitPoint Theme */
.text-gold {
  color: #d4a373; /* Gold for titles */
}
.text-light {
  color: #ffffff; /* White for readability */
}

/* Quiz Card */
.quiz-card {
  background: #2c2c2c; /* Dark gray background */
  border-left: 5px solid #d71818; /* Red accent */
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4); /* Deeper shadow */
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.quiz-card:hover {
  transform: translateY(-5px); /* Lift on hover */
  box-shadow: 0 10px 25px rgba(212, 163, 115, 0.2); /* Gold-tinted shadow */
  border-left-color: #d4a373; /* Shift to gold */
}

/* Question Text */
.question-text {
  color: #ffffff;
  font-size: 20px;
  margin-bottom: 15px;
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.1); /* Subtle glow */
}

/* Choice Styling */
.choice-container {
  position: relative;
  padding: 12px;
  border-radius: 8px;
  transition: background 0.3s ease;
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
.choice-input {
  margin-right: 10px;
}
.choice-label {
  color: #aaaaaa; /* Muted gray default */
  transition: color 0.3s ease;
}
.choice-input:checked + .choice-label {
  color: #d4a373; /* Gold when selected */
  font-weight: bold;
}
.choice-container:hover {
  background: rgba(212, 163, 115, 0.1); /* Gold tint on hover */
}

/* Ripple Effect */
.ripple-effect {
  position: absolute;
  width: 0;
  height: 0;
  background: rgba(212, 163, 115, 0.3); /* Gold ripple */
  border-radius: 50%;
  transform: scale(0);
  pointer-events: none;
  opacity: 0;
  transition: transform 0.4s ease, opacity 0.4s ease;
}
.choice-input:checked + .choice-label + .ripple-effect {
  width: 100px;
  height: 100px;
  transform: scale(2);
  opacity: 1;
  transition: transform 0s ease, opacity 0s ease; /* Instant trigger */
}

/* Submit Button */
.btn-red {
  background: #d71818; /* Japanese red */
  color: #ffffff;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  padding: 12px 25px;
  transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
  box-shadow: 0 5px 15px rgba(215, 24, 24, 0.4); /* Red glow */
  position: relative;
  overflow: hidden;
}
.btn-red:hover {
  background: #b31414; /* Darker red */
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(215, 24, 24, 0.6);
}
.btn-red::after {
  content: '';
  position: absolute;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transition: width 0.5s ease, height 0.5s ease;
}
.btn-red:active::after {
  width: 200px;
  height: 200px;
  transition: width 0s ease, height 0s ease;
}
</style>

<!-- Scripts -->
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Ripple effect for choices
  $('.choice-container').on('click', function(e) {
    let ripple = $(this).find('.ripple-effect');
    let x = e.pageX - $(this).offset().left;
    let y = e.pageY - $(this).offset().top;
    
    ripple.css({ left: x, top: y });
    ripple.addClass('active');
    setTimeout(() => ripple.removeClass('active'), 400);
  });

  // Ripple effect for submit button
  $('.btn-red').on('click', function(e) {
    let x = e.pageX - $(this).offset().left;
    let y = e.pageY - $(this).offset().top;
    
    let ripple = $('<span/>');
    ripple
      .css({
        left: x,
        top: y,
        position: 'absolute',
        background: 'rgba(212, 163, 115, 0.3)', /* Gold ripple */
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
});
</script>
@endsection

@endsection