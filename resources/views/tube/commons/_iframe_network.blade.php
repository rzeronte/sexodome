@if (env("SHOW_IFRAME_NETWORK", true))
    <iframe src="http://{{$site->getHost()}}/ads" width="100%" style="border: none;height:305px;overflow:hidden !important;"></iframe>
@endif