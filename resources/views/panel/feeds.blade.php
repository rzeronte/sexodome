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
            <p><i class="glyphicon glyphicon-open-file"></i> <b>Channels</b></p>
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
            <div class="row" style="background-color:<?=$bgColor?>;margin:0px;padding-top:15px;">
                <form action="{{route('fetch')}}" class="submit-feed-site-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="feed_name" value="{{ $channel->name }}"/>

                    <div class="col-md-1" style="text-align:center;">
                        <img src="{{asset('channels/'.$channel->logo)}}" style="width:60px; border: solid 1px black;"/><br/>
                        <p>{{$channel->name}}</p><br/>
                    </div>
                    <div class="col-md-1">
                        {{$channel->permalink}}
                    </div>
                    <div class="col-md-1">
                        {{number_format($channel->nvideos, 0, ",", ".")}} scenes
                    </div>

                    <div class="col-md-1">
                        @if ($channel->embed == 1 )
                            Embed
                        @else
                            No embed
                        @endif
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="site_id" style="width:100%" required>
                            <option value="">-- select site --</option>
                            @foreach($sites as $site)
                                @if ($site->have_domain == 1)
                                    <option value="{{$site->id}}">{{$site->domain}}</option>
                                @else
                                    <option value="{{$site->id}}">{{$site->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-control" name="max" style="width:100%" required>
                            <option value="">-- amount --</option>
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="duration" style="width:100%" required>
                            <option value="">-- secs --</option>
                            <option value="50">min. 50 secs</option>
                            <option value="100">min. 100 secs</option>
                            <option value="200">min. 200 secs</option>
                            <option value="300">min. 300 secs</option>
                            <option value="400">min. 400 secs</option>
                            <option value="500">min. 500 secs</option>
                            <option value="600">min. 600 secs</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary" value="Importar">
                    </div>

                </form>
            </div>
        @endforeach

        <div style="border-top: solid 1px darkorange;margin-top:20px;">
            <p class="text-right">panel v.0.16</p>
        </div>

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
