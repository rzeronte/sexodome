<div class="col-md-12">
    <div style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><b>Promoted categories for {{$site->getHost()}}</b></p>
        </div>
    </div>

    <div class="row" style="padding:10px;">
        <form action="{{route('site', ['site_id'=>$site->id, 'locale'=>$locale])}}" method="get">
            <div class="row">
                <div class="col-md-1">
                    <input type="submit" value="search" class="btn btn-primary"/>
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="category_query_string"/>
                </div>
            </div>
            <br/>
        </form>

        <div class="clearfix"></div>

        <div class="col-md-12">
            @foreach ($site->categories()->get() as $category)
                <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                <a href="{{route('removeCategory', ['locale'=>$locale, "site_id"=> $site->id, "category_id"=>$category->id])}}"><i style="color:red" class="fa fa-minus-square"></i>
                </a>
                <small>{{ $translation->name}}</small>
            @endforeach
        </div>
    </div>

    <div class="row" style="padding:10px;">
        @foreach ($categories as $category)
            <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
            @if ($translation)
                <div class="col-md-2">
                    <a href="{{route("addCategory", ['locale'=>$locale, "site_id" => $site->id, "category_id" => $category->id, 'q'=> Request::input("q"), 'page' => Request::input("page")])}}"><i class="fa fa-plus"></i></a>
                    <small>{{$translation->name}}</small>
                </div>
            @endif
        @endforeach

        <div class="clearfix" style="margin-top:20px;"></div>

        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        @endif

        @if (count($categories) == 0)
            <p>This language dont have categories associated.</p>
        @endif

        <?php echo $categories->appends(['q' => $query_string])->render(); ?>
    </div>
</div>
