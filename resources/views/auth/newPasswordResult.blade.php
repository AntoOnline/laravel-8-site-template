@extends('dashboard')

@section('content')
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <h3 class="card-header text-center">Update Password</h3>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            {{$message}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection