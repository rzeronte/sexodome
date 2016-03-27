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
        <div class="col-md-12 text-right">
            <br/>
            <br/>
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
            <br/>
            <br/>
        </div>
        <div class="col-md-12">
            <div id="graph_site_global" style="padding:5px;width:99%; height:300px;border: solid 1px cornflowerblue;margin:5px;"></div>

            <script type="text/javascript">
                $(function () {
                    serieVisitorsGlobal = {
                        name: 'Visitors',
                        color: '#FF0000',
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
                        color: '#0000FF',
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
                            zoomType: 'xy'
                        },
                        title: false,
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

            <div class="col-md-12" style="background-color:<?=$bgColor?>;padding:10px;">

                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="row">
                    <div class="col-md-4">
                        <h4 style="padding:5px;">http://{{$site->domain}}</h4>

                        <?php $data = $site->getAnalytics($fi, $ff)->get(); ?>

                        <div id="graph_site_{{$site->id}}" style="padding:5px;width:100%; height:180px;border: solid 1px cornflowerblue;"></div>
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
                                    title: false,
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
                    <div class="col-md-8">
                        <div class="col-md-3">
                            <button type="button" class="seo-info-keywords btn btn-success btn-xs" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('siteKeywords', ['locale'=>$locale, 'site_id'=>$site->id])}}" style="width:100%;margin-bottom:5px;"><i class="glyphicon glyphicon-link"></i> Top Keywords</button><br/>
                            <button type="button" class="seo-info-keywords btn btn-success btn-xs" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('siteReferrers', ['locale'=>$locale, 'site_id'=>$site->id])}}" style="width:100%;margin-bottom:5px;"><i class="glyphicon glyphicon-send"></i> Top Referrers</button><br/>
                            <button type="button" class="seo-info-keywords btn btn-success btn-xs" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('sitePageViews', ['locale'=>$locale, 'site_id'=>$site->id])}}" style="width:100%"><i class="glyphicon glyphicon-thumbs-up"></i> Most Visited pages</button>
                        </div>

                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label for="iframe_site_id">Iframe</label>
                                <select name="iframe_site_id_{{$site->id}}" class="form-control">
                                    <option value="">No iframe</option>
                                    @foreach($sites as $sit)
                                        <option value="{{$sit->id}}" @if ($site->iframe_site_id == $sit->id) selected @endif>{{$sit->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="iframe_site_id">Ga View ID</label>
                                <input name="ga_view_{{$site->id}}" value="{{$site->ga_account}}" class="form-control" style="width:100px;"/>
                            </div>

                        </div>
                        <br/>
                        <br/>

                    </div>
                </div>
                <div class="clearfix"></div>

            </div>
        @endforeach
        <br/>
    </form>

    </div>
    <!-- Modal SEO Info -->
    <div class="modal fade" id="SEOInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
