<div class="col-md-7" style="height: 50px; padding-top: 4px;">
    <small style="color: white;">
        @foreach($languages as $itemLang)
            @if ($itemLang->code != $language->code)
                <a href="{{route('changeLocale', ['locale' => $itemLang->code])}}"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
            @endif
        @endforeach
    </small>
</div>

<div class="col-md-5 text-right" style="color:white; margin: 12px 0 0 0">
    <a href="{{route('sites', ['locale'=>$language->code])}}" class="btn btn-primary"><i class="glyphicon glyphicon-globe"></i> Sites</a>
    <a href="{{route('content', ['locale'=>$language->code])}}" class="btn btn-primary"><i class="glyphicon glyphicon-th"></i> Content</a>
    <a href="{{route('tags', ['locale'=>$language->code])}}" class="btn btn-primary"><i class="glyphicon glyphicon-link"></i> Tags</a>
    <a href="{{route('stats', ['locale'=>$language->code])}}" class="btn btn-primary"><i class="glyphicon glyphicon-sort-by-alphabet"></i> Words counter</a>
</div>

