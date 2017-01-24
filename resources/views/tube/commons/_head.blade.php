    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$seo_title}}</title>
    <meta name="description" content="{{$seo_description}}" />
    <meta name="language" content="{{App::getLocale()}}" />

    <!--Favicons-->
    <link rel="icon" href="{{asset('/favicons/'.md5($site->id).".png")}}">
    <meta name="RATING" content="RTA-5042-1996-1400-1577-RTA" />

    <link rel="stylesheet" href="{{asset('css/default/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('tubeThemes/'.$site->getCSSThemeFilename())}}">

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
