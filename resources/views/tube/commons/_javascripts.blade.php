<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="{{asset('tube/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('tube/bower_components/wow/dist/wow.min.js')}}"></script>
<script src="{{asset('tube/js/main.js')}}"></script>


<!-- Bootstrap JS-->
<script src="{{asset('js/bootstrap.min.js')}}"></script>

<!-- JQuery-->
<script src="{{asset('js/jquery-2.1.4.min.js')}}"></script>

<!-- Bootstrap JS-->
<script src="{{asset('js/bootstrap.min.js')}}"></script>

<!-- JSsocials JS-->
<script src="{{asset('jssocials-1.4.0/jssocials.js')}}"></script>
<link rel="stylesheet" href="{{asset('jssocials-1.4.0/jssocials.css')}}">

<!-- Font -->
<script type="text/javascript" src="{{asset('js/front.js')}}"></script>

<!--[if lt IE 9]>
<script src="{{asset('js/html5shiv.min.js')}}"></script>
<script src="{{asset('js/respond.min.js')}}"></script>
<![endif]-->

<script src="{{asset('js/popunders.js')}}"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- custom js -->
<?php echo App::make('sexodomeKernel')->getSite()->javascript ?>
