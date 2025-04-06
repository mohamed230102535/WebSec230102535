@extends('layouts.master2')
@section('title', 'Edit User')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #2c2c2c 0%, #1f1f1f 100%); border: 1px solid #d4a373;">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #2c0b0e, #4a1a1e); border-bottom: 2px solid #d71818;">
                    <h5 class="mb-0" style="color: #d4a373; text-shadow: 0 0 10px rgba(212, 163, 115, 0.7);">
                        <i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->name }}
                    </h5>
                    <a href="{{ route('WebAuthentication.dashboard') }}" class="btn btn-sm btn-outline-danger btn-cool" title="Back to Dashboard">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('WebAuthentication.updateUser', $user->id) }}">
                        @csrf
                       <!-- Added for proper update routing -->

                        <div class="mb-4">
                            <label for="name" class="form-label text-gold fw-bold">Name</label>
                            <input type="text" class="form-control glowing-input @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label text-gold fw-bold">Email</label>
                            <input type="email" class="form-control glowing-input @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-gold fw-bold">Password (Leave blank to keep current)</label>
                            <input type="password" class="form-control glowing-input @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="roles" class="form-label text-gold fw-bold">Roles</label>
                            <select multiple class="form-select glowing-input @error('roles') is-invalid @enderror" name="roles[]">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->hasRole('Admin'))
                            <div class="mb-4">
                                <label for="permissions" class="form-label text-gold fw-bold">Direct Permissions</label>
                                <select multiple class="form-select glowing-input @error('permissions') is-invalid @enderror" name="permissions[]">
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->name }}" {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('permissions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-cool glowing-btn" style="background: linear-gradient(45deg, #d71818, #b31414); color: #ffffff;">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
/* Cool Theme Enhancements */
.text-gold {
    color: #d4a373;
    text-shadow: 0 0 5px rgba(212, 163, 115, 0.5);
}

/* Glowing Input */
.glowing-input {
    background: #2c2c2c;
    border: 2px solid #d71818;
    color: #ffffff;
    transition: all 0.3s ease;
    border-radius: 8px;
    padding: 10px;
}
.glowing-input:focus {
    border-color: #d4a373;
    box-shadow: 0 0 15px rgba(212, 163, 115, 0.5), inset 0 0 5px rgba(212, 163, 115, 0.3);
    background: #3a3a3a;
}

/* Invalid Input */
.glowing-input.is-invalid {
    border-color: #d71818;
}
.glowing-input.is-invalid:focus {
    box-shadow: 0 0 15px rgba(215, 24, 24, 0.5);
}

/* Cool Buttons */
.btn-cool {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    font-weight: bold;
    border-radius: 8px;
    padding: 12px 20px;
}
.btn-cool:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 163, 115, 0.5);
}

/* Glowing Button */
.glowing-btn {
    border: 2px solid #d71818;
}
.glowing-btn:hover {
    border-color: #d4a373;
    box-shadow: 0 0 20px rgba(215, 24, 24, 0.7), inset 0 0 10px rgba(212, 163, 115, 0.3);
}

/* Outline Button */
.btn-outline-danger {
    border-color: #d71818;
    color: #d71818;
}
.btn-outline-danger:hover {
    background: #d71818;
    color: #ffffff;
    box-shadow: 0 0 15px rgba(215, 24, 24, 0.5);
}

/* Card Styling */
.card {
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(212, 163, 115, 0.2);
}

/* Invalid Feedback */
.invalid-feedback {
    color: #d71818;
    text-shadow: 0 0 5px rgba(215, 24, 24, 0.3);
}

/* Multi-select Styling */
.form-select {
    background: #2c2c2c;
    border: 2px solid #d71818;
    color: #ffffff;
}
.form-select:focus {
    border-color: #d4a373;
    box-shadow: 0 0 15px rgba(212, 163, 115, 0.5);
}
.form-select option {
    background: #3a3a3a;
    color: #ffffff;
}
</style>

<!-- Scripts -->

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
                background: 'rgba(212, 163, 115, 0.3)', /* Gold ripple */
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