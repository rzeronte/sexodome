<div class="col-md-12 detail-pornstars" style="display:none;margin-top:20px;margin-top:20px;">
    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-th"></i> <b>Pornstars</b></p>
    </div>

    <?php $pornstars = \App\Model\Pornstar::where('site_id', '=', $site->id)->paginate(10); ?>

    <div class="pornstars_ajax_container">
        @include('panel._ajax_site_pornstars')
    </div>

</div>
