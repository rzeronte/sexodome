<div class="col-md-12 detail-analytics">

    <div class="row">
        <form class="form-update-google-data" action="{{route('updateGoogleData', ['locale' => $locale, 'site_id' => $site->id])}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2">
                        <label>GA View:</label>
                    </div>
                    <div class="col-md-3">
                        <input name="ga_view_{{$site->id}}" value="{{$site->ga_account}}" class="form-control" style="width: 100px;margin-bottom:10px;" placeholder="GAView"/>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class=" btn btn-primary"style=""><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                @if ($site->ga_account)
                    <button type="button" class="seo-info-keywords btn btn-success text-right" data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('siteKeywords', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-link"></i> Top Keywords</button>
                    <button type="button" class="seo-info-keywords btn btn-success" data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('siteReferrers', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-send"></i> Top Referrers</button>
                    <button type="button" class="seo-info-keywords btn btn-success" data-toggle="modal" data-target="#modal-sexodome" data-url="{{route('sitePageViews', ['locale'=>$locale, 'site_id'=>$site->id])}}" style=""><i class="glyphicon glyphicon-thumbs-up"></i> Most Visited pages</button>
                @endif
            </div>
        </form>
    </div>

    @if ($site->ga_account)
        <?php $data = $site->getAnalytics($fi, $ff)->get(); ?>
        <?php $rangeDates = \App\rZeBot\rZeBotUtils::date_range($fi, $ff, '+1 day', 'Y-m-d')?>

        <div class="row">
            <div class="col-md-12">
                <div id="graph_site_{{$site->id}}" style="padding:5px;width:1024px; height:400px;border: solid 1px cornflowerblue;margin-top:10px;" class="text-center"></div>
            </div>
        </div>
        <script type="text/javascript">
            <?php
            $arrayDates = [];
            foreach($rangeDates as $date) {
                $arrayDates[] = $date;
            }
            ?>

            $(function () {
                serieVisitors = {
                    name: 'Visitors',
                    data: [@foreach($data as $day) {{$day->visitors}}, @endforeach]
                };

                seriePageView = {
                    name: 'PageViews',
                    data: [@foreach($data as $day) {{$day->pageviews}}, @endforeach]
                };

                $('#graph_site_<?=$site->id?>').highcharts({
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
                    series: [serieVisitors, seriePageView]
                });
            });
        </script>
    @else
    <p>No chart available</p>
    @endif

</div>
