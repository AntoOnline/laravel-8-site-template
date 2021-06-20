@extends('dashboard')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <h3 class="card-header text-center">Account deleted!</h3>
                        <div class="card-body">
                            @if ($deleted)
                                You account has been deleted successfully. <a href="{{ route('home') }}">Click here</a> to
                                return to home screen.
                            @else
                                An unknown error has occoured. Please try later. <br><a href="{{ route('home') }}">Click
                                    here</a> to return to home screen.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
