    <div class="col-md-12 detail-categories">

    <div class="row">
        <form action="{{route('ajaxSiteCategories', ['site_id' => $site->id])}}" class="category-search-form">
            <div class="col-md-4">
                <input type="text" placeholder="type category name" name="q" class="form-control"/>
            </div>

            <div class="col-md-2">
                <small>Order by nº scenes</small>
                <input type="checkbox" class="" name="order_by_nscenes" value="1"/>
            </div>

            <div class="col-md-2">
                <input type="submit" class="btn btn-success" value="Search"/>
            </div>


            <div class="col-md-4 text-right">
                <a href="{{route('orderCategories', ['site_id' => $site->id])}}" class="btn btn-success"><i class="glyphicon glyphicon-th"></i> Manage order</a>
                <button type="button" class="seo-info-keywords btn btn-success btn-create-category" data-toggle="modal" data-target="#modal-sexodome" data-url="{{ route('createCategory', ['site_id' => $site->id]) }}" style=""><i class="glyphicon glyphicon-plus-sign"></i> Create category</button>
            </div>

        </form>

    </div>

    <div class="clearfix"></div>
    <?php $categories = \App\Model\Category::getTranslationSearch(false, $site->language->id, $site->id)->paginate(30) ?>

    <div class="categories_ajax_container" style="margin-top: 20px;">
        @include('panel.ajax._ajax_site_categories')
    </div>

</div>
