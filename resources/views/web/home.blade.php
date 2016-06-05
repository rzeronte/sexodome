<!DOCTYPE html>
<html lang="en">

<head>

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

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<!-- Navigation -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top" style="margin:0;padding:0;">
                <img src="{{asset('images/logo.png')}}">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a class="page-scroll" href="#howitworks">How it works</a>
                </li>
                <li>
                    <a class="page-scroll" href="#features">Features</a>
                </li>
                <li>
                    <a href="{{ route('login') }}" class="page-scroll" href="#contact">Login</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Intro Header -->
<header class="intro">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="brand-heading">porn webmaster tool</h1>
                    <p class="intro-text" style="opacity: 0.8;background-color: black;color: white;font-family:Montserrat,'Helvetica Neue',Helvetica,Arial,sans-serif">Agile platform for creating web pages porn<br>No technical knowledge</p>
                    <a href="#howitworks" class="btn btn-circle page-scroll">
                        <i class="fa fa-angle-double-down animated"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- About Section -->
<section id="howitworks" class="container content-section text-center">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2" style="font-family:Montserrat,'Helvetica Neue',Helvetica,Arial,sans-serif">
            <h2>How it works</h2>
            <p>In <strong style="color:red">sexodome</strong> you can generate your porn web pages for free. Also allow link your own domains if you have them without additional costs.</p>
            <p><strong style="color:red">sexodome</strong> integrates with most providers of free content on the internet and allows your selections content of them for your website without technical knowledge.</p>
            <p><b>the hard work is <strong style="color:red">done</strong>!</b></p>
        </div>
    </div>
</section>

<!-- Download Section -->
<section id="features" class="content-section text-center">
    <div class="download-section">
        <div class="container">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Features</h2>

                <div class="row" style="font-family:Montserrat,'Helvetica Neue',Helvetica,Arial,sans-serif">
                    <div class="col-md-3">
                        <p>Integration with free content channels</p>
                    </div>
                    <div class="col-md-3">
                        <p>Publisher configurable automatic</p>
                    </div>
                    <div class="col-md-3">
                        <p>Taxonomies: tags and categories</p>
                    </div>
                    <div class="col-md-3">
                        <p>Integration with Google Analytics</p>
                    </div>
                    <div class="col-md-3">
                        <p>SEO settings for each website</p>
                    </div>
                    <div class="col-md-3">
                        <p>Setting simple subjects per site</p>
                    </div>
                    <div class="col-md-3">
                        <p>Iframes associated with your own websites</p>
                    </div>
                    <div class="col-md-3">
                        <p>Several languages available</p>
                    </div>

                </div>

                <a href="{{route('register')}}" class="btn btn-default btn-lg">Create new account</a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
{{--<section id="contact" class="container content-section text-center">--}}
    {{--<div class="row">--}}
        {{--<div class="col-lg-8 col-lg-offset-2">--}}
            {{--<h2>Contact Start Bootstrap</h2>--}}
            {{--<p>Feel free to email us to provide some feedback on our templates, give us suggestions for new templates and themes, or to just say hello!</p>--}}
            {{--<p><a href="mailto:feedback@startbootstrap.com">feedback@startbootstrap.com</a>--}}
            {{--</p>--}}
            {{--<ul class="list-inline banner-social-buttons">--}}
                {{--<li>--}}
                    {{--<a href="https://twitter.com/SBootstrap" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--<a href="https://github.com/IronSummitMedia/startbootstrap" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--<a href="https://plus.google.com/+Startbootstrap/posts" class="btn btn-default btn-lg"><i class="fa fa-google-plus fa-fw"></i> <span class="network-name">Google+</span></a>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</section>--}}

<!-- Map Section -->
<div id="map" style="display:none;"></div>

<!-- Footer -->
<footer>
    <div class="container text-center">
        <p>sexodome.com {{date("Y")}}</p>
    </div>
</footer>

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