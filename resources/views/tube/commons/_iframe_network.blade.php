@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $agent = new \Jenssegers\Agent\Agent() ?>

    @if ($site->iframe_site_id != "")
        <?php
        $iframeSite = Cache::remember('iframe_'.$site->id, env('MEMCACHED_QUERY_TIME', 30), function() use ($site) {
            return \App\Model\Site::find($site->iframe_site_id);
        });
        ?>
        <div class="container">
            <iframe src="http://{{$iframeSite->getHost()}}/ads?c={{str_replace('#', '', $site->color)}}&c10={{str_replace('#', '', $site->color10)}}&c11={{str_replace('#', '', $site->color11)}}&c6={{str_replace('#', '', $site->color6)}}" width="100%" style="border: none;height:170px;" scrolling="no"></iframe>
        </div>
    @endif
@endif