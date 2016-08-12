<div class="col-md-12 detail-tags">

    <?php $tags = \App\Model\Tag::getTranslationSearch(false, $language->id)->where('site_id', '=', $site->id)->paginate(10); ?>

    <div class="tags_ajax_container">
        @include('panel._ajax_site_tags')
    </div>

</div>
