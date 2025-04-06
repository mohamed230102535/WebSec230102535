@extends("layouts.master2")
@section("title", "Create Quiz - OneHitPoint")
@section("content")

<!-- Header Section -->
<section class="py-5 text-white" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e);">
  <div class="container text-center">
    <h1 class="display-4 fw-bold animate__animated animate__fadeInDown" style="color: #d4a373; text-shadow: 0 0 15px rgba(212, 163, 115, 0.7);">
      <i class="fas fa-plus-circle me-2"></i>Create Quiz
    </h1>
    <p class="lead" style="color: #ffffff;">
      Craft Your OneHitPoint Challenge
    </p>
  </div>
</section>

<!-- Create Form Section -->
<section class="py-5" style="background: #1a1a1a; min-height: calc(100vh - 200px);">
  <div class="container">
    <div class="card bg-dark text-white border-0 shadow-lg animate__animated animate__fadeIn">
      <div class="card-body">
        <!-- Quiz Create Form -->
        <form action="{{ route('quiz.store') }}" method="POST">
          @csrf

          <!-- Quiz Title -->
          <div class="mb-4">
            <label for="title" class="form-label text-gold">Quiz Title:</label>
            <input type="text" name="title" id="title" class="form-control bg-dark text-white border-danger" required>
            @error('title')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <!-- Quiz Description -->
          <div class="mb-4">
            <label for="description" class="form-label text-gold">Description:</label>
            <textarea name="description" id="description" class="form-control bg-dark text-white border-danger" rows="3"></textarea>
            @error('description')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <!-- Questions Section -->
          <h3 class="text-gold mb-3">Questions</h3>
          <div id="questions">
            <div class="question mb-4 p-3 bg-secondary rounded animate__animated animate__fadeInUp">
              <label class="form-label text-gold">Question:</label>
              <input type="text" name="questions[0][question_text]" class="form-control bg-dark text-white border-danger" required>
              @error('questions.0.question_text')
                <span class="text-danger">{{ $message }}</span>
              @enderror

              <h4 class="text-gold mt-3">Choices:</h4>
              <div class="choices">
                <div class="choice-item mb-2">
                  <div class="input-group">
                    <input type="text" name="questions[0][choices][0][choice_text]" class="form-control bg-dark text-white border-danger" required>
                    <input type="radio" name="questions[0][correct_choice]" value="0" class="form-check-input ms-2" required>
                    <label class="form-check-label text-muted ms-2">Correct</label>
                  </div>
                </div>
                <div class="choice-item mb-2">
                  <div class="input-group">
                    <input type="text" name="questions[0][choices][1][choice_text]" class="form-control bg-dark text-white border-danger" required>
                    <input type="radio" name="questions[0][correct_choice]" value="1" class="form-check-input ms-2" required>
                    <label class="form-check-label text-muted ms-2">Correct</label>
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-outline-warning btn-add-choice mt-2" onclick="addChoice(this, 0)">Add Choice</button>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-gold btn-cool me-2" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-red btn-cool">
              <i class="fas fa-save me-2"></i>Create Quiz
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

/* Buttons */
.btn-red, .btn-outline-gold {
  font-weight: bold;
  border-radius: 8px;
  padding: 12px 25px;
  transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
  position: relative;
  overflow: hidden;
}
.btn-red {
  background: #d71818; /* Japanese red */
  color: #ffffff;
  border: none;
  box-shadow: 0 5px 15px rgba(215, 24, 24, 0.4); /* Red glow */
}
.btn-red:hover {
  background: #b31414; /* Darker red */
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(215, 24, 24, 0.6);
}
.btn-outline-gold {
  border-color: #d4a373; /* Gold border */
  color: #d4a373;
}
.btn-outline-gold:hover {
  background: #d4a373; /* Gold fill */
  color: #2c0b0e;
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(212, 163, 115, 0.4);
}
.btn-outline-warning {
  border-color: #d4a373;
  color: #d4a373;
}
.btn-outline-warning:hover {
  background: #d4a373;
  color: #2c0b0e;
}
.btn-red::after, .btn-outline-gold::after {
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
.btn-red:active::after, .btn-outline-gold:active::after {
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let questionIndex = 1;
const MAX_QUESTIONS = 10;
const MAX_CHOICES = 4;

// Function to add a new question block
function addQuestion() {
    if (questionIndex >= MAX_QUESTIONS) {
        alert('Maximum limit of ' + MAX_QUESTIONS + ' questions reached!');
        return;
    }
    
    let questionsDiv = $('#questions');
    let newQuestion = `
        <div class="question mb-4 p-3 bg-secondary rounded animate__animated animate__fadeInUp">
            <label class="form-label text-gold">Question:</label>
            <input type="text" name="questions[${questionIndex}][question_text]" class="form-control bg-dark text-white border-danger" required>
            
            <h4 class="text-gold mt-3">Choices:</h4>
            <div class="choices">
                <div class="choice-item mb-2">
                    <div class="input-group">
                        <input type="text" name="questions[${questionIndex}][choices][0][choice_text]" class="form-control bg-dark text-white border-danger" required>
                        <input type="radio" name="questions[${questionIndex}][correct_choice]" value="0" class="form-check-input ms-2" required>
                        <label class="form-check-label text-muted ms-2">Correct</label>
                    </div>
                </div>
                <div class="choice-item mb-2">
                    <div class="input-group">
                        <input type="text" name="questions[${questionIndex}][choices][1][choice_text]" class="form-control bg-dark text-white border-danger" required>
                        <input type="radio" name="questions[${questionIndex}][correct_choice]" value="1" class="form-check-input ms-2" required>
                        <label class="form-check-label text-muted ms-2">Correct</label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-warning btn-add-choice mt-2" onclick="addChoice(this, ${questionIndex})">Add Choice</button>
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeQuestion(this)">Remove Question</button>
        </div>
    `;
    
    questionsDiv.append(newQuestion);
    questionIndex++;
}

// Function to add a new choice
function addChoice(button, qIndex) {
    let choicesDiv = $(button).siblings('.choices');
    let choiceCount = choicesDiv.find('.choice-item').length;
    
    if (choiceCount >= MAX_CHOICES) {
        alert('Maximum limit of ' + MAX_CHOICES + ' choices per question reached!');
        return;
    }
    
    let newChoice = `
        <div class="choice-item mb-2">
            <div class="input-group">
                <input type="text" name="questions[${qIndex}][choices][${choiceCount}][choice_text]" class="form-control bg-dark text-white border-danger" required>
                <input type="radio" name="questions[${qIndex}][correct_choice]" value="${choiceCount}" class="form-check-input ms-2" required>
                <label class="form-check-label text-muted ms-2">Correct</label>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeChoice(this)">X</button>
            </div>
        </div>
    `;

    choicesDiv.append(newChoice);
}

// Function to remove a question block
function removeQuestion(button) {
    $(button).closest('.question').remove();
}

// Function to remove a choice option
function removeChoice(button) {
    $(button).closest('.choice-item').remove();
}

// Verify jQuery is loaded
$(document).ready(function() {
    console.log('jQuery loaded and document ready');
});
</script>

@endsection