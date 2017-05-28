@extends('tube.layouts.app')

@section('seo_title'){{ $sexodomeKernel->getSite()->getPornstarsTitle($page) }}@endsection
@section('seo_description'){{ $sexodomeKernel->getSite()->getPornstarsTitle($page) }}@endsection

@section('pagination_seo')
    @if (isset($pornstars))
        @if ( $pornstars->currentPage() > 1)
            <link rel="prev" href="{{  route('pornstars_page', ['profile' => Route::current()->parameter('host'), 'page' => $pornstars->currentPage() - 1]) }} " />
        @endif

        @if ( $pornstars->currentPage() < ($pornstars->lastPage()))
            <link rel="next" href="{{ route('pornstars_page', ['profile' => Route::current()->parameter('host'), 'page' => $pornstars->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @include('tube.paginators._paginator_pornstars', ['paginator' => $pornstars, 'route_name' => 'pornstars_page'])
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-stars"></i> {{ $sexodomeKernel->getSite()->getH2Pornstars() }}</h2>
@endsection

@section('content')
    @include('tube.commons._header')
    @include('tube.commons._pornstars')
@endsection