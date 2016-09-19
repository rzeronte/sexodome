<?php $agent = new \Jenssegers\Agent\Agent() ?>
@if ($agent->isMobile())
    @if ($site->banner_mobile1 != "")
    <section class="banner_mobile_header text-center container">
        <?php echo $site->banner_mobile1 ?>
    </section>
    @endif
@endif

