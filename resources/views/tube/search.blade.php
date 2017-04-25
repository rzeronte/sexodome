@extends('tube.layouts.app')

@section('h2_tag')
    <h2><i class="mdi mdi-home"></i> {{$site->getH2Home()}}</h2>
@endsection

@section('paginator')
    @include('tube.paginators._paginator_search', ['paginator' => $scenes, 'route_name' => 'search_page'])
@endsection

@section('content')
    @include('tube.commons._header')

    @include('tube.commons._videos')

    @if ($language->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif
@endsection

