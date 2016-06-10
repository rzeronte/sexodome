<div class="row">

    <div class="col-md-8">
        <a href="{{route('home_website')}}"><img src="{{ asset('images/logo.png') }}" style="margin-top:5px;margin-left:10px;"></a>
        <a href="{{route('sites',   ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "sites") btn-success @else btn-primary @endif" style="margin-top:10px;"><i class="glyphicon glyphicon-globe"></i> Sites</a>
        <a href="{{route('works',   ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "works") btn-success @else btn-primary @endif" style="margin-top:10px;"><i class="glyphicon glyphicon-cog"></i> Works</a>
        <a href="{{route('feeds',   ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "feeds") btn-success @else btn-primary @endif" style="margin-top:10px;"><i class="glyphicon glyphicon-open-file"></i> Channels</a>
        <a href="{{route('content', ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "content") btn-success @else btn-primary @endif" style="margin-top:10px;"><i class="glyphicon glyphicon-th"></i> Scenes</a>
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