@extends('dashboard')

@section('content')

<div class="row">

    @foreach($data as $data)
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 col-xxl-6">
        <div class="small-box {{$data->color}}">
            <div class="inner">
                <h3><a href="/{{$data->route}}" class=" text-white text-decoration-none">{{$data->name}}</a></h3>
                <p>{{$data->comment}}</p>
            </div>
            <div class="icon">
                <i class="{{$data->icon}}"></i>
            </div>

            <div class="small-box-footer text-right p-1 pr-3">
                @if($data->chargeModel =="p")
                Paid
                @else
                Free
                @endif
                | 
                <a href="/{{$data->route}}" class="text-white">
                    Open <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
    @endforeach


</div>


@endsection