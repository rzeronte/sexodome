@if (env("SHOW_IFRAME_NETWORK", true))
    <?php $siteIframe = App\Model\Site::find($site->iframe_site_id) ?>
    <iframe src="http://{{$siteIframe->getHost()}}/ads" width="100%" style="border: none;height:205px;overflow:hidden !important;">
    </iframe>
@endif