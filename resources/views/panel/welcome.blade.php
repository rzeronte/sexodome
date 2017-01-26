<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <head>
        <title>Porn Tube Generator, the best way to be a porn webmaster</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Web platform designed for creating porn websites easily. The best porn content for webmasters in sexodome.com">

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

        <!-- jQuery -->
        <script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
        <meta name="robots" content="noindex">
    </head>
</head>
<body>

<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
            <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand page-scroll" href="#page-top" style="margin:0;padding:0;">
            <img src="{{asset('images/logo.png')}}">
        </a>
    </div>
    <div class="clearfix"></div>
    <div>
        <h2>Welcome to Sexodome</h2>
        <a href="/home" class="btn btn-default">Let's start!</a>
    </div>
</div>

@include('panel._sticker')
@include('panel._modal')
</body>
</html>
