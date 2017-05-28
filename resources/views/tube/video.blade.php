@extends('tube.layouts.app')

@section('seo_title'){{ App::make('sexodomeKernel')->getSite()->getSceneTitle($video) }}@endsection
@section('seo_description'){{ App::make('sexodomeKernel')->getSite()->getSceneDescription($video) }}@endsection

@section('content')
    @include('tube.commons._header')

    @include('tube.commons._video')

    @if (App::make('sexodomeKernel')->getSite()->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

@endsection