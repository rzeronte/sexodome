<?php $agent = new \Jenssegers\Agent\Agent() ?>

@if ($agent->isMobile())
    <aside class="billboard">
        <div class="container text-center">
            @if (strlen(trim($site->link_billboard_mobile)) > 0)
                <?php echo $site->link_billboard_mobile ?>
            @endif
        </div>
    </aside>
@else
    <aside class="billboard">
        <div class="container text-center">
            @if (strlen(trim($site->link_billboard)) > 0)
                <?php echo $site->link_billboard ?>
            @endif
        </div>
    </aside>
@endif
