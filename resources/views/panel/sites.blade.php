<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><i class="glyphicon glyphicon-globe"></i> <b>My sites ({{count( $sites )}})</b></p>
        </div>
    </div>

    <div class="col-md-12 text-right">
        <a href="{{route('addSite', ['locale'=>$locale])}}" class="btn btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Add site </a>
        <br/>
        <br/>
    </div>

    @if(Session::get('error'))
        <p style='color:red'>{{Session::get('error')}}</p>
    @endif

    <div class="row">

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

                <div style="border-bottom: solid 1px gray;margin-bottom:20px;">

                    <p>
                        <b><i class="glyphicon glyphicon-globe"></i>
                            @if ($site->have_domain == 1)
                                <a href="http://{{$site->domain}}" target="_blank">http://{{$site->domain}}</a>
                            @else
                                <a href="http://{{$site->name}}.{{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}" target="_blank">http://{{$site->name}}.{{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}</a>
                            @endif
                        </b>
                    </p>
                </div>

                <div class="row">

                    <div class="col-md-9">
                        <a class="btn btn-primary logo-show-info" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-picture"></i> Logo</a>
                        <a href="{{route('site', ["locale" =>$language->code, "site_id"=>$site->id])}}" class="btn btn-primary" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-pencil"></i> SEO</a>
                        <a class="btn btn-primary google-show-info" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-signal"></i> Google</a>
                        <a href="{{route('tags_admin', ['locale'=>$language->code, "site_id"=>$site->id])}}" class="btn btn-primary" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-tags"></i> Tags</a>
                        <a href="{{route('categories_admin', ['locale'=>$language->code, "site_id"=>$site->id])}}" class="btn btn-primary" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-tag"></i> Categories</a>
                        <a class="btn btn-primary iframe-show-info" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-screenshot"></i> IFrame</a>
                        <a class="btn btn-primary colors-show-info" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-tint"></i> Colors</a>
                        <a href="{{route('deleteSite', ['locale'=>$language->code, "site_id"=>$site->id])}}" class="btn btn-danger" style="margin-bottom:10px;margin-right:5px;"><i class="glyphicon glyphicon-trash"></i> DELETE SITE</a>
                    </div>

                </div>

                <div class="clearfix"></div>

                <div class="col-md-12 detail-colors" style="display:none">
                    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                        <p><b>Colors</b></p>
                    </div>

                    <form action="{{route('updateColors', ['locale' => $locale, 'site_id' => $site->id])}}" class="form-update-color-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                        <div class="col-md-2">
                            <input type="text" name="color" class="color form-control"/>
                        </div>

                        <div class="col-md-2">
                            <input type="text" name="color2" class="color form-control" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="color3" class="color form-control" />
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
                        </div>

                    </form>

                </div>

                <div class="col-md-12 detail-logo" style="display:none">
                    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                        <p><b>Logo</b></p>
                    </div>
                    <form action="{{route('updateLogo', ['locale' => $locale, 'site_id' => $site->id])}}" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                        <div class="col-md-1">
                            @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="border: solid 1px gray; max-height: 50px;"/>
                            @endif
                        </div>

                        <div class="col-md-5">
                            <input type="file" name="logo"/>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
                        </div>

                   </form>
                </div>

                <div class="col-md-12 detail-iframe" style="display:none;">

                    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                        <p><b>Iframe</b></p>
                    </div>

                    <div class="row" style="margin-bottom:10px;">
                        <form class="form-update-iframe-data" action="{{route('updateIframeData', ['locale' => $locale, 'site_id' => $site->id])}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <div class="col-md-5">
                                <select name="iframe_site_id_{{$site->id}}" class="form-control">
                                    <option value="">No iframe</option>
                                    @foreach($sites as $sit)
                                        @if ($sit->have_domain == 1)
                                            <option value="{{$sit->id}}" @if ($site->iframe_site_id == $sit->id) selected @endif>{{$sit->domain}}</option>
                                        @else
                                            <option value="{{$sit->id}}" @if ($site->iframe_site_id == $sit->id) selected @endif>{{$sit->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
                            </div>
                        </form>

                    </div>

                </div>

                <div class="col-md-12 detail-analytics" style="display:none;">
                    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                        <p><b>Google Analytics</b></p>
                    </div>

                    <div class="row">
                        <form class="form-update-google-data" action="{{route('updateGoogleData', ['locale' => $locale, 'site_id' => $site->id])}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input name="ga_view_{{$site->id}}" value="{{$site->ga_account}}" class="form-control" style="width: 100px;margin-bottom:10px;" placeholder="GAView"/>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class=" btn btn-primary" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('siteKeywords', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                @if ($site->ga_account)
                                    <button type="button" class="seo-info-keywords btn btn-success text-right" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('siteKeywords', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-link"></i> Top Keywords</button>
                                    <button type="button" class="seo-info-keywords btn btn-success" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('siteReferrers', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-send"></i> Top Referrers</button>
                                    <button type="button" class="seo-info-keywords btn btn-success" data-toggle="modal" data-target="#SEOInfoModal" data-url="{{route('sitePageViews', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-thumbs-up"></i> Most Visited pages</button>
                                @endif
                            </div>
                        </form>
                    </div>

                        <?php $data = $site->getAnalytics($fi, $ff)->get(); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="graph_site_{{$site->id}}" style="padding:5px;width:1024px; height:150px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
                            </div>
                        </div>
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

            </div>
        @endforeach

        <div class="col-md-12" style="display: none;">
            <div id="graph_site_global" style="padding:5px;width: 100%; height:300px;border: solid 1px cornflowerblue;margin:5px;"></div>

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
                        width:1024,
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

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

<script type="text/javascript">
    $('.color').colorpicker({});
</script>

</body>
</html>
