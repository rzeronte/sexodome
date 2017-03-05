<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
        <br/>
    </div>

    <div id="graph_site_global" style="width:100%;height:400px;border: solid 1px gray;">

    </div>

    <script type="text/javascript">
        <?php $rangeDates = \App\rZeBot\rZeBotUtils::date_range($fi, $ff, '+1 day', 'Y-m-d')?>
        <?php
        $arrayDates = [];
        foreach($rangeDates as $date) {
            $arrayDates[] = $date;
        }
        ?>

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
                    zoomType: 'xy'
                },
                title: {
                    text: 'Global analytics from your network'
                },
                width:1024,
                xAxis: {
                    categories: <?php echo json_encode($arrayDates) ?>
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
