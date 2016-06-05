<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}}</title>

    {{--analytics--}}

    {{--<!--Bootstrap and Other Vendors-->--}}
    <link rel="stylesheet" href="{{asset('vendor/TubeFront/css/bootstrap.min.css')}}">

    {{--<!--Theme Styles-->--}}
    <link rel="stylesheet" href="{{asset('vendor/TubeFront/css/default/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/TubeFront/css/responsive/responsive.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>

    <?=$language->google_analytics?>

    <!--[if lt IE 9]>
    <script src="{{asset('vendor/TubeFront/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('vendor/TubeFront/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-2.2.2.min.js" integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</head>
<body style="background-color: black;">
    <?php $i=0; ?>
    <div class="content" style="padding:25px;">
        <div class="row">
        @foreach ($scenes as $scene)
            <div class="col-md-2">
                <?php $translation = $scene->translations()->where('language_id',$language->id)->first(); ?>
                @if ($translation)
                    <a href="{{route('video', ['permalink' => $translation->permalink])}}?utm_source=ads_{{$language->domain}}" alt="{{$translation->title}}" target="_blank">
                        <img src="{{$scene->preview}}" alt="{{$translation->title}}" style="border: solid 1px black; width: 100%;">
                        <p style="margin-top:3px;font-size:12px;font-family: Permanent Marker;">{{$translation->title}}</p>
                    </a>
                @endif

            </div>
            <?php $i++; ?>
        @endforeach
        </div>
    </div>
</body>
</html>
