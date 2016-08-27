@if (count($infojobs) == 0)
    <div class="row" style="margin:0px;padding-top:15px;">
        No jobs in queue
    </div>
@endif

<div class="row infojobs-list">

    <?php $loop = 0 ?>
    @foreach($infojobs as $infojob)
        <?php
        $loop++;
        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>
        <div class="row" style="background-color:<?=$bgColor?>;margin:0px;padding-top:15px;">

            <div class="col-md-2">
                @if ($infojob->finished)
                    Finished at: ({{$infojob->finished_at}})
                @else
                    Work in progress
                @endif
            </div>

            <div class="col-md-2">
                http://{{$infojob->site()->first()->getHost()}}
                <br/>
                Created at: {{date("Y-m-d", strtotime($infojob->created_at))}}

            </div>

            <div class="col-md-8">
                @if ($infojob->serialized)
                    <?php $cronjobData = \GuzzleHttp\json_decode($infojob->serialized) ?>

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
                    @if (isset($cronjobData->categories))
                        <span class='label label-success' style="margin-right:5px;margin-top:4px;">
                        Categories: {{$cronjobData->categories}}
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

                @else
                    No serialized data
                @endif
            </div>

            <div class="clearfix"></div>
        </div>
    @endforeach
</div>

<div class="row site_workers_paginator">
    <?php $infojobs->setPath("/".$locale.'/workers/'.$site->id);?>
    <?php echo $infojobs->render(); ?>
</div>
