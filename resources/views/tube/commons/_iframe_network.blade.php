@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $agent = new \Jenssegers\Agent\Agent() ?>

    @if ($site->iframe_site_id != "")
        <?php $iframeSite = \App\Model\Site::find($site->iframe_site_id); ?>
        <div class="container">
            <iframe src="http://{{$iframeSite->getHost()}}/ads?c={{str_replace('#', '', $site->color)}}&c10={{str_replace('#', '', $site->color10)}}&c11={{str_replace('#', '', $site->color11)}}&c6={{str_replace('#', '', $site->color6)}}" width="100%" style="border: none;height:305px;overflow:hidden !important;"></iframe>
        </div>
    @endif
@endif