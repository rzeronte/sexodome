<div class="col-md-12 detail-clicks">

    <div class="row">
        <div class="col-md-12">
            <div id="graph_site_clicks_{{$site->id}}" style="padding:5px;width:1024px; height:300px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="graph_site_clicks_ua_{{$site->id}}" style="padding:5px;width:1024px; height:300px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
        </div>
    </div>

    <script type="text/javascript">
        <?php $dataClicks = $site->getClicks($fi, $ff)->get(); ?>
        <?php $dataUAs = $site->getUAs($fi, $ff)->get();?>
        <?php $rangeDates = \App\rZeBot\rZeBotUtils::date_range($fi, $ff, '+1 day', 'Y-m-d')?>

        <?php
        $array = [];
        $arrayDates = [];
        foreach($rangeDates as $date) {
            $dateExists = false;
            foreach($dataClicks as $dayClick) {
                if ($dayClick->dia == $date) {
                    $array[] = $dayClick->total;
                    $arrayDates[] = $date;
                    $dateExists = true;
                }
            }
            if ($dateExists == false) {
                    $array[] = 0;
                    $arrayDates[] = $date;
            }
        }
        ?>

        $(function () {
            serieVisitors = {
                name: 'Clicks',
                data: <?php echo json_encode($array) ?>
            };

            $('#graph_site_clicks_<?=$site->id?>').highcharts({
                chart: {
                    type: 'line'
                },
                title: false,
                xAxis: {
                    categories: <?php echo json_encode($arrayDates) ?>
                },
                yAxis: {
                    title: {
                        text: 'Number'
                    }
                },
                series: [serieVisitors]
            });
        });

        $(function () {
            $('#graph_site_clicks_ua_<?=$site->id?>').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Clicks by User-Agent'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [
                        @foreach($dataUAs as $ua )
                            { name:@if ($ua->name) '{{$ua->name}}' @else 'Undefined' @endif, y: {{$ua->y}} },
                        @endforeach
                    ]
                }]
            });
        })
    </script>

</div>
