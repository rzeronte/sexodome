<div class="col-md-12 detail-categories">

    <?php $categories = \App\Model\Category::getTranslationByStatus(1, $language->id)->where('site_id', '=', $site->id)->paginate(10)?>

    <div class="categories_ajax_container">
        @include('panel._ajax_site_categories')
    </div>

</div>
