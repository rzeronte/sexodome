<!DOCTYPE html>
<html>

@include('panel._head')

<body>

    <div class="container">
        <div class="header row">
            @include('panel._header_config')
        </div>

        <div class="row">

            <div class="col-md-12">
                <div style="margin-top:20px;">
                    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                        <p><b>Promoted categories for {{$site->domain}}</b></p>
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


            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><b>SEO for {{$site->domain}}</b></p>
                </div>
            </div>

            <form method="post" action="{{route('site', ['locale'=>$locale, 'site_id'=>$site->id])}}">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                <div class="col-md-12">
                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Title Index:
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->title_index}}" name="title_index">
                            <small><b>Variables:</b> {domain}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Description Index:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->description_index}}" name="description_index">
                            <small><b>Variables:</b> {domain}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Title Category:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->title_category}}" name="title_category">
                            <small><b>Variables:</b> {domain}, {category}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Description Category:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->description_category}}" name="description_category">
                            <small><b>Variables:</b> {domain}, {category}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Title Tag:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->title_tag}}" name="title_tag">
                            <small><b>Variables:</b> {domain}, {tag}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Description Tag:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->description_tag}}" name="description_tag">
                            <small><b>Variables:</b> {domain}, {tag}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Title Top Scenes:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->title_topscenes}}" name="title_topscenes">
                            <small><b>Variables:</b> {domain}</small>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Description Top Scenes:
                        </div>

                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->description_topscenes}}" name="description_topscenes">
                            <small><b>Variables:</b> {domain}</small>
                        </div>
                    </div>


                    <div style="margin-top:20px;">
                        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                            <p><b>Main config for {{$site->domain}}</b></p>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Domain:
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->domain}}" name="domain" onfocus="blur()">
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            Billboard Text:
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="{{$site->head_billboard}}" name="head_billboard">
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-3">
                            GAnalytics:
                        </div>
                        <div class="col-md-7">
                            <textarea name="google_analytics" class="form-control">{{$site->google_analytics}}</textarea>
                        </div>
                    </div>

                    <div class="row" style="padding:10px;">
                        <div class="col-md-7 col-md-offset-3">
                            <input type="submit" class="btn btn-primary" value="Save settings for {{$site->domain}}" style="width:100%;"/>
                        </div>
                    </div>

                </div>
            </form>

        </div>

        <div style="border-top: solid 1px darkorange;margin-top:20px;">
            <p class="text-right">panel v.0.16</p>
        </div>

    </div>
</body>
</html>
