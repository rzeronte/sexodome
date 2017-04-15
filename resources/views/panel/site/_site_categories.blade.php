    <div class="col-md-12 detail-categories">

    <div class="row">
        <form action="{{route('ajaxSiteCategories', ['locale' => $locale, 'site_id' => $site->id])}}" class="category-search-form">
            <div class="col-md-4">
                <input type="text" placeholder="type category name" name="q" class="form-control"/>
            </div>

            <div class="col-md-2">
                <input type="submit" class="btn btn-success" value="Search"/>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{route('orderCategories', ['locale' => $locale, 'site_id' => $site->id])}}" class="btn btn-success">Manual Order</a>
            </div>

        </form>

    </div>

    <div class="clearfix"></div>

    <?php $categories = \App\Model\Category::getTranslationByStatus(1, $language->id)->where('site_id', '=', $site->id)->paginate(30)?>

    <div class="categories_ajax_container" style="margin-top: 20px;">
        @include('panel.ajax._ajax_site_categories')
    </div>

</div>
