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
        <div class="alert alert-success" style="background-color:<?=$bgColor?>;margin:0px;padding-top:15px;">

            <div class="col-md-3">
                @if ($infojob->finished)
                    Finished ({{$infojob->finished_at}})
                @else
                    Work in progress
                @endif
            </div>

            <div class="col-md-2">
                http://{{$infojob->site()->first()->getHost()}}
            </div>

            <div class="col-md-1">
                {{date("Y-m-d", strtotime($infojob->created_at))}}
            </div>

            <div class="col-md-1">
                {{--@if (!$infojob->channel()->first())--}}
                    {{--No channel--}}
                {{--@else--}}
                    {{--{{$infojob->channel()->first()->name}}--}}
                {{--@endif--}}
            </div>

            <div class="col-md-4">
                @if ($infojob->serialized)
                    {{dump(json_decode($infojob->serialized))}}
                @else
                    No serialized data
                @endif
            </div>

            <div class="clearfix"></div>
        </div>
    @endforeach
</div>

<div class="row site_workers_paginator">
    <?php $infojobs->setPath('workers/'.$site->id);?>
    <?php echo $infojobs->render(); ?>
</div>
