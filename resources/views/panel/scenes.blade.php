<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><i class="glyphicon glyphicon-th"></i> <b>Scenes</b>
            <b>{{ number_format($scenes->total(), 0, ",", ".") }}</b> found for:
                @if ($query_string != "")
                    <b><i>"{{$query_string}}"</i></b> in title
                @else
                    <b><i>any title</i></b>
                @endif
                @if ($tag_q != "")
                    and <b><i>"{{$tag_q}}"</i></b> tag
                @endif
            </p>
        </div>
    </div>

    <div class="row" style="padding:10px;">
        <form action="{{ route('content', ['site_id' => $site->id]) }}" method="get" style="width:100%">
            <div class="col-md-2">
                <input id="query_string" name="q" type="text" placeholder="title search" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
            </div>
            <div class="col-md-2">
                <input name="category_string" class="form-control" style="width:100%;" placeholder="category search">
            </div>
            <div class="col-md-1">
                <input id="query_tags" name="tag_q" type="text" placeholder="tag" class="form-control query_string" value="{{$tag_q}}" style="width:100%;">
            </div>
            <div class="col-md-2">
                <select name="duration" class="form-control">
                    <option value="">any duration</option>
                    <option value="300" @if($duration == 300) selected @endif>5min</option>
                    <option value="360" @if($duration == 360) selected @endif>6min</option>
                    <option value="420" @if($duration == 420) selected @endif>7min</option>
                    <option value="480" @if($duration == 480) selected @endif>8min</option>
                    <option value="540" @if($duration == 540) selected @endif>9min</option>
                    <option value="600" @if($duration == 600) selected @endif>10min</option>
                    <option value="600" @if($duration == 900) selected @endif>15min</option>
                    <option value="600" @if($duration == 1200) selected @endif>20min</option>
                </select>
            </div>

            <div class="col-md-2">
                <select class="form-control" name="publish_for" style="width:100%">
                    <option value="">all my sites</option>
                    @foreach($sites as $site)
                        @if ($site->id == $publish_for)
                            @if ($site->have_domain == 1)
                                <option value="{{$site->id}}" selected>{{$site->domain}}</option>
                            @else
                                <option value="{{$site->id}}" selected>{{$site->name}}.{{\App\rZeBot\sexodomeKernel::getMainPlataformDomain()}}</option>
                            @endif
                        @else
                            @if ($site->have_domain == 1)
                                <option value="{{$site->id}}">{{$site->domain}}</option>
                            @else
                                <option value="{{$site->id}}">{{$site->name}}.{{\App\rZeBot\sexodomeKernel::getMainPlataformDomain()}}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 text-left">
                <input name="empty_title" type="checkbox" @if (\Illuminate\Support\Facades\Request::input('empty_title') == "on") checked @endif>
                Title empty<br/>
                <input name="empty_description" type="checkbox" @if (\Illuminate\Support\Facades\Request::input('empty_description') == "on") checked @endif>
                Description empty<br/>

            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">search</button>
            </div>
        </form>
    </div>

    <div class="row">
        <?php echo $scenes->appends([
                'q'           => $query_string,
                'tag_q'       => $tag_q,
                'publish_for' => $publish_for,
                'duration'    => $duration
        ])->render(); ?>
    </div>

    @foreach($scenes as $scene)
        <?php
            $thumbs = json_decode($scene->thumbs);
            $index = rand(0, count($thumbs)-1);
        ?>

        <div class="row coloreable" style="padding: 5px;">
            <form action="{{route('saveTranslation', ['scene_id'=>$scene->id])}}" class="ajax-form">
                <div class="col-md-2">

                    @if ($scene->thumb_index > 0)
                        <img title="{{$scene->permalink}}" src="<?=htmlspecialchars($thumbs[$scene->thumb_index])?>" class="img-responsive thumbnail selected-thumb-for-{{$scene->id}}"/>
                    @else
                        <img title="{{$scene->permalink}}" src="<?=htmlspecialchars($scene->preview)?>" class="img-responsive thumbnail selected-thumb-for-{{$scene->id}}"/>
                    @endif

                </div>

                <div class="col-md-2">
                    <i class="glyphicon glyphicon-thumbs-up"></i> {{number_format($scene->rate, 2)}}%</b><br/>
                    <i class="glyphicon glyphicon-time"></i> {{gmdate("i:s", $scene->duration)}}<br/>
                    <i class="glyphicon glyphicon-eye-open"></i> {{ $scene->views+0}} views<br/>
                    <i class="glyphicon glyphicon-open-file"></i> {{ $scene->channel_name}}<br/>
                    <i class="glyphicon glyphicon-open-file"></i> Id: {{ $scene->site->id}}<br/>
                    @if ($scene->site_have_domain == 1)
                    @else
                    @endif
                    @foreach (App::make('sexodomeKernel')->getLanguages() as $itemLang)
                        <a href="{{route('content', ['locale'=>$itemLang->code,'scene_id'=> $scene->id])}}" target="_blank"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
                    @endforeach

                </div>

                <div class="col-md-5" style="margin: 5px 0 0 0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" class="selectedThumb{{$scene->id}}" name="selectedThumb" value="{{$scene->thumb_index}}"/>

                    <input type="text" value="{{$scene->title}}" class="form-control" name="title"/>
                    <textarea class="form-control" style="margin-top:5px;margin-bottom:5px;height:90px;" name="description" placeholder="Description here...">{{$scene->description}}</textarea>
                </div>

                <div class="col-md-3" style="margin: 10px 0 0 0">
                    @if ($scene->embed == 1)
                        <button type="button" class="btn-preview-scene btn btn-primary" data-toggle="modal" data-target="#previewModal" data-scene-id="{{$scene->id}}" data-url="{{route('scenePreview', ['scene_id'=>$scene->id])}}" style="width:100%">
                            <i class="fa fa-eye"></i> preview
                        </button>
                    @else
                        <a href="{{$scene->iframe}}" target="_blank" class="btn btn-primary"  style="width:100%"><i class="fa fa-eye"></i> preview</a>
                    @endif

                    <br/>

                    <button type="button" class="btn-select-thumb btn btn-primary" data-toggle="modal" data-target="#previewModal" data-url="{{route('sceneThumbs', ['scene_id'=>$scene->id])}}" style="width:100%;margin-top:7px;">
                        <i class="glyphicon glyphicon-picture"></i> thumbnails
                    </button>
                    <br/>

                    <button type="submit" class="btn btn-success" style="width:100%;margin-top:7px;">
                        <i class="fa fa-floppy-o"></i> update
                    </button>

                </div>
            </form>

        </div>

    @endforeach

    <div class="row">
        <?php echo $scenes->appends([
                'q'           => $query_string,
                'tag_q'       => $tag_q,
                'publish_for' => $publish_for,
                'duration'    => $duration
        ])->render(); ?>
    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

<!-- Modal Tag Tiers-->
<div class="modal fade" id="TagTiersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('panel._sticker')

</body>
</html>
