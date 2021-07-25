@extends('shared.header')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <h3 class="card-header text-center">Change Password</h3>
                        <div class="card-body">
                            <form action="{{ route('password.change') }}" method="post">
                                @csrf
                                <p>Please enter your current password:</p>
                                <div class="form-group mb-3">
                                    <input type="password" placeholder="Password" id="old_password" class="form-control"
                                        name="old_password" required autofocus>
                                    @if ($errors->has('old_password'))
                                        <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                    @endif
                                </div>

                                <p>Please enter your new password:</p>
                                <div class="form-group ">
                                    <input type="password" placeholder="New Password" id="password" class="form-control mb-2"
                                        name="password" required autofocus>
                                    <input type="password" placeholder="Confirm New Password" id="password" class="form-control mb-3" name="password_confirmation" required autofocus>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    @include('shared.captcha')
                                    <button type="submit" class="btn btn-outline-info" name="submit">
                                        Change Password
                                    </button>
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
