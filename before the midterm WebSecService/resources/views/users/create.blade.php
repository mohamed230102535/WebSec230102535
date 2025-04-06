@extends('layouts.master')

@section('title', 'Create User')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h2 class="text-center text-primary mb-4">Create User</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
                <div class="invalid-feedback">Please enter a name.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="invalid-feedback">Please enter a password.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Role:</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="guest">Guest</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Bootstrap Form Validation & Password Toggle --}}
<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })();

    // Password Visibility Toggle
    document.getElementById("togglePassword").addEventListener("click", function() {
        var passwordInput = document.getElementById("password");
        var icon = this.querySelector("i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });
</script>

@endsection
