<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><i class="glyphicon glyphicon-exclamation-sign"></i> <b>Current jobs in queue</b></p>
        </div>
    </div>

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
                    @if (!$infojob->channel()->first())
                        No channel
                    @else
                        {{$infojob->channel()->first()->name}}
                    @endif
                </div>

                <div class="col-md-4">
                    @if ($infojob->serialized)
                        {{var_dump(json_decode($infojob->serialized))}}
                    @else
                        No serialized data
                    @endif
                </div>

                <div class="clearfix"></div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <?php echo $infojobs->appends([
        ])->render(); ?>
    </div>


    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Jobs queue manager</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
