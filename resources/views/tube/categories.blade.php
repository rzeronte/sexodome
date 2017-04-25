@section('pagination_seo')
    @if (isset($categories))
        @if ( $categories->currentPage() > 1)
            <link rel="prev" href="{{  route('categories_page', ['profile' => $profile, 'page' => $categories->currentPage() - 1]) }} " />
        @endif

        @if ( $categories->currentPage() < ($categories->lastPage()))
            <link rel="next" href="{{ route('categories_page', ['profile' => $profile, 'page' => $categories->currentPage() + 1])}}" />
        @endif
    @endif
@endsection

<!DOCTYPE html>
<html>
    <head>
        @include('tube.commons._head')
    </head>


    <body class="homepage">
        @include('tube.commons._header')
        @include('tube.commons._categories')
        @include('tube.commons._iframe_network')
        @include('tube.commons._footer')
        @include('tube.commons._javascripts')
    </body>
</html>