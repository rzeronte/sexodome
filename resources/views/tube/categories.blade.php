@extends('tube.layouts.app')

@section('seo_title'){{ App::make('sexodomeKernel')->getSite()->getCategoriesTitle($page) }}@endsection
@section('seo_description'){{ App::make('sexodomeKernel')->getSite()->getCategoriesDescription() }}@endsection

@section('pagination_seo')
    @if (isset($categories))
        @if ( $categories->currentPage() > 1)
            <link rel="prev" href="{{  route('categories_page', ['profile' => App::make('sexodomeKernel')->getSite()->domain, 'page' => $categories->currentPage() - 1]) }} " />
        @endif

        @if ( $categories->currentPage() < ($categories->lastPage()))
            <link rel="next" href="{{ route('categories_page', ['profile' => App::make('sexodomeKernel')->getSite()->domain, 'page' => $categories->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @include('tube.paginators._paginator_categories', ['paginator' => $categories, 'route_name' => 'categories_page'])
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-home"></i> {{App::make('sexodomeKernel')->getSite()->getH2Home()}}</h2>
@endsection

@section('content')
    @include('tube.commons._header')
    @include('tube.commons._categories')
    @include('tube.commons._categories_alphabetical')
    @include('tube.commons._iframe_network')
@endsection
