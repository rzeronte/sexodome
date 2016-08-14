<div class='row'>
    <div class='col-md-4'>
        <span class='label label-primary'><i class='glyphicon glyphicon-globe'></i> {{$s->getHost()}}</span>
        <span class='label label-success'><i class='glyphicon glyphicon-film'></i> Scenes: {{$s->getTotalScenes()}}</span>
{{--        <span class='label label-success'><i class='glyphicon glyphicon-th-large'></i> Categories: {{\App\Model\Category::getTranslationByStatus(1, $language->id)->count()}}</span>--}}
        <span class='label label-success'><i class='glyphicon glyphicon-time'></i> CronJobs: {{$s->cronjobs()->count()}}</span>
        <span class='label label-success'><i class='glyphicon glyphicon-star-empty'></i> Pornstars: {{$s->pornstars()->count()}}</span>
    </div>
</div>
