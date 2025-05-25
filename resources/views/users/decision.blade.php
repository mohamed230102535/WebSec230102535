@extends('layouts.master')
@section('title', 'Microsoft Account Decision')
@section('content')
<div class="d-flex justify-content-center">
    <div class="card m-4 col-sm-6">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Microsoft Account</h4>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('ms_user'))
                <p class="text-center mb-4">
                    Welcome {{ session('ms_user')['displayName'] }}!<br>
                    What would you like to do?
                </p>

                <div class="d-grid gap-3">
                    <form action="{{ route('msgraph.login') }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Login with Existing Account
                        </button>
                    </form>

                    <form action="{{ route('msgraph.register') }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Create New Account
                        </button>
                    </form>
                </div>
            @else
                <div class="alert alert-danger">
                    No Microsoft account information found. Please try logging in again.
                </div>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
