@extends("layouts.master2")
@section("title", "Quiz - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e); position: relative; overflow: hidden;">
  <div class="container text-center position-relative z-index-1">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7), 0 0 30px rgba(212, 163, 115, 0.5);">
      <i class="fas fa-book-open me-2"></i> Available Quizzes
    </h1>
    <p class="lead" style="color: #ffffff; text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);">
      Challenge Your Mind with OneHitPoint
    </p>
  </div>
  <!-- Background Effect -->
  <div class="header-bg-effect" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, rgba(212, 163, 115, 0.1) 0%, rgba(44, 11, 14, 0.8) 80%); opacity: 0.7;"></div>
</section>

<!-- Quiz List Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px); position: relative;">
  <div class="container">
    <!-- Create New Quiz Button -->
    <div class="d-flex justify-content-end mb-4">
    @can('quizCreate')
      <a href="{{ route('quiz.create') }}" class="btn-create">
        <i class="fas fa-plus-circle me-2"></i> Create New Quiz
      </a>
    @endcan
    </div>
    <div class="quiz-list">
      @forelse($quizzes as $quiz)
        <div class="quiz-card animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
          <div class="quiz-content">
            <h5 class="quiz-title">{{ $quiz->title }}</h5>
            <div class="quiz-actions">
              <a href="{{ route('quiz.show', $quiz->id) }}" class="btn-start">
                <i class="fas fa-play me-2"></i> Start Quiz
              </a>
              @can('quizEdit')
              <a href="{{ route('quiz.edit', $quiz->id) }}" class="btn-edit">
                <i class="fas fa-edit me-2"></i> Edit
              </a>
              @endcan
              @can('quizDestroy')
              <form action="{{ route('quiz.destroy', $quiz->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-remove" onclick="return confirm('Are you sure you want to delete this quiz?')">
                  <i class="fas fa-trash-alt me-2"></i> Remove
                </button>
              </form>
              @endcan
            </div>
          </div>
          <p class="quiz-desc">{{ $quiz->description ?? 'Test your skills with this exciting quiz!' }}</p>
          <!-- Neon Glow Effect -->
          <span class="glow-effect"></span>
        </div>
      @empty
        <div class="text-center text-white">
          <h3>No quizzes available at the moment.</h3>
          <p>Check back soon for new challenges!</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
/* OneHitPoint Theme */
.text-gold {
  color: #d4a373; /* Gold for titles and highlights */
}

/* Header Enhancements */
.z-index-1 {
  position: relative;
  z-index: 1;
}

/* Quiz List Container */
.quiz-list {
  display: flex;
  flex-direction: column;
  gap: 25px; /* Increased spacing */
}

/* Quiz Cards */
.quiz-card {
  background: linear-gradient(135deg, #2c2c2c 0%, #1f1f1f 100%); /* Gradient background */
  border-left: 5px solid #d71818; /* Red accent */
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), inset 0 0 10px rgba(212, 163, 115, 0.1); /* Inner glow */
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  position: relative;
  overflow: hidden;
}
.quiz-card:hover {
  transform: translateY(-8px) scale(1.02); /* Slight scale on hover */
  box-shadow: 0 15px 30px rgba(212, 163, 115, 0.3), 0 0 20px rgba(215, 24, 24, 0.2); /* Enhanced glow */
  border-left-color: #d4a373; /* Gold on hover */
}

/* Glow Effect */
.glow-effect {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(212, 163, 115, 0.2) 0%, transparent 70%);
  opacity: 0;
  transition: opacity 0.5s ease;
  pointer-events: none;
}
.quiz-card:hover .glow-effect {
  opacity: 0.5;
}

/* Quiz Content */
.quiz-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

/* Quiz Title */
.quiz-title {
  color: #ffffff;
  font-size: 26px;
  font-weight: bold;
  text-shadow: 0 0 8px rgba(212, 163, 115, 0.4); /* Enhanced glow */
  margin: 0;
  letter-spacing: 1px; /* Slight spacing for futuristic feel */
}

/* Quiz Description */
.quiz-desc {
  color: rgba(255, 255, 255, 0.8);
  font-size: 16px;
  margin: 0;
  font-style: italic;
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.1);
}

/* Start Button */
.btn-start {
  background: #d71818; /* Japanese red */
  color: #ffffff;
  padding: 12px 25px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 8px;
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}
.btn-start:hover {
  background: #b31414; /* Darker red */
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(215, 24, 24, 0.7), 0 0 25px rgba(215, 24, 24, 0.4); /* Red neon glow */
}

/* Edit Button */
.btn-edit {
  background: #ffcc00; /* Yellow */
  color: #000;
  padding: 12px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 8px;
  text-decoration: none;
  transition: all 0.3s ease;
}
.btn-edit:hover {
  background: #e6b800;
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(255, 204, 0, 0.7);
}

/* Remove Button */
.btn-remove {
  background: #d71818;
  color: #fff;
  padding: 12px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}
.btn-remove:hover {
  background: #b31414;
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(215, 24, 24, 0.7);
}

/* Create Button */
.btn-create {
  background: linear-gradient(45deg, #28a745, #34d058); /* Gradient green */
  color: #fff;
  padding: 14px 30px;
  font-size: 18px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: bold;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.2); /* Glow with inset */
  border: 2px solid #34d058; /* Light green border */
}
.btn-create:hover {
  background: linear-gradient(45deg, #218838, #2ebd4b);
  transform: scale(1.05) translateY(-2px); /* Slight lift */
  box-shadow: 0 10px 25px rgba(40, 167, 69, 0.9), inset 0 0 15px rgba(255, 255, 255, 0.3);
  border-color: #d4a373; /* Gold border on hover */
}

/* Quiz Actions */
.quiz-actions {
  display: flex;
  gap: 15px;
}
</style>

<!-- Scripts -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Enhanced ripple effect for buttons
  $('.btn-start, .btn-edit, .btn-remove, .btn-create').on('click', function(e) {
    let x = e.pageX - $(this).offset().left;
    let y = e.pageY - $(this).offset().top;
    
    let ripple = $('<span/>');
    ripple
      .css({
        left: x,
        top: y,
        position: 'absolute',
        background: 'rgba(255, 255, 255, 0.3)', /* White ripple for contrast */
        'border-radius': '50%',
        transform: 'scale(0)',
        'pointer-events': 'none',
        width: '100px',
        height: '100px',
        'margin-left': '-50px',
        'margin-top': '-50px'
      })
      .animate({
        scale: 2.5,
        opacity: 0
      }, 400, function() {
        $(this).remove();
      });
      
    $(this).append(ripple);
  });
});
</script>


@endsection