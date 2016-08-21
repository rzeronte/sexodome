<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div id="ajaxUrls" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
        <br/>
    </div>

    <div id="graph_site_global" style="width:100%;height:200px;border: solid 1px black;">

    </div>

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
    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

</body>
</html>
