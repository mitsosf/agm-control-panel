@extends('layouts.checkin.master')

@section('content')
    @foreach($hotels as $key=>$hotel)
        @if(!$key%4)
            <div class="row">
                @endif
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <a href="{{route('checkin.hotel', $hotel)}}">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$hotel->name}}</h3>
                                <p>{{$checkedInCount[$hotel->id].'/'.$residentsCount[$hotel->id]}}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
                @if(!$key%4)
                    <div>
        @endif

    @endforeach
@endsection