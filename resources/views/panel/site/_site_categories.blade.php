<div class="col-md-12 detail-categories" style="display:none;margin-top:20px;">

    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-th-large"></i> <b>Categories</b></p>
    </div>

    <?php $categories = \App\Model\Category::getTranslationByStatus(1, $language->id)->where('site_id', '=', $site->id)->paginate(10)?>

    <div class="categories_ajax_container">
        @include('panel._ajax_site_categories')
    </div>

</div>
