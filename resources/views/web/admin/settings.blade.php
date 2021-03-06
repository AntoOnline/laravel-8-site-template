@extends('shared.header')
@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <form action="{{ route('web.admin.settings') }}" method="post">
                            @csrf
                            <h3 class="card-header text-center">Welcome!</h3>
                            <div class="card-body">

                                <div class="container">
                                    <div class="row mb-4">
                                        <div class="offset-md-1 col-md-3 text-right">
                                            Use Dark Mode:
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" onclick="changeTheme(this)"
                                                    id="flexSwitchCheckDefault" name="settings[darkmode]"
                                                    {{ ((isset($settings->darkmode) && $settings->darkmode === 'on') || old('settings[darkmode]') === 'on') ? 'checked' : "" }} />
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Dark
                                                    mode</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-md-4">
                                            <button type="submit" name="submit" class="btn btn-primary form-control">
                                                Save
                                            </button>
                                        </div>
                                        @if (isset($success))
                                            <p class="text-success text-right">{{ $success }}</p>
                                        @elseif (isset($faliure))
                                            <p class="text-danger text-right">{{ $faliure }}</p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-md-6">
                    <div class="card">
                        {{-- <h3 class="card-header text-left">Change Password</h3> --}}
                        <div class="card-body">
                            <div class="container text-center">
                                <p class="d-flex align-items-center justify-content-around mb-0">
                                    Change Password:
                                    <a href="{{ route('web.admin.change_password') }}">
                                        <button class="btn btn-outline-info">
                                            Click here to change password
                                        </button>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <h3 class="card-header text-center">Delete Account</h3>
                        <div class="card-body">
                            <div class="container text-center">
                                <p>Click the button below to remove your account. </p>
                                <p>This action cannot be undone.</p>
                                <a href="{{ route('web.admin.delete_account') }}">
                                    <button class="btn btn-outline-warning">
                                        Click here to delete account...
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
