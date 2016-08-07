<div class="col-md-12 detail-cronjobs" style="display:none;margin-top:20px;">

    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-time"></i> <b>CronJobs</b></p>
    </div>

    <div class="row cronjobs_ajax_container" style="background-color:white;">
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
                <div class="col-md-3">
                    <img src="{{asset('channels/'.$cronjob->channel->logo)}}" style="width:40px; border: solid 1px black;"/>
                    <b>http://{{$cronjob->site->getHost()}}</b>
                </div>
                <div class="col-md-9">
                    {{dump($cronjob->params)}}
                    <a href="{{route('deleteCronJob', ['locale' => $locale, "cronjob_id" => $cronjob->id])}}" class="btn btn-danger delete-site-cronjob-btn" style="float:right;">DELETE CRONJOB</a>
                </div>

            </div>
        @endforeach
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><i class="glyphicon glyphicon-time"></i> <b>Create new CronJob</b></p>
        </div>
    </div>

    <div class="row" style="background-color:white;">
        <?php $loop = 0 ?>
        @foreach($channels as $channel)
            <?php
            $loop++;
            if ($loop % 2) {
                $bgColor = '#e8e8e8';
            } else {
                $bgColor = 'lightyellow';
            }
            ?>
            <div class="row" style="background-color:<?=$bgColor?>;margin:0px;padding:15px;">
                <form class="form-create-cronjob" data-update-cronjobs-url="{{route('ajaxCronJobs', ['locale' => $locale, 'site_id' => $site->id])}}" action="{{route('ajaxSaveCronJob', ['locale' => $locale])}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="feed_name" value="{{ $channel->name }}"/>
                    <input type="hidden" name="site_id" value="{{ $site->id}}"/>

                    <div class="col-md-1" style="text-align:center;">
                        <img src="{{asset('channels/'.$channel->logo)}}" style="width:40px; border: solid 1px black;"/><br/>
                        <p>{{$channel->name}}</p><br/>
                    </div>

                    <div class="col-md-2">
                        <select class="form-control" name="max" style="width:100%" required>
                            <option value="">-- select amount --</option>
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="25">25</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="categories" class="form-control" placeholder="categories comma separated">
                    </div>

                    <div class="col-md-1">
                        <select class="form-control" name="duration" style="width:100%">
                            <option value="">time</option>
                            <option value="60">1 min</option>
                            <option value="300">5 min</option>
                            <option value="600">10 min</option>
                            <option value="900">15 min</option>
                            <option value="1200">20 min</option>
                            <option value="1500">25 min</option>
                            <option value="1800">30 min</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary" value="Create cronjob">
                    </div>

                </form>
            </div>
        @endforeach

    </div>

</div>
