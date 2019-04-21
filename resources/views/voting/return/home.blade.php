@extends('layouts.voting.master')

@section('content')
    @foreach($rounds as $key=>$round)
        @if(!$key%4)
            <div class="row">
                @endif
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <a href="{{route('voting.return.round', $round->id)}}">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{$round->name}}</h3>
                                <p>VDs for {{$round->name}}</p>
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