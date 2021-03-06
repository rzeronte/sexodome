<head>

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('seo_title')</title>
<meta name="description" content="@yield('seo_description')">
<meta property="og:title" content="@yield('seo_title')">
<meta property="og:description" content="@yield('seo_description')">

<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
<link rel=”canonical” href="{{  Request::url() }}"/>
<link rel="apple-touch-icon" href="apple-touch-icon.png">

<link rel="dns-prefetch" href="https://ajax.googleapis.com">
<link rel="dns-prefetch" href="https://www.google-analytics.com">

<meta name="format-detection" content="telephone=no">
<meta name="hubtraffic-domain-validation"  content="a896a15b3b3df915" />

<!--[if IE]>
<script src="{{asset('tube/bower_components/respond/dest/respond.min.js')}}"></script>
<script src="{{asset('tube/bower_components/html5shiv/dist/html5shiv.min.js')}}"></script>
<![endif]-->

<link rel="stylesheet" href="{{asset('tube/bower_components/bootstrap-material-design-icons/css/material-icons.min.css')}}">
<link rel="stylesheet" href="{{asset('tube/css/main.css')}}">
<link rel="stylesheet" href="{{asset('tubeThemes/'.App::make('sexodomeKernel')->getSite()->getCSSThemeFilename())}}">

<link rel="icon" href="{{asset('/favicons/'.md5(App::make('sexodomeKernel')->getSite()->id).".png")}}">
<meta name="language" content="{{App::getLocale()}}" />
<meta charset="UTF-8">

{{-- meta noindex en demo --}}
@if (App::make('sexodomeKernel')->getSite()->id == env('DEMO_SITE_ID') OR isset($noindex))
<meta name="robots" content="noindex">
@endif

{{--analytics--}}
@if (App::make('sexodomeKernel')->getSite()->google_analytics)
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ App::make('sexodomeKernel')->getSite()->google_analytics}}', 'auto');
        ga('send', 'pageview');

    </script>
@endif

@yield('pagination_seo')

</head>