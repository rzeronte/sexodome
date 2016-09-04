<?php $agent = new \Jenssegers\Agent\Agent() ?>;
@if (!$agent->isMobile())
    <section class="banner_footer text-center">
        @if ($site->banner_script1 != "")
            <?php echo $site->banner_script1 ?>
        @endif
    </section>
@endif

