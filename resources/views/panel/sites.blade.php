<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
        <br/>
    </div>

    <div class="row">
        <div class="col-md-12">
            <select id="selector_site" name="site_id" class="form-control">
                @foreach($sites as $site)
                    <option value="{{$site->id}}" data-action="{{route('site', ['locale' => $locale, 'site_id' => $site->id])}}">{{$site->getHost()}}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

</body>
</html>
