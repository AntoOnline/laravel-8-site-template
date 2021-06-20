@extends('dashboard')

@section('content')
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header text-center">Update Password</h3>
                    <div class="card-body">
                        <form method="POST" action="{{ route('change.password') }}">
                            @csrf
                            <input type="hidden" value="{{old("password_token", $password_token)}}" name="password_token" id="password_token" >

                            <div class="form-group mb-3">
                                <input type="password" placeholder="New Password" id="password" class="form-control" name="password" required value="{{ old('password') }}">
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="Confirm Password" id="password_confirmation" class="form-control" name="password_confirmation" required value="{{ old('password') }}">
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                @if ($errors->has('error'))
                                <span class="text-danger">{{ $errors->first('error') }}</span>
                                @endif
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">Change Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection