<div class="col-md-12 detail-clicks">
    <?php $dataClicks = $site->getClicks($fi, $ff)->get(); ?>
    <?php $rangeDates = \App\rZeBot\rZeBotUtils::date_range($fi, $ff, '+1 day', 'Y-m-d')?>

    <div class="row">
        <div class="col-md-12">
            <div id="graph_site_clicks_{{$site->id}}" style="padding:5px;width:1024px; height:400px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
        </div>
    </div>
    <script type="text/javascript">
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

        // Built in Highcharts date formatter based on the PHP strftime (see API reference for usage)
        Highcharts.dateFormat("Month: %m Day: %d Year: %Y", 20, false);
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
    </script>

</div>
