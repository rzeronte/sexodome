<div class="col-md-12 detail-cronjobs coloreable">

    <div class="row cronjobs_ajax_container">
        <?php $loop = 0 ?>

        <div class="conteiner" style="margin-top:20px;">
            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-time"></i> <b>Cronjobs saved</b></p>
            </div>
        </div>

        @if ($site->cronjobs()->count() == 0)
            <div class="row" style="margin:0px;padding:15px;">
                Currently no cronjobs
            </div>
        @endif

        @foreach($site->cronjobs()->get() as $cronjob)
            <div class="row alternate_coloreable" style="margin:0px;padding:15px;">
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
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><i class="glyphicon glyphicon-time"></i> <b>Create new CronJob</b></p>
        </div>
    </div>

    <div class="row coloreable">
        <?php $loop = 0 ?>
        @foreach($channels as $channel)
            @if ($channel->existDump())
            <div class="row alternate_coloreable" style="margin:0px;padding:15px;">
            <form class="form-create-cronjob" data-update-cronjobs-url="{{route('ajaxCronJobs', ['site_id' => $site->id])}}" action="{{route('ajaxSaveCronJob', [])}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="feed_name" value="{{ $channel->name }}"/>
                <input type="hidden" name="site_id" value="{{$site->id}}"/>

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
                    <?php $cfg = new $channel->mapping_class; ?>
                    <?php $mappedColumn = $cfg->mappingColumns(); ?>

                    <input type="text" name="tags" class="form-control" placeholder="tags comma separated">

                </div>

                <div class="col-md-2">
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

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="only_with_pornstars" value="1"> <small>Only with pornstars</small>
                        </label>
                    </div>

                </div>

                <div class="col-md-2">
                    <input type="submit" class="btn btn-primary" value="Create cronjob">
                </div>

            </form>
            </div>
            @endif
        @endforeach

    </div>

</div>
