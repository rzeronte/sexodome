<div class="col-md-12 detail-import" style="display:none;margin-top:20px;">

    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-film"></i> <b>Import scenes</b></p>
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
                <form action="{{route('fetch', ['site_id' => $site->id])}}" class="submit-feed-site-form">
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
                            type: <b>No embed</b>
                        @endif
                        <br/>
                        {{number_format($channel->nvideos, 0, ",", ".")}} total scenes
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

                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary" value="Importar">
                    </div>

                </form>
            </div>
        @endforeach

    </div>

</div>
