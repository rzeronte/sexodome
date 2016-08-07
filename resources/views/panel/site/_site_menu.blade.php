<div class="col-md-12">
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs logo-show-info"><i class="glyphicon glyphicon-picture"></i> Logo</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs seo-show-info"><i class="glyphicon glyphicon-signal"></i> SEO</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs categories-show-info"><i class="glyphicon glyphicon-th-large"></i> Categories</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs tags-show-info"><i class="glyphicon glyphicon-th"></i> Tags</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs google-show-info"><i class="glyphicon glyphicon-globe"></i> Google</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs iframe-show-info" ><i class="glyphicon glyphicon-screenshot"></i> IFrame</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs colors-show-info"><i class="glyphicon glyphicon-tint"></i> Colors</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs works-show-info"><i class="glyphicon glyphicon-cd"></i> Works History</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs import-show-info"><i class="glyphicon glyphicon-film"></i> Import scenes</a>
    <a style="margin-bottom:10px;margin-right:5px;" class="btn btn-primary btn-xs import-show-cronjobs"><i class="glyphicon glyphicon-time"></i> CronJobs</a>

    <a class="btn btn-xs btn-danger" href="{{route('deleteSite', ['locale'=>$language->code, "site_id"=>$site->id])}}" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-trash"></i> DELETE SITE</a>
</div>
