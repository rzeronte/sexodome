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
            <div class="row" style="background-color:<?=$bgColor?>;margin:0px;padding:15px;">
                <form action="{{route('fetch')}}" class="submit-feed-site-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="feed_name" value="{{ $channel->name }}"/>

                    <div class="col-md-1" style="text-align:center;">
                        <img src="{{asset('channels/'.$channel->logo)}}" style="width:60px; border: solid 1px black;"/><br/>
                        <p>{{$channel->name}}</p><br/>
                    </div>

                    <div class="col-md-2">
                        permalink: <b>{{$channel->permalink}}</b><br/>

                        @if ($channel->embed == 1 )
                            type: <b>Embed</b>
                        @else
                            type:<b>No embed</b>
                        @endif
                        <br/>
                        {{number_format($channel->nvideos, 0, ",", ".")}} total scenes
                    </div>

                    <div class="col-md-2">
                        <select class="selector_feeds_site form-control" name="site_id" data-ajax="{{route('ajaxCategoriesOptions', ['locale' => $locale])}}" style="width:100%" required>
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
                            <option value="">-- select amount --</option>
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="1000">1000</option>
                            <option value="5000">5000</option>
                            <option value="10000">10000</option>
                            <option value="25000">25000</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="selector_feed_categories form-control" name="categories[]" style="width:100%;height:150px;" multiple>
                            <option value=""> -- select site first-- </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-control" name="duration" style="width:100%">
                            <option value="">-- any minutes --</option>
                            <option value="60">1 min</option>
                            <option value="300">5 min</option>
                            <option value="600">10 min</option>
                            <option value="900">15 min</option>
                            <option value="1200">20 min</option>
                            <option value="1500">25 min</option>
                            <option value="1800">30 min</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary" value="Importar">
                    </div>

                </form>
            </div>
        @endforeach

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
