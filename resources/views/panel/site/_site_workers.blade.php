<div class="col-md-12 detail-works">

    <?php $infojobs = $site->infojobs()->paginate(10); ?>

    <div class="workers_ajax_container">
        @include('panel.ajax._ajax_site_workers')
    </div>

</div>
