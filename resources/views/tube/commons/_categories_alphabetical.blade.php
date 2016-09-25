<section>

    <div class="container">
        <h3 class="alphabetical_categories">{{trans('tube.all_categories')}}</h3>

        <div class="col-md-12 text-center">
            <?php $previous = null; ?>
            @foreach($categoriesAlphabetical as $category)
                <?php $firstLetter = substr($category->name, 0, 1)  ?>

                @if($previous !== strtoupper($firstLetter))
                    <?php $previous = strtoupper($firstLetter) ?>
                    @if (is_string($previous))
                        <div class="col-md-1 alphabetical_letter">{{$previous}}</div>
                        @endif
                @endif

                @if (is_string($previous))
                    <div class="col-md-1 alphabetical_category_link"><a class="small" href="{{route('category', ['profile' => $profile, 'permalink'=>str_slug($category->name)])}}">{{ucwords($category->name)}}</a></div>
                @endif

            @endforeach
        </div>

    </div>

</section>