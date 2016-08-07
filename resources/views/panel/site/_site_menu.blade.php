<div class="col-md-12">
    <a data-div-show="detail-logo" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-picture"></i> Logo</a>
    <a data-div-show="detail-seo" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-signal"></i> SEO</a>
    <a data-div-show="detail-categories" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-th-large"></i> Categories</a>
    <a data-div-show="detail-tags" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-th"></i> Tags</a>
    <a data-div-show="detail-analytics" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-globe"></i> Google</a>
    <a data-div-show="detail-iframe" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs" ><i class="glyphicon glyphicon-screenshot"></i> IFrame</a>
    <a data-div-show="detail-colors" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-tint"></i> Colors</a>
    <a data-div-show="detail-works" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-cd"></i> Works History</a>
    <a data-div-show="detail-import" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-film"></i> Import scenes</a>
    <a data-div-show="detail-cronjobs" style="margin-bottom:10px;margin-right:5px;" class="btn_site_menu_option btn btn-primary btn-xs"><i class="glyphicon glyphicon-time"></i> CronJobs</a>

    <a class="btn btn-xs btn-danger" href="{{route('deleteSite', ['locale'=>$language->code, "site_id"=>$site->id])}}" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-trash"></i> DELETE</a>
</div>
