<div class='row'>
    <div class='col-md-12'>
        <span><img src='{{asset("flags/".$s->language->code.".png")}}'/> {{$s->getHost()}}</span>
        <small>
            <span class='label label-success'><i class='glyphicon glyphicon-film'></i> Scenes: {{$s->getTotalScenes()}}</span>
            <span class='label label-success'><i class='glyphicon glyphicon-th-large'></i> Categories: {{$s->categories()->where('status', 1)->count()}}</span>
            <span class='label label-success'><i class='glyphicon glyphicon-time'></i> Cronjobs: {{$s->cronjobs()->count()}}</span>
            <span class='label label-success'><i class='glyphicon glyphicon-star-empty'></i> Pornstars: {{$s->pornstars()->count()}}</span>
        </small>
    </div>
</div>
