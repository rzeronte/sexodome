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

<script src="{{asset('js/jquery-2.1.4.min.js')}}"></script>

<!--Bootstrap JS-->
<script src="{{asset('js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="{{asset('js/front.js')}}"></script>


<!--[if lt IE 9]>
<script src="{{asset('js/html5shiv.min.js')}}"></script>
<script src="{{asset('js/respond.min.js')}}"></script>
<![endif]-->

<script src="{{asset('js/popunders.js')}}"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
