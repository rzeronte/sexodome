@if (env("SHOW_IFRAME_NETWORK", true))
    <h3 class="iframe_url" style="border-bottom: solid 1px black;padding-bottom:4px;">{{$language->iframe_src}}</h3>
    <iframe src="http://{{$language->iframe_src}}/ads" width="100%" style="background-color: transparent; border: none;height:270px;overflow:hidden;padding:5px;font-family: Arial">

    </iframe>
@endif