<div class="col-md-12 detail-popunders">

    <form id="formAddPopunder" action="{{route('ajaxSavePopunder', ["locale" => $locale, "site_id" => $site->id])}}" data-ajax-popunders="{{route('ajaxPopunders', ['locale' => $locale, "site_id" => $site->id])}}">
        <div class="row">
                <div class="col-md-1">
                   <label>URL:</label>
                </div>
                <div class="col-md-8">
                    <input type="url" class="form-control" placeholder="type here your popunder URL" name="url" required/>
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn btn-primary" value="Add popunder"/>
                </div>
        </div>
    </form>

    <br/>

    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-signal"></i> <b>Popunders for {{$site->getHost()}}</b></p>
    </div>

    <div class="container-ajax-popunders">
        <?php $popunders = $site->popunders()->get() ?>
        @include('panel.ajax._ajax_site_popunders')

    </div>

</div>
