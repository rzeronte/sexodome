<div class="container">
    <div class="col-md-2 col-xs-12 col-sm-12 header_col_logo">
        <a href="{{route('index', ['profile' => $profile])}}" title="{{$language->title}}">
            @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="max-height: 50px;"/>
            @else
                {{$site->domain}}
            @endif
        </a>
    </div>

    <div class="col-md-4 col-xs-12 col-sm-12 header_col_billboard">
        <h1 class="text-center"><?php if (isset($tagTranslation)):?>{{$tagTranslation->name}} | <?php endif?>{{$language->head_billboard}}</h1>
    </div>

    <div class="col-md-3 header_query_string" style="text-align: right; margin-top:15px;">
        <form action="{{ route('search', ['profile' => $profile]) }}" method="get">
            <div class="input-group">
                <input id="query_string" name="q" type="text" placeholder="@if (isset($scenes)){{ number_format($scenes->total(), 0, ".", ",") }} videos @endif" class="form-control input_search" value="{{$query_string}}">
                <span class="input-group-btn">
                    <button type="submit" class="btn"><i class="glyphicon glyphicon-search"></i> search</button>
                </span>
            </div>
        </form>
    </div>

    <div class="col-md-3 col-xs-12 col-sm-12 header_menu" style="text-align: right; margin-top:15px;">
        <a href="{{route('topscenes', ['profile' => $profile])}}" class="btn btn-primary "><span class="glyphicon glyphicon-cloud-upload"></span> Top Videos</a>
        <a href="{{route('categories', ['profile' => $profile])}}" class="btn btn-primary"><span class="glyphicon glyphicon-tags"></span> Categories</a>
    </div>

</div>