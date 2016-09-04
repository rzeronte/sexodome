<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    @if(Session::get('error'))
        <p style='color:red'>{{Session::get('error')}}</p>
    @endif

    <div class="row">

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <h3 style="margin-top: 0;">
                        <a href="http://{{$site->getHost()}}" target="_blank">
                        http://{{$site->getHost()}}
                        </a>
                        <small>({{$site->getTotalScenes()}} active scenes)</small>
                    </h3>
                </div>
                <div class="col-md-12">
                    @include('panel.site._site_menu')
                </div>
            </div>

        </div>

    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

@include('panel._sticker')
@include('panel._modal')
</body>
</html>
