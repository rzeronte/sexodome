<div class="col-md-12 detail-clicks">
    <?php $dataClicks = $site->getClicks($fi, $ff)->get(); ?>
    <?php $rangeDates = \App\rZeBot\rZeBotUtils::date_range($fi, $ff, '+1 day', 'Y-m-d')?>

    <div class="row">
        <div class="col-md-12">
            <div id="graph_site_clicks_{{$site->id}}" style="padding:5px;width:1024px; height:150px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            serieVisitors = {
                name: 'Clicks',
                data: [
                    <?php $array = [];
                    foreach($rangeDates as $date) {
                        $dateExists = false;
                        foreach($dataClicks as $dayClick) {
                            if ($dayClick->dia == $date) {
                                $array[] = $dayClick->total;
                                $dateExists = true;
                            }
                        }
                        if ($dateExists == false) {
                            $array[] = 0;
                        }
                    }
                    echo implode(",", $array);
                    ?>
                ]
            };

            $('#graph_site_clicks_<?=$site->id?>').highcharts({
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
                series: [serieVisitors]
            });
        });
    </script>

</div>
