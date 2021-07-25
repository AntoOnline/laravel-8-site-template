@extends('shared.header')

@section('content')
<main class="signup-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    {{-- Not escaping head and message since it might be HTML at times. --}}
                    <h3 class="card-header text-center">{!! $head !!}</h3>
                    <div class="card-body">
                        @csrf
                        <div class="form-group mb-3">
                            {!! $message !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
