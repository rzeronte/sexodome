<div class="col-md-12 detail-works" style="display:none;">
    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-cd"></i> <b>History Workers</b></p>
    </div>

    <?php $infojobs = $site->infojobs()->paginate(10); ?>

    <div class="workers_ajax_container">
        @include('panel._ajax_site_workers')
    </div>

</div>
