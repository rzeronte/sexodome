<!DOCTYPE html>
<html lang="en">

<head>
    <title>See the google position for specific keywords in your urls</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tool to find out their position your urls with keywords you fancy in sexodome.com">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-78770486-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<div class="container">
    <div class="row text-center">
        <br/>
        <img src="{{asset('images/logo.png')}}"/>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @if (isset($position))
                <br/>
                @if ($position > 0)
                    <div class="alert alert-success">'<b>{{$url}}</b>' is <b>{{$position}}</b> position for '<b>{{$keyword}}</b>'</div>
                @else
                    <div class="alert alert-danger">'<b>{{$url}}</b>' have not results for '<b>{{$keyword}}</b>'</div>
                @endif
            @endif
            <form action="{{route('GoogleKeywordPosition')}}" method="get">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <br/>
                <label for="url">URL</label>
                <input class="form-control" type="url" placeholder="http://yoururl.com" name="url" required/>

                <label for="keyword">Keyword</label>
                <input class="form-control" type="text" placeholder="Keywords" name="keyword" required/>
                <br/>
                <input class="form-control btn btn-primary" type="submit" value="Check your URL position in Google's SERPs"/>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<!-- Plugin JavaScript -->
<script src="{{asset('js/jquery.easing.min.js') }}"></script>

<!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('js/front-website.js') }}"></script>

</body>

</html>
