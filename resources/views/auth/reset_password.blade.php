@extends('dashboard')

@section('content')
<main class="signup-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header text-center">Password Reset</h3>
                    <div class="card-body">

                        <form action="{{ route('forgot.password') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email_address"><sup>Enter your email to reset your password:</sup></label>
                                <input type="text" placeholder="Email" id="email_address" class="form-control"
                                    name="email" required autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="d-grid mx-auto">
                                @include('shared.captcha')
                                <button type="submit" class="btn btn-dark btn-block">Send Reset Link</button>
                                @if ($errors->has('recaptcha'))
                                <span class="text-danger">{{ $errors->first('recaptcha') }}</span>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
