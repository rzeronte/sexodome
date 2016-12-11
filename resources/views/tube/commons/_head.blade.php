    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$seo_title}}</title>
    <meta name="description" content="{{$seo_description}}" />
    <meta name="language" content="{{App::getLocale()}}" />

    <!--Favicons-->
    <link rel="icon" href="{{asset('/favicons/'.md5($site->id).".png")}}">
    <meta name="RATING" content="RTA-5042-1996-1400-1577-RTA" />

    {!! Minify::stylesheet(array('/css/bootstrap.min.css', '/css/default/style.css')) !!}
    {!! Minify::stylesheet('/tubeThemes/'.$site->getCSSThemeFilename()) !!}

    <meta name="tubecorporate_com_verify" content="6b49120a79a3e4ba859fe452e09b040a"/>
    <meta name="hubtraffic-domain-validation" content="a896a15b3b3df915" />
    <meta name="xhamster-site-verification" content="270cf0ef4e1be6b74343413034a7384d"/>
    {{--analytics--}}
    @if ($site->google_analytics)
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '{{$site->google_analytics}}', 'auto');
            ga('send', 'pageview');

        </script>
    @endif
