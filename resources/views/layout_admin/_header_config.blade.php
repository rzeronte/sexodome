<div class="col-md-10" style="height: 50px; padding-top: 4px;">
    <span style="font-size:22px;color:white; margin-right:10px;">Exporter 1.0</span>
</div>

<div class="col-md-10" style="height: 50px; padding-top: 4px;">
    <small style="color: white;">
        @foreach($languages as $itemLang)
            @if ($itemLang->code != $language->code)
                <a href="{{route('changeLocale', ['locale' => $itemLang->code])}}"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
            @endif
        @endforeach

    </small>
</div>


<div class="col-md-2 text-right" style="color:white; margin: 12px 0 0 0">
</div>

