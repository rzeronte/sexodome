    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$seo_title}}</title>
    <meta name="description" content="{{$seo_description}}" />
    <meta name="language" content="{{App::getLocale()}}" />

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

    <script async src="{{asset('js/jquery-2.1.4.min.js')}}"></script>

    <!--Bootstrap JS-->
    <script async src="{{asset('js/bootstrap.min.js')}}"></script>

    <script async type="text/javascript" src="{{asset('js/front.js')}}"></script>

    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#e74c3c">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">

    {{--<!--Bootstrap and Other Vendors-->--}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <!--[if lt IE 9]>
    <script async src="{{asset('js/html5shiv.min.js')}}"></script>
    <script async src="{{asset('js/respond.min.js')}}"></script>
    <![endif]-->

    <script async src="{{asset('js/popunders.js')}}"></script>

    <link rel="shortcut icon" href="favicon.ico"/>

    {{-- Theme Styles --}}
    <link rel="stylesheet" href="{{asset('css/default/style.css')}}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('tubeThemes/'.$site->getCSSThemeFilename())}}">