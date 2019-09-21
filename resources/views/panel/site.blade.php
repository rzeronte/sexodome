<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>
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
                    <div class="alert alert-success" role="alert">
                        <a href="http://{{$site->getHost()}}" target="_blank">
                            http://{{$site->getHost()}}
                        </a>
                        <small>({{number_format($site->getTotalScenes(), 0, ",", ".")}} active scenes)</small>

                    </div>
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
