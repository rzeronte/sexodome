<div style="min-height:50px;">
    <div class="col-md-2">
       <a href="{{route('sites', ['locale'=>$locale])}}"> <img src="{{asset('images/logo.png')}}" style="width:100%;margin-top: 10px;"></a>
    </div>

    <div class="col-md-3">
        <div style="margin-top:10px;" data-width="100%">
            @include('panel._selector_site')
        </div>
    </div>

    <div class="col-md-2">
        <div class="row">
            <div class="col-xs-12" style="margin-top:10px;">
                <select id="selector_language" class="selectpicker form-control show-tick" data-width="100%" data-style="btn-primary">
                    @foreach($languages as $itemLang)
                        @if ($itemLang->code != $language->code)
                            <option data-action="{{route('changeLocale', ['locale' => $itemLang->code])}}" data-content="<small><img src='{{asset("flags/$itemLang->code.png")}}'/> {{$itemLang->name}}</small>"></option>
                        @else
                            <option data-action="{{route('changeLocale', ['locale' => $itemLang->code])}}" data-content="<small><img src='{{asset("flags/$itemLang->code.png")}}'/> {{$itemLang->name}}</small>" selected></option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="col-md-4" style="margin-top:10px;">
        <a href="{{route('content', ['locale'=>$language->code])}}" class="btn @if (\Request::route()->getName() == "content") btn-success @else btn-primary @endif"><i class="glyphicon glyphicon-th"></i> Scenes</a>
        <a href="{{route('addSite', ['locale'=>$locale])}}" class="btn btn-warning" ><i class="glyphicon glyphicon-plus-sign"></i> Add site </a>
        <a href="#" class="btn btn-success">
            <i class="glyphicon glyphicon-user"></i>
        </a>

    </div>


    <div class="col-md-1"  style="margin-top:10px;">
        <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-off"></i></a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
    </div>
</div>