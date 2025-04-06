@extends("layouts.master2")
@section("title", "OneHitPoint")
@section("content")

<!-- Hero Section -->
<section class="hero vh-100 text-white d-flex align-items-center" 
         style="background: linear-gradient(135deg, #2c0b0e 0%, #4a1a1e 70%), url('https://source.unsplash.com/random/1920x1080?japanese,tech'); background-size: cover; background-position: center;">
  <div class="container text-center">
    <h1 class="display-1 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 20px rgba(212, 163, 115, 0.7);">
      OneHitPoint
    </h1>
    <p class="lead animate__animated animate__fadeInUp animate__delay-1s" style="color: #ffffff;">
      Your Ultimate Hub for Cutting-Edge PC Products & Tech/Gaming Quizzes
    </p>
    <div class="mt-5">
      <a href="#products" class="btn btn-danger btn-lg btn-cool mx-2">
        <i class="fas fa-desktop me-2"></i>View Products
      </a>
      <a href="#quizzes" class="btn btn-outline-warning btn-lg btn-cool mx-2">
        <i class="fas fa-brain me-2"></i>Take Quizzes
      </a>
    </div>
  </div>
</section>

<!-- Products Section -->
<section id="products" class="py-5" style="background: #1a1a1a;">
  <div class="container">
    <h2 class="text-center fw-bold mb-5" style="color: #d4a373; text-shadow: 0 0 10px rgba(212, 163, 115, 0.5);">
      <i class="fas fa-dragon me-2"></i>PC Products
    </h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card bg-dark text-white border-0 shadow-lg product-card">
          <img src="https://source.unsplash.com/random/400x300?pc,gaming" class="card-img-top" alt="Gaming PC">
          <div class="card-body">
            <h5 class="card-title">Gaming PCs</h5>
            <p class="card-text">High-end specs for the ultimate gaming experience</p>
            <a href="#" class="btn btn-outline-danger btn-cool">See Details</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-dark text-white border-0 shadow-lg product-card">
          <img src="https://source.unsplash.com/random/400x300?monitor" class="card-img-top" alt="Monitor">
          <div class="card-body">
            <h5 class="card-title">Monitors</h5>
            <p class="card-text">Vivid colors and high refresh rates</p>
            <a href="#" class="btn btn-outline-danger btn-cool">See Details</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-dark text-white border-0 shadow-lg product-card">
          <img src="https://source.unsplash.com/random/400x300?accessories" class="card-img-top" alt="Accessories">
          <div class="card-body">
            <h5 class="card-title">Accessories</h5>
            <p class="card-text">Peripherals for seamless control</p>
            <a href="#" class="btn btn-outline-danger btn-cool">See Details</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Quizzes Section -->
<section id="quizzes" class="py-5" style="background: linear-gradient(180deg, #4a1a1e, #2c0b0e);">
  <div class="container">
    <h2 class="text-center fw-bold mb-5" style="color: #d4a373; text-shadow: 0 0 10px rgba(212, 163, 115, 0.5);">
      <i class="fas fa-scroll me-2"></i>Tech & Gaming Quizzes
    </h2>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card bg-dark text-white border-0 shadow-lg quiz-card">
          <div class="card-body">
            <h5 class="card-title">Tech Trivia Challenge</h5>
            <p class="card-text">Test your knowledge of PC hardware and innovations</p>
            <a href="#" class="btn btn-outline-warning btn-cool">Start Quiz</a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-dark text-white border-0 shadow-lg quiz-card">
          <div class="card-body">
            <h5 class="card-title">Gaming Legends Quiz</h5>
            <p class="card-text">Prove your mastery of gaming history and lore</p>
            <a href="#" class="btn btn-outline-warning btn-cool">Start Quiz</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="py-4 text-center text-white" style="background: #2c0b0e;">
  <p>&copy; 2025 OneHitPoint. Powered by Tech & Passion.</p>
</footer>

<!-- Custom Styles -->
<style>
.hero {
  position: relative;
  overflow: hidden;
}

.hero::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(44, 11, 14, 0.5);
  z-index: 1;
}

.hero .container {
  position: relative;
  z-index: 2;
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

.product-card, .quiz-card {
  transition: all 0.3s ease;
}

.product-card:hover, .quiz-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 20px rgba(212, 163, 115, 0.2);
  border: 1px solid #d4a373;
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

  // Scroll animation for sections
  $(window).scroll(function() {
    $('.product-card, .quiz-card').each(function() {
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