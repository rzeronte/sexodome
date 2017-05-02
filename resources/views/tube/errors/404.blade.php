@extends('tube.layouts.app')

@section('content')
    @include('tube.commons._header')
    <div class="row">
       <div class="col-lg-offset-5">
           <h2>404 :(</h2>
       </div>
    </div>

    @include('tube.commons._videos')

    @if ($language->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

@endsection