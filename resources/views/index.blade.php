<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('layout_admin._head')

<body style="background-color: dimgray;">

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <form action="{{ route('content', ['locale'=>$locale]) }}" method="get" style="width:100%">
            <div class="col-md-2">
                <input id="query_string" name="q" type="text" placeholder="title search" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
            </div>
            <div class="col-md-2">
                <input id="query_tags" name="tag_q" type="text" placeholder="tag search" class="form-control query_string" value="{{$tag_q}}" style="width:100%;">
            </div>
            <div class="col-md-2">
                <select name="duration" class="form-control">
                    <option value="">any duration</option>
                    <option value="300" @if($duration == 300) selected @endif>min5min</option>
                    <option value="360" @if($duration == 360) selected @endif>min6min</option>
                    <option value="420" @if($duration == 420) selected @endif>min7min</option>
                    <option value="480" @if($duration == 480) selected @endif>min8min</option>
                    <option value="540" @if($duration == 540) selected @endif>min9min</option>
                    <option value="600" @if($duration == 600) selected @endif>min10min</option>
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-control" name="publish_for" style="width:100%">
                    <option value="">all</option>
                    <option value="notpublished">not published</option>
                    @foreach($sites as $site)
                        @if ($site["name"] == $publish_for)
                            <option value="{{$site['name']}}" selected>{{$site['name']}}</option>
                        @else
                            <option value="{{$site['name']}}">{{$site['name']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">search</button>
            </div>
        </form>
    </div>

    <div class="row" style="background-color:white;padding:10px;padding-bottom:15px;">
        <div class="col-md-12">
            <p><b>{{ number_format($total_scenes, 0, ",", ".") }}</b> scenes found for:
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

    <?php $loop = 0 ?>
    @foreach($scenes as $scene)
        <?php
            $thumbs = json_decode($scene->thumbs);
            $index = rand(0, count($thumbs)-1);
            $loop++;

            if ($loop % 2) {
                $bgColor = '#e8e8e8';
            } else {
                $bgColor = 'lightyellow';
            }
        ?>

        <div class="row coloreable" style="background-color:<?=$bgColor?>;padding: 5px;">

                <div class="col-md-2">
                    <img title="{{$scene->permalink}}" src="<?=htmlspecialchars($scene->preview)?>" class="img-responsive thumbnail"/>

                    <small>
                        <b>
                            {{number_format($scene->rate, 2)}}% | {{gmdate("i:s", $scene->duration)}} |  {{ $scene->views+0}} views
                        </b>
                    </small>
                    <br/>

                    @foreach ($languages as $itemLang)
                        <a href="{{route('content', ['locale'=>$itemLang->code,'scene_id'=> $scene->id])}}" target="_blank"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
                    @endforeach

                </div>

                <div class="col-md-5" style="margin: 5px 0 0 0">
                    <form action="{{route('saveTranslation', ['locale'=>$locale, 'scene_id'=>$scene->id])}}" class="ajax-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="text" value="{{$scene->title}}" class="form-control" name="title"/>
                        <textarea class="form-control" style="margin-top:5px;margin-bottom:5px;" name="description">{{$scene->description}}</textarea>
                        <input name="tags" type="text" class="js-tags-<?=$scene->id?>"/>
                        {{--<input type="submit" class="btn btn-primary" value="update" style="margin-right:10px;margin-bottom:5px;"/>--}}

                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-floppy-o"></i> update
                        </button>
                        <script type="text/javascript">
                            var data = [
                                @foreach ($scene->tags()->get() as $tag)
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                '<?= $translation->name?>',
                                @endforeach
                                ];
                            $('.js-tags-<?=$scene->id?>').tagEditor({
                                initialTags: data,
                                autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
                            });
                        </script>

                    </form>

                </div>

                <div class="col-md-3" style="margin: 10px 0 0 0">
                    <small><b>Available in:</b></small><br/>
                    @foreach ($languages as $itemLang)
                        <?php $translation = $scene->translations()->where('language_id',$itemLang->id)->first(); ?>
                            <img src="{{asset("flags/$itemLang->code.png")}}"/>
                        @if (isset($translation->title))
                            <small>[T] </small>
                        @endif
                        @if (isset($translation->description))
                            <small>[D] </small>
                        @endif
                    @endforeach
                    <br/>
                    <br/>
                    <button type="button" class="btn-publication-info btn btn-success" data-toggle="modal" data-target="#TagTiersModal" data-url="{{route('scenePublicationInfo', ['locale'=>$locale, 'scene_id'=>$scene->id])}}">
                        <i class="fa fa-random"></i> publication info
                    </button>

                    @if (count($scene->logspublish()->get()) == 0)
                        <small>NoPublished</small>
                    @endif

                </div>
            <form action="{{route('exportScene', ['locale'=>$locale, 'scene_id'=>$scene->id, 'q' => Request::get("q"),'tag_q' => Request::get("tag_q"),  'page' => Request::get("page")])}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="col-md-2" style="margin: 15px 0 0 0">
                    <select class="form-control" name="database" style="width:100%" id="site_select_{{$scene->id}}">
                        @foreach($sites as $site)
                            <option value="{{$site->name}}">{{$site->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1" style="margin: 15px 0 0 0">
                    {{--<input type="submit" value="export" class="btn btn-primary form-control" style=""/> <br/><br/>--}}
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-cloud-upload"></i> export
                    </button><br/><br/>

                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal{{$scene->id}}">
                        <i class="fa fa-eye"></i> preview
                    </button><br/><br/>

                    <button type="button" class="btn-tag-tiers btn btn-primary" data-toggle="modal" data-target="#TagTiersModal" data-url="{{route('tagTiersInfo', ['locale'=>$locale])}}" data-scene-id="{{$scene->id}}">
                        <i class="fa fa-tags"></i> tag tiers
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal{{$scene->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php
                                    $iframe = $scene->iframe;
                                    $pattern = "/width=\"[0-9]*\"/";
                                    $iframe = preg_replace($pattern, "width='100%'", $iframe);
                                    $pattern2 = "/width=\"[0-9]*+px\"/";
                                    $pattern = "/width='[0-9]*'/";
                                    $iframe = preg_replace($pattern, "width='100%'", $iframe);
                                    $pattern2 = "/width='[0-9]*+px'/";

                                    $iframe = preg_replace($pattern2, "width='100%'", $iframe);
                                    ?>
                                    <?php echo $iframe;?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    @endforeach

    <div class="row">
        <?php echo $scenes->appends([
                'locale'      => $locale,
                'q'           => $query_string,
                'tag_q'       => $tag_q,
                'publish_for' => $publish_for,
                'duration'    => $duration
        ])->render(); ?>
    </div>
</div>

<!-- Modal Tag Tiers-->
<div class="modal fade" id="TagTiersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                wer
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .successAjax{
        border: solid 3px green;
    }

    .errorAjax{
        border: solid 3px red;
    }

</style>
</body>
</html>
