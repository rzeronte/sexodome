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


    <div class="row" style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <div class="col-md-11">
            <i class="glyphicon glyphicon-globe"></i> <b>My sites ({{$sites->total()}})</b>
        </div>
        <div class="col-md-1">
        </div>
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
                <div class="row">
                    <div class="col-md-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <a href="http://{{$site->getHost()}}" target="_blank">
                            <b>http://{{$site->getHost()}}</b>
                        </a>
                        <br/>
                        <small>({{$site->getTotalScenes()}} active scenes)</small>

                    </div>
                    <div class="col-md-10">
                        @include('panel.site._site_menu')
                    </div>
                </div>

                <div class="clearfix"></div>

                @include('panel.site._site_colors')

                @include('panel.site._site_logo')

                @include('panel.site._site_iframe')

                @include('panel.site._site_analytics')

                @include('panel.site._site_seo')

                @include('panel.site._site_categories')

                @include('panel.site._site_tags')

                @include('panel.site._site_workers')

                @include('panel.site._site_import_scenes')

                @include('panel.site._site_cronjobs')
            </div>
        @endforeach

    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <?php echo $sites->render(); ?>
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

</body>
</html>
