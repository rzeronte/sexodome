<section>

    <div class="container">
        <h3 class="alphabetical_categories">{{trans('tube.all_categories')}}</h3>

        <div class="col-md-12 text-left">
            <?php $previous = null; ?>
            @foreach($categoriesAlphabetical as $category)
                <?php $firstLetter = str_slug(substr($category->name, 0, 1))  ?>

                @if($previous !== str_slug(strtoupper($firstLetter)))
                    <?php $previous = str_slug(strtoupper($firstLetter)) ?>
                    @if (is_string($previous) && !is_numeric($previous) && strlen(trim($previous)) > 0)
                        <div class="clearfix"></div>
                        <h3 class="alphabetical_categories text-left">{{$previous}}</h3>
                    @endif
                @endif

                @if (is_string($previous) && !is_numeric($previous) && strlen(trim($previous)) > 0)
                    <div class="col-md-1 text-left">
                        <a class="small alphabetical_category_link text-left" href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}">{{ucwords($category->name)}} ({{$category->nscenes}})</a>
                    </div>
                @endif

            @endforeach
        </div>

    </div>

</section>