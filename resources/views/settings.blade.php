@extends('dashboard')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-5">
                        <form action="{{ route('save.settings') }}" method="post">
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
                                                <input class="form-check-input" onchange="changeTheme(this)" type="checkbox" id="flexSwitchCheckDefault"
                                                    name="settings[darkmode]"
                                                    @if(isset($settings->darkmode) && $settings->darkmode === 'on')
                                                        checked
                                                    @endif />
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Dark
                                                    mode</label>
                                            </div>
                                            <script>
                                                function changeTheme(el){
                                                    if(el.checked){
                                                        console.log('foo')
                                                        document.getElementsByTagName('body')[0].classList.add("darkmode-on");
                                                    }else{
                                                        document.getElementsByTagName('body')[0].classList.remove("darkmode-on");
                                                    }
                                                }
                                            </script>

                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-md-4">
                                            <button type="submit" name="submit" class="btn btn-primary form-control">
                                                Save
                                            </button>
                                        </div>
                                        @if (isset($success))
                                            <span class="text-success">{{ $success }}</span>
                                        @elseif (isset($faliure))
                                            <span class="text-danger">{{ $faliure }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-5">
                        <div class="card">
                            <h3 class="card-header text-center">Delete Account</h3>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="offset-md-1 col-md-3">
                                            <p>Delete account:</p>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('delete-account') }}" method="POST">
                                                @csrf
                                                <input class="btn btn-outline-warning" type="submit" name="delete_account"
                                                    value="Click here to delete account...">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
        </div>
    </main>
@endsection
