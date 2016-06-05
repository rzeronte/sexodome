<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div id="ajaxUrls" data-categories-url="{{route('ajaxCategories', ['locale'=> $locale])}}" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="margin-top:20px;">
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
        <form action="{{ route('content', ['locale'=>$locale]) }}" method="get" style="width:100%">
                <div class="col-md-2">
                <input id="query_string" name="q" type="text" placeholder="title search" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-control" style="width:100%;">
                    <option value="">all categories</option>
                    @foreach($categories as $category)
                        <?php $nameTranslated = $category->translations()->where('language_id', "=", $language->id)->first()->name;?>
                        @if ($nameTranslated != "")
                            <option value="{{$category->id}}" @if (\Illuminate\Support\Facades\Request::input('category_id') == $category->id) selected @endif>{{$nameTranslated}} ({{$language->code}}) </option>
                        @endif
                    @endforeach
                </select>
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
                                <option value="{{$site->id}}" selected>{{$site->name}}.{{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}</option>
                            @endif
                        @else
                            @if ($site->have_domain == 1)
                                <option value="{{$site->id}}">{{$site->domain}}</option>
                            @else
                                <option value="{{$site->id}}">{{$site->name}}.{{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}</option>
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
                'locale'      => $locale,
                'q'           => $query_string,
                'tag_q'       => $tag_q,
                'publish_for' => $publish_for,
                'duration'    => $duration
        ])->render(); ?>
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
            <form action="{{route('saveTranslation', ['locale'=>$locale, 'scene_id'=>$scene->id])}}" class="ajax-form">
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
                    <i class="glyphicon glyphicon-open-file"></i> {{ $scene->channel->name}}<br/>
                    <b>http://{{ $scene->site->name}}</b><br/>
                    @foreach ($languages as $itemLang)
                        <a href="{{route('content', ['locale'=>$itemLang->code,'scene_id'=> $scene->id])}}" target="_blank"><img src="{{asset("flags/$itemLang->code.png")}}"/></a>
                    @endforeach

                </div>

                <div class="col-md-5" style="margin: 5px 0 0 0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" class="selectedThumb{{$scene->id}}" name="selectedThumb" value="{{$scene->thumb_index}}"/>

                    <input type="text" value="{{$scene->title}}" class="form-control" name="title"/>
                    <textarea class="form-control" style="margin-top:5px;margin-bottom:5px;height:90px;" name="description" placeholder="Description here...">{{$scene->description}}</textarea>
                    {{--<input type="submit" class="btn btn-primary" value="update" style="margin-right:10px;margin-bottom:5px;"/>--}}

                    {{--<button type="button" class="btn-spin-text btn btn-warning" data-toggle="modal" data-target="#previewModal" data-url="{{route('spinScene', ['locale' => $locale, 'scene_id'=>$scene->id])}}">--}}
                        {{--<i class="glyphicon glyphicon-education"></i> spin text--}}
                    {{--</button><br/><br/>--}}

                    <script type="text/javascript">
                        var data = [
                            @foreach ($scene->tags()->get() as $tag)
                            <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                            '<?= $translation->name?>',
                            @endforeach
                            ];
                        var dataCategories = [
                            @foreach ($scene->categories()->get() as $category)
                            <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
                            '<?= $translation->name?>',
                            @endforeach
                            ];

                        $('.js-tags-<?=$scene->id?>').tagEditor({
                            initialTags: data,
                            autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
                        });

                        $('.js-categories-<?=$scene->id?>').tagEditor({
                            initialTags: dataCategories,
                            autocomplete: { 'source': $("#ajaxUrls").attr('data-categories-url'), minLength: 3 }
                        });

                    </script>
                </div>

                <div class="col-md-3" style="margin: 10px 0 0 0">
                    @if ($scene->embed == 1)
                        <button type="button" class="btn-preview-scene btn btn-primary" data-toggle="modal" data-target="#previewModal" data-scene-id="{{$scene->id}}" data-url="{{route('scenePreview', ['locale' => $locale, 'scene_id'=>$scene->id])}}" style="width:100%">
                            <i class="fa fa-eye"></i> preview
                        </button>
                    @else
                        <a href="{{$scene->iframe}}" target="_blank" class="btn btn-primary"  style="width:100%"><i class="fa fa-eye"></i> preview</a>
                    @endif

                    <br/>

                    <button type="button" class="btn-select-thumb btn btn-primary" data-toggle="modal" data-target="#previewModal" data-url="{{route('sceneThumbs', ['locale' => $locale, 'scene_id'=>$scene->id])}}" style="width:100%;margin-top:7px;">
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
                'locale'      => $locale,
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

<style>
    .successAjax{
        border: solid 3px green;
    }

    .errorAjax{
        border: solid 3px red;
    }

    .js_tags+.tag-editor { background: #fafafa; font-size: 12px; }
    .js_tags+.tag-editor .tag-editor-spacer { width: 7px; }
    .js_tags+.tag-editor .tag-editor-delete { display: none; }
    .js_tags_tier1+.tag-editor .tag-editor-tag {
        color: #ffffff; background: limegreen;
        border-radius: 2px;
    }
    .js_tags_tier2+.tag-editor .tag-editor-tag {
        color: #ffffff; background: orange;
        border-radius: 2px;
    }
    .js_tags_tier3+.tag-editor .tag-editor-tag {
        color: #ffffff; background: deepskyblue;
        border-radius: 2px;
    }
</style>

</body>
</html>