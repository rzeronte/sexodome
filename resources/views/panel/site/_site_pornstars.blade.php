<div class="col-md-12 detail-pornstars">

    <?php $pornstars = \App\Model\Pornstar::where('site_id', '=', $site->id)->paginate(12); ?>

    <div class="pornstars_ajax_container">
        @include('panel._ajax_site_pornstars')
    </div>

</div>
