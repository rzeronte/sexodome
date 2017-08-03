<div class="col-md-12 detail-tags">

    <div class="row">
        <form action="{{route('ajaxSiteTags', ['site_id' => $site->id])}}" class="tag-search-form">
            <div class="col-md-4">
                <input type="text" placeholder="type tag name" name="q" class="form-control"/>
            </div>

            <div class="col-md-2">
                <input type="submit" class="btn btn-success" value="Search"/>
            </div>

            <div class="col-md-2">
            </div>

            <div class="col-md-4 text-right">
                <button type="button" class="seo-info-keywords btn btn-success btn-create-tag" data-toggle="modal" data-target="#modal-sexodome" data-url="{{ route('createTag', ['site_id' => $site->id]) }}" style=""><i class="glyphicon glyphicon-plus-sign"></i> Create tag</button>
            </div>

        </form>

    </div>

    <?php $tags = \App\Model\Tag::getTranslationSearch(false, $site->language->id)->where('site_id', '=', $site->id)->paginate(App::make('sexodomeKernel')->perPageTags); ?>
    <div class="tags_ajax_container">
        @include('panel.ajax._ajax_site_tags')
    </div>

</div>
