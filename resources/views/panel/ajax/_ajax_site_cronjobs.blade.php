<?php $loop = 0 ?>

@if ($site->cronjobs()->count() == 0)
    <div class="row" style="margin:0px;padding:15px;">
        No current cronjobs founded
    </div>
@endif

@foreach($site->cronjobs()->get() as $cronjob)
    <?php
    $loop++;
    if ($loop % 2) {
        $bgColor = '#e8e8e8';
    } else {
        $bgColor = 'lightyellow';
    }
    ?>
    <div class="row" style="background-color:<?=$bgColor?>;margin:0px;padding:15px;">
        <div class="col-md-6">
            <img src="{{asset('channels/'.$cronjob->channel->logo)}}" style="width:40px; border: solid 1px black;"/>
            <b>http://{{$cronjob->site->getHost()}}</b>
        </div>
        <div class="col-md-6">
            <a href="{{route('deleteCronJob', ['locale' => $locale, "cronjob_id" => $cronjob->id])}}" class="btn btn-danger delete-site-cronjob-btn" style="float:right;">DELETE CRONJOB</a>
        </div>

    </div>
@endforeach
