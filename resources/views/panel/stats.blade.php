<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <h2>Title keywords density</h2>
        {{dump($words)}}
    </div>

</div>
</body>
</html>
