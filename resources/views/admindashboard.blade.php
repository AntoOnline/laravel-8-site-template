@extends('dashboard')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <h3 class="card-header text-center">Welcome!</h3>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="offset-md-1 col-md-3">
                                        <p>Name:</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ ucfirst(auth()->user()->name) }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-md-1 col-md-3">
                                        <p>Email:</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-md-1 col-md-3">
                                        <p>Event log:</p>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('event-log') }}" method="POST">
                                            @csrf
                                            <button type="submit" name="submit" class="btn btn-primary form-control">
                                                View Event Log
                                            </button>
                                        </form>
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
