<div class="row">

    <div class="col-md-5">
        <div style="margin-top:10px;" data-width="100%">
            @include('panel._selector_site')
        </div>
    </div>

    <div class="col-md-3">
        <a href="{{route('content', ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "content") btn-success @else btn-primary @endif" style="margin-top:10px;"><i class="glyphicon glyphicon-th"></i> Content panel</a>
        <a href="{{route('addSite', ['locale'=>$locale])}}" class="btn btn-warning" style="margin-top:10px;"><i class="glyphicon glyphicon-plus-sign"></i> Add site </a>
    </div>


    <div class="col-md-4 text-right" style="height: 50px; padding-top: 12px;">
        <a href="{{route('changeLocale', ['locale' => $language->code])}}"><img src="{{asset("flags/$language->code.png")}}" style="width:20px;border: solid 1px black;"/></a>
        |
        <small style="color: white;">
            @foreach($languages as $itemLang)
                @if ($itemLang->code != $language->code)
                    <a href="{{route('changeLocale', ['locale' => $itemLang->code])}}"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
                @endif
            @endforeach
        </small> |
        <small style="color: black"><i class="glyphicon glyphicon-user"></i> <b>{{Auth::user()->name}} ({{$language->code}})</b> </small>
        <a href="{{route('logout')}}" class="btn btn-danger"><i class="glyphicon glyphicon-off"></i> Logout</a>
    </div>

</div>