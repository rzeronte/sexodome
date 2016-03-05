<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('layout_admin._head')

<body style="background-color: dimgray;">

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row"  style="background-color:white;">
        <form action="" method="post">
        <div class="col-md-12">
            <h2>Tag tiers</h2>
        </div>
        <div class="col-md-12 text-right">
            <input type="submit" class="btn btn-primary" value="Update"/>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <div id="graph_site_global" style="width:100%; height:300px;border: solid 1px black;"></div>

            <script type="text/javascript">
                $(function () {
                    serieVisitorsGlobal = {
                        name: 'Visitors',
                        data: [
                        <?php
                            $begin = new DateTime( $fi );
                            $end   = new DateTime( $ff );

                            for($i = $begin; $begin <= $end; $i->modify('+1 day')){
                                $visitors=0;
                                $pageViews=0;
                                foreach($sites as $site) {
                                    $data = $site->getAnalytics($i->format("Y-m-d"), $i->format("Y-m-d"))->first();
                                    if ($data) {
                                        $visitors+=$data->visitors;
                                        $pageViews+=$data->pageviews;
                                    }
                                }
                                echo $visitors.",";
                            }
                        ?>
                        ]
                    };
                    seriePageViewGlobal = {
                        name: 'PageViews',
                        data: [
                            <?php
                                $begin = new DateTime( $fi );
                                $end   = new DateTime( $ff );

                                for($i = $begin; $begin <= $end; $i->modify('+1 day')){
                                    $visitors=0;
                                    $pageViews=0;
                                        foreach($sites as $site) {
                                        $data = $site->getAnalytics($i->format("Y-m-d"), $i->format("Y-m-d"))->first();
                                        if ($data) {
                                            $visitors+=$data->visitors;
                                            $pageViews+=$data->pageviews;
                                        }
                                    }
                                    echo $pageViews.",";
                                }
                            ?>
                        ]
                    };

                    $('#graph_site_global').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Visitas in all network'
                        },
                        xAxis: {
                            categories: ['Days']
                        },
                        yAxis: {
                            title: {
                                text: 'Number'
                            }
                        },
                        series: [serieVisitorsGlobal, seriePageViewGlobal]
                    });
                });
            </script>
        </div>

        <?php $loop = 0 ?>
        @foreach($sites as $site)
            <?php
            $loop++;

            if ($loop % 2) {
                $bgColor = '#e8e8e8';
            } else {
                $bgColor = 'lightyellow';
            }
            ?>
            <div class="col-md-12">

                <?php $data = $site->getAnalytics($fi, $ff)->get(); ?>

                <div id="graph_site_{{$site->id}}" style="width:100%; height:300px;border: solid 1px black;"></div>
                <script type="text/javascript">
                    $(function () {
                        serieVisitors = {
                            name: 'Visitors',
                            data: [
                                @foreach($data as $day) {{$day->visitors}}, @endforeach
                            ]
                        };

                        seriePageView = {
                            name: 'PageViews',
                            data: [
                                @foreach($data as $day) {{$day->pageviews}}, @endforeach
                            ]
                        };

                        $('#graph_site_<?=$site->id?>').highcharts({
                            chart: {
                                type: 'line'
                            },
                            title: {
                                text: 'Visitas in {{$site->domain}}'
                            },
                            xAxis: {
                                categories: ['Days']
                            },
                            yAxis: {
                                title: {
                                    text: 'Number'
                                }
                            },
                            series: [serieVisitors, seriePageView]
                        });
                    });
                </script>
            </div>

            <div class="col-md-12" style="background-color:<?=$bgColor?>;padding:10px;">

                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="row">
                    <div class="col-md-3">
                        <img src="{{asset('favicons/favicon-'.$site->name.'.png')}}" style="float:left;"/>
                        <span style="margin-left:5px;">http://{{$site->domain}}</span>
                    </div>
                    <div class="col-md-3">
                        <label>Tier1:</label>
                        <input name="tier1_{{$site->id}}" type="text" class="js_tags_tier1 ajax-form form-control js-tags1-<?=$site->id?>" style="margin:10px;"/>
                    </div>

                    <div class="col-md-3">
                        <label>Tier2:</label>
                        <input name="tier2_{{$site->id}}" type="text" class="js_tags_tier2 ajax-form form-control js-tags2-<?=$site->id?>" style="margin:10px;"/>
                    </div>

                    <div class="col-md-3">
                        <label>Tier3:</label>
                        <input name="tier3_{{$site->id}}" type="text" class="js_tags_tier3 ajax-form form-control js-tags3-<?=$site->id?>" style="margin:10px;"/>

                        <script type="text/javascript">
                            var data1 = [
                                @foreach ($site->tags()->where('tipo', 'tier1')->get() as $tag)
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                '<?= $translation->name?>',
                                @endforeach
                            ];

                            var data2 = [
                                @foreach ($site->tags()->where('tipo', 'tier2')->get() as $tag)
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                '<?= $translation->name?>',
                                @endforeach
                            ];

                            var data3 = [
                                @foreach ($site->tags()->where('tipo', 'tier3')->get() as $tag)
                                <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
                                '<?= $translation->name?>',
                                @endforeach
                            ];

                            $('.js-tags1-<?=$site->id?>').tagEditor({
                                initialTags: data1,
                                removeDuplicates: true,
                                autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
                            });

                            $('.js-tags2-<?=$site->id?>').tagEditor({
                                initialTags: data2,
                                removeDuplicates: true,
                                autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
                            });

                            $('.js-tags3-<?=$site->id?>').tagEditor({
                                initialTags: data3,
                                removeDuplicates: true,
                                autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
                            });
                        </script>
                    </div>
                </div>
                <div class="clearfix"></div>

            </div>
        @endforeach
        <br/>
    </form>

    </div>
</div>
<style>
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
