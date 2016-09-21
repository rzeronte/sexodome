@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $agent = new \Jenssegers\Agent\Agent() ?>

    @if (!$agent->isMobile())
        @if ($site->iframe_site_id != "")
            <?php $iframeSite = \App\Model\Site::find($site->iframe_site_id); ?>
            <div class="container">
                <iframe src="http://{{$iframeSite->getHost()}}/ads?c={{str_replace('#', '', $site->color)}}" width="100%" style="border: none;height:305px;overflow:hidden !important;"></iframe>
            </div>
        @endif
    @endif
@endif