@extends('shared.header')

@section('content')
    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <h3 class="card-header text-center">Event Log</h3>
                        <div class="card-body">
                            <div class="container">
                                <p>
                                    @empty($events)
                                        No events to show...
                                    @endempty
                                    @foreach ($events as $event)
                                        [{{ $event->created_at }}]: {{ Str::ucfirst($event->event_type->name) }}<br>
                                    @endforeach
                                </p>
                                {{$events->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
