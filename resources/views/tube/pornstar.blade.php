@extends('tube.layouts.app')

@section('seo_title'){{ App::make('sexodomeKernel')->getSite()->getPornstarTitle($pornstar->name) }}@endsection
@section('seo_description'){{ App::make('sexodomeKernel')->getSite()->getPornstarTitle($pornstar->name) }}@endsection

@section('pagination_seo')
    @if (isset($scenes))
        @if ( $scenes->currentPage() > 1)
            <link rel="prev" href="{{  route('pornstar_page', ['profile' => Route::current()->parameter('host'), 'permalinkPornstar'=> $pornstar->permalink,  'page' => $scenes->currentPage() - 1]) }} " />
        @endif

        @if ( $scenes->currentPage() < ($scenes->lastPage()))
            <link rel="next" href="{{ route('pornstar_page', ['profile' => Route::current()->parameter('host'), 'permalinkPornstar'=> $pornstar->permalink,  'page' => $scenes->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @if (isset($pornstar))
        @include('tube.paginators._paginator_pornstar', ['paginator' => $scenes, 'route_name' => 'pornstar_page'])
    @endif
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-stars"></i> {{ App::make('sexodomeKernel')->getSite()->getH2Pornstar($pornstar->name) }}</h2>
@endsection

@section('content')
    @include('tube.commons._header')

    @include('tube.commons._videos')

    @if (App::make('sexodomeKernel')->getSite()->iframe_src != "")
        <section class="container">
            @include('tube.commons._iframe_network')
        </section>
    @endif

@endsection