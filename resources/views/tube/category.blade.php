@extends('tube.layouts.app')

@section('seo_title'){{ App::make('sexodomeKernel')->getSite()->getCategoryTitle($categoryTranslation->name, $page) }}@endsection
@section('seo_description'){{ App::make('sexodomeKernel')->getSite()->getCategoryDescription($categoryTranslation->name) }}@endsection

@section('pagination_seo')
    @if (isset($scenes))
        @if ( $scenes->currentPage() > 1)
            <link rel="prev" href="{{  route('category_page', ['profile' => Route::current()->parameter('host'), 'permalinkCategory'=> $categoryTranslation->permalink,  'page' => $scenes->currentPage() - 1]) }} " />
        @endif

        @if ( $scenes->currentPage() < ($scenes->lastPage()))
            <link rel="next" href="{{ route('category_page', ['profile' => Route::current()->parameter('host'), 'permalinkCategory'=> $categoryTranslation->permalink,  'page' => $scenes->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

@section('paginator')
    @include('tube.paginators._paginator_category', ['paginator' => $scenes, 'route_name' => 'category_page'])
@endsection

@section('h2_tag')
    <h2><i class="mdi mdi-home"></i> {{App::make('sexodomeKernel')->getSite()->getH2Category($categoryTranslation->name)}}</h2>
@endsection

@section('orders')
    @if (isset($categoryTranslation))
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            <a href="{{route('category', ['profile' => App::make('sexodomeKernel')->getSite()->getHost(), 'permalink' => $permalinkCategory, 'order' => 'popular'])}}" rel="nofollow" class="btn btn-secondary btn-sm active link_order"> @if (Request::get('order') == 'popular') <b>{{trans('tube.btn_order_mostpopular')}}</b> @else {{trans('tube.btn_order_mostpopular')}} @endif </a>
            <a href="{{route('category', ['profile' => App::make('sexodomeKernel')->getSite()->getHost(), 'permalink' => $permalinkCategory, 'order' => 'newest'])}}" rel="nofollow" class="btn btn-secondary btn-sm active link_order">@if (Request::get('order') == false || Request::get('order') == 'newest') <b>{{trans('tube.btn_order_news')}}</b> @else {{trans('tube.btn_order_news')}} @endif </a>
        </div>
    @else
        <div class="link_order_container">
            <a href="{{route('categories', ['order' => 'latest'])}}" rel="nofollow" class="btn btn-secondary btn-sm active link_order">Latest porn videos</a>
            <a href="{{route('categories', ['order' => 'newest'])}}" rel="nofollow" class="btn btn-secondary btn-sm active link_order">Newest porn videos</a>
        </div>
    @endif
@endsection

@section('content')
    @include('tube.commons._header')
    @include('tube.commons._videos')
    @include('tube.commons._iframe_network')

@endsection