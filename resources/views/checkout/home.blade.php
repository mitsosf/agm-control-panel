@extends('layouts.checkout.master')

@section('content')
    @foreach($hotels as $key=>$hotel)
        @if($key == 0)
            <div class="row">

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <a href="{{route('checkout.hotel', $hotel)}}">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>All Hotels</h3>
                                <p>{{$checkedOut.'/'.$checkedInCount[$hotel->id]}}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

    @endforeach
@endsection