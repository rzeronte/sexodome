<div style="min-height:50px;">
    <div class="col-md-2">
       <a href="{{route('sites', [])}}"> <img src="{{asset('images/logo.png')}}" style="width:100%;margin-top: 10px;"></a>
    </div>

    <div class="col-md-3">
        <div style="margin-top:10px;" data-width="100%">
            @include('panel._selector_site')
        </div>
    </div>

    <div class="col-md-4" style="margin-top:10px;">
        @if (isset($site) and Request::route()->getName() != 'content')
            <a href="{{route('content', ['site_id' => $site->id])}}" class="btn @if (\Request::route()->getName() == "content") btn-success @else btn-primary @endif"><i class="glyphicon glyphicon-th"></i> Scenes</a>
        @endif
        @if (isset($site) and Request::route()->getName() == 'content')
            <a href="{{route('site', ['site_id' => $site->id])}}" class="btn @if (\Request::route()->getName() == "site") btn-success @else btn-primary @endif"><i class="glyphicon glyphicon-cog"></i> Setup site</a>
        @endif
        <a href="{{route('addSite', [])}}" class="btn btn-warning" ><i class="glyphicon glyphicon-plus-sign"></i> Add site </a>
    </div>

    <div class="col-md-3 text-right" style="margin-top:10px; float: right;">
        <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-off"></i> Logout</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
    </div>
</div>