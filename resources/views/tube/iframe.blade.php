<head>
    <title>{{$sexodomeKernel->getLanguage()->title}}</title>

    <meta name="description" content="{{$language->description}}" />

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{--jquery --}}
    <script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>

    {{--bootstrap 3.3.6--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    {{--analytics--}}
    {{$language->google_analytics}}
</head>

<?php
$iframe = $scene->iframe;
$pattern = "/width=\"[0-9]*\"/";
$iframe = preg_replace($pattern, "width='100%'", $iframe);
$pattern2 = "/width=\"[0-9]*+px\"/";
$iframe = preg_replace($pattern2, "width='100%'", $iframe);
$pattern3 = "/height=\"[0-9]*\"/";
$iframe = preg_replace($pattern3, "height='100%'", $iframe);

?>
<?php echo $iframe?>
