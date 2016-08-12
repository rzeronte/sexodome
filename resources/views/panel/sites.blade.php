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


    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

</body>
</html>
