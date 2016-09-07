<?php $agent = new \Jenssegers\Agent\Agent() ?>;

@if (!$agent->isMobile())
    <section class="banner_footer text-center container">
        <div class="col-md-4">
            @if ($site->banner_script1 != "")
                <?php echo $site->banner_script1 ?>
            @endif
        </div>
        <div class="col-md-4">
            @if ($site->banner_script2 != "")
                <?php echo $site->banner_script2 ?>
            @endif
        </div>
        <div class="col-md-4">
            @if ($site->banner_script3 != "")
                <?php echo $site->banner_script3 ?>
            @endif
        </div>
    </section>
@endif

