<div class="col-md-12 detail-tags" style="display:none;margin-top:20px;margin-top:20px;">
    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-th"></i> <b>Tags</b></p>
    </div>

    <?php $tags = \App\Model\Tag::getTranslationSearch(false, $language->id)->where('site_id', '=', $site->id)->paginate(10); ?>

    <div class="tags_ajax_container">
        @include('panel._ajax_site_tags')
    </div>

</div>
