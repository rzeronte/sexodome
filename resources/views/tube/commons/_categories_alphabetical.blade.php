<section style="margin-bottom:20px;">

    <div class="container">
        <div class="col-md-12">
            <h3 class="alphabetical_categories"><i class="glyphicon glyphicon-th"></i> {{ App::make('sexodomeKernel')->getSite()->categories_h3 }}</h3>
        </div>

        <div class="col-md-12 text-left">
            <?php $previous = null; ?>
            @foreach($categoriesAlphabetical as $category)
                <div class="col-md-2 col-xs-6 text-left alphabetical_category_link">
                    <a class="text-left cat_text_link" href="{{route('category', ['profile' => Route::current()->parameter('host'), 'permalink'=>str_slug($category->name)])}}" title="{{ucwords($category->name)}}">{{str_limit(ucwords($category->name), $limit = 15, $end = '...')}} ({{$category->nscenes}})</a>
                </div>
            @endforeach
        </div>

    </div>

</section>

<style>
    .alphabetical_category_link{
        margin-top: 10px;
    }
    .cat_text_link{
        font-size: 14px;
        margin-top: 5px;
    }
</style>