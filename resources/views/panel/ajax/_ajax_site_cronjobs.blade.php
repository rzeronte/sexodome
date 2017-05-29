<?php $loop = 0 ?>

@if ($site->cronjobs()->count() == 0)
    <div class="row" style="margin:0px;padding:15px;">
        No current cronjobs founded
    </div>
@endif

@foreach($site->cronjobs()->get() as $cronjob)
    <div class="row" style=";margin:0px;padding:15px;">
        <div class="col-md-1">
            <img src="{{asset('channels/'.$cronjob->channel->logo)}}" style="width:40px; border: solid 1px black;"/>
        </div>

        <div class="col-md-9">
            <?php $cronjobData = json_decode($cronjob->params) ?>

            @if (isset($cronjobData->feed_name))
                <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                            {{$cronjobData->feed_name}}
                        </span>
            @endif
            @if (isset($cronjobData->max))
                <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                        Max scenes: {{$cronjobData->max}}
                        </span>
            @endif
            @if (isset($cronjobData->duration))
                <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                        Min duration: {{$cronjobData->duration}}
                        </span>
            @endif
            @if (isset($cronjobData->tags))
                <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                        Tags: {{$cronjobData->tags}}
                        </span>
            @endif
            @if (isset($cronjobData->only_with_pornstars))
                <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                            Only with pornstar: {{$cronjobData->only_with_pornstars}}
                        </span>
            @endif

        </div>

        <div class="col-md-2">
            <a href="{{route('deleteCronJob', ["cronjob_id" => $cronjob->id])}}" class="btn btn-danger delete-site-cronjob-btn" style="float:right;">DELETE CRONJOB</a>
        </div>

    </div>
@endforeach
