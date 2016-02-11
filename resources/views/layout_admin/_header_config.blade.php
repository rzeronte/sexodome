<div class="col-md-9" style="height: 50px; padding-top: 4px;">
    <small style="color: white;">
        @foreach($languages as $itemLang)
            @if ($itemLang->code != $language->code)
                <a href="{{route('changeLocale', ['locale' => $itemLang->code])}}"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
            @endif
        @endforeach
    </small>
</div>


<div class="col-md-3 text-right" style="color:white; margin: 12px 0 0 0">
    <a href="{{route('content', ['locale'=>$language->code])}}" class="btn btn-primary">Content</a>
    <a href="{{route('tags', ['locale'=>$language->code])}}" class="btn btn-primary">Tags</a>
    <a href="{{route('stats', ['locale'=>$language->code])}}" class="btn btn-primary">Stats</a>
</div>

