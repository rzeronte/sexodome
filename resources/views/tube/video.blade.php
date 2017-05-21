@extends('tube.layouts.app')

@section('content')
    @include('tube.commons._header')

    @include('tube.commons._video')

    @if ($sexodomeKernel->getSite()->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

@endsection