@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $agent = new \Jenssegers\Agent\Agent() ?>

    @if ($sexodomeKernel->getSite()->iframe_site_id != "")
        <?php
        $site = $sexodomeKernel->getSite();
        $iframeSite = Cache::remember('iframe_'.$sexodomeKernel->getSite()->id, env('MEMCACHED_QUERY_TIME', 30), function() use ($site) {
            return \App\Model\Site::find($site->iframe_site_id);
        });
        ?>
        <div class="container">
            <iframe src="http://{{$iframeSite->getHost()}}/ads?c={{str_replace('#', '', $sexodomeKernel->getSite()->color)}}&c10={{str_replace('#', '', $sexodomeKernel->getSite()->color10)}}&c11={{str_replace('#', '', $sexodomeKernel->getSite()->color11)}}&c6={{str_replace('#', '', $sexodomeKernel->getSite()->color6)}}" width="100%" style="border: none;height:170px;" scrolling="no"></iframe>
        </div>
    @endif
@endif