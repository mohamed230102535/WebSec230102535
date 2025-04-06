@extends("layouts.master2")
@section("title", "Quiz Edit - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-edit me-2"></i>Edit Quiz
    </h1>
    <p class="lead" style="color: #ffffff;">
      Customize Your OneHitPoint Quiz
    </p>
  </div>
</section>

<!-- Edit Form Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
      <div class="card-body">
        <!-- Success/Error Messages -->
        @if(session('success'))
          <div class="alert alert-success animate__animated animate__fadeIn" style="background: #2c0b0e; border-color: #d4a373; color: #ffffff;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger animate__animated animate__fadeIn" style="background: #d71818; border-color: #b31414; color: #ffffff;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Quiz Edit Form -->
        <form action="{{ route('quiz.update', $quiz->id) }}" method="POST">
          @csrf

          <!-- Quiz Title -->
          <div class="mb-4">
            <label for="title" class="form-label text-gold">Quiz Title:</label>
            <input type="text" name="title" id="title" value="{{ $quiz->title }}" class="form-control bg-dark text-white border-danger" required>
            @error('title')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <!-- Quiz Description -->
          <div class="mb-4">
            <label for="description" class="form-label text-gold">Description:</label>
            <textarea name="description" id="description" class="form-control bg-dark text-white border-danger" rows="3">{{ $quiz->description }}</textarea>
            @error('description')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <!-- Questions Section -->
          <h3 class="text-gold mb-3">Questions</h3>
          <div id="questions">
            @foreach($quiz->questions as $qIndex => $question)
              <div class="question mb-4 p-3 bg-secondary rounded animate__animated animate__fadeInUp" style="animation-delay: {{ $qIndex * 0.1 }}s;">
                <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $question->id }}">
                <label class="form-label text-gold">Question:</label>
                <input type="text" name="questions[{{ $qIndex }}][question_text]" value="{{ $question->question_text }}" class="form-control bg-dark text-white border-danger" required>
                @error("questions.{$qIndex}.question_text")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

                <h4 class="text-gold mt-3">Choices:</h4>
                <div class="choices">
                  @foreach($question->choices as $cIndex => $choice)
                    <div class="choice-item mb-2">
                      <input type="hidden" name="questions[{{ $qIndex }}][choices][{{ $cIndex }}][id]" value="{{ $choice->id }}">
                      <div class="input-group">
                        <input type="text" name="questions[{{ $qIndex }}][choices][{{ $cIndex }}][choice_text]" value="{{ $choice->choice_text }}" class="form-control bg-dark text-white border-danger" required>
                        <input type="radio" name="questions[{{ $qIndex }}][correct_choice]" value="{{ $cIndex }}" {{ $choice->is_correct ? 'checked' : '' }} class="form-check-input ms-2">
                        <label class="form-check-label text-muted ms-2">Correct</label>
                      </div>
                      @error("questions.{$qIndex}.choices.{$cIndex}.choice_text")
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn btn-red btn-cool mt-3">
              <i class="fas fa-save me-2"></i>Update Quiz
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
/* OneHitPoint Theme */
.text-gold {
  color: #d4a373; /* Gold for labels and titles */
}
.text-muted {
  color: #aaaaaa; /* Muted gray for secondary text */
}

/* Card Styling */
.card {
  border: 1px solid #d4a373; /* Gold border */
  background: #2c2c2c; /* Dark gray background */
}

/* Form Elements */
.form-control, .form-control:focus {
  background-color: #2c2c2c; /* Dark input background */
  border-color: #d71818; /* Red border */
  color: #ffffff; /* White text */
  transition: all 0.3s ease;
}
.form-control:focus {
  box-shadow: 0 0 10px rgba(215, 24, 24, 0.5); /* Red glow on focus */
}

/* Question Container */
.question {
  background: #3a3a3a; /* Slightly lighter gray */
  border-radius: 8px;
  transition: transform 0.3s ease;
}
.question:hover {
  transform: translateY(-3px); /* Slight lift on hover */
}

/* Choice Item */
.choice-item {
  display: flex;
  align-items: center;
}
.choice-item .input-group {
  flex-grow: 1;
}
.choice-item .form-check-input {
  margin-left: 10px;
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

/* Error Text */
.text-danger {
  color: #d71818; /* Red for errors */
  font-size: 14px;
}
</style>

<!-- Scripts -->
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Button ripple effect
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