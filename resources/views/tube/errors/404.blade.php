@extends('tube.layouts.app')

@section('content')
    @include('tube.commons._header')
    <div class="container">
       <div class="col-md-12">
           <h2>{{trans('tube.404_h2')}} :_(</h2>
       </div>
    </div>

    @include('tube.commons._videos')

    @if (App::make('sexodomeKernel')->getSite()->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

@endsection