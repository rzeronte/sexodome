@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $agent = new \Jenssegers\Agent\Agent() ?>;

    @if (!$agent->isMobile())
        <div class="container">
            <iframe src="http://{{$site->getHost()}}/ads" width="100%" style="border: none;height:305px;overflow:hidden !important;"></iframe>
        </div>
    @endif
@endif