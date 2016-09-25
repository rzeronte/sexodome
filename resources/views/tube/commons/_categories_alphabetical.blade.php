<section>

    <div class="container">
        <h3 class="alphabetical_categories">{{trans('tube.all_categories')}}</h3>

        <div class="col-md-12 text-left">
            <?php $previous = null; ?>
            @foreach($categoriesAlphabetical as $category)
                <?php $firstLetter = substr($category->name, 0, 1)  ?>

                @if($previous !== strtoupper($firstLetter))
                    <?php $previous = strtoupper($firstLetter) ?>
                    @if (is_string($previous) && !is_numeric($previous) && strlen(trim($previous)) > 0)
                        <div class="clearfix"></div>
                        <h3 class="alphabetical_categories text-left">{{$previous}}</h3>
                    @endif
                @endif

                @if (is_string($previous) && !is_numeric($previous) && strlen(trim($previous)) > 0)
                    <a class="small alphabetical_category_link" href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}">{{ucwords($category->name)}}</a> |
                @endif

            @endforeach
        </div>

    </div>

</section>