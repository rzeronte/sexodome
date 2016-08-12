<div class='row'>
    <div class='col-md-4'>
        <span class='label label-primary'><i class='glyphicon glyphicon-th-large'></i> Site: http://{{$s->getHost()}}</span>
        <span class='label label-success'><i class='glyphicon glyphicon-film'></i> Total scenes: {{$s->getTotalScenes()}}</span>
        <span class='label label-success'><i class='glyphicon glyphicon-time'></i> CronJobs: {{$s->cronjobs()->count()}}</span>
    </div>
</div>
