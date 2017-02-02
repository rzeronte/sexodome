<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.jcarousel.min.css')}}">
    <script src="{{asset('js/jquery.jcarousel.min.js')}}"></script>
    <script src="{{asset('js/jcarousel.responsive.js')}}"></script>
</head>

<body style="background-color: @if (ctype_xdigit(Request::input('c', 'ffffff'))) #{{Request::input('c', 'ffffff')}} @else #fffff @endif;">
        <!-- Wrapper for slides -->
        <?php $i = 0 ?>
        <section>
            <div class="container">
                <div class="row">
                    <h2>{{$site->getHost()}}</h2>

                    <div class="jcarousel-wrapper col-md-12">
                        <div class="jcarousel">
                            <ul>
                                @foreach ($categories as $scene)
                                    <li>
                                        <?php $translation = $scene->translations()->where('language_id',$language->id)->first(); ?>
                                        <div class="scene">
                                            @if ($translation)
                                                <a href="{{route('category', ['profile'=>$site->getHost(),'permalink' => $translation->permalink])}}?utm_source=ads_{{$language->domain}}" alt="{{$translation->title}}" target="_blank">
                                                    <p class="text">{{$translation->name}}</p>
                                                    <?php $srcThumbnail = asset('/thumbnails/'.md5($translation->thumb).".jpg")?>
                                                    <img src="{{$srcThumbnail}}" alt="{{ucwords($translation->name)}}">
                                                </a>
                                            @endif
                                        </div>
                                    </li>
                                    <?php $i++;?>
                                @endforeach
                            </ul>
                        </div>
                        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                        <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                        <p class="jcarousel-pagination"></p>
                    </div>

                </div>

            </div>

        </section>


<style>

    body{
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        padding: 40px;
        padding-top: 0px;
    }

    .jcarousel-wrapper{
        width: 100%;
        height: auto;
    }

    .scene{
        width: 100%;
    }

    .scene img{
        width: 100%;
        height: 150px !important;
    }

    .scene a{
        width: 100%;
        height: 100%;
    }

    .scene:hover{
        font-weight: bold;
        text-decoration: none;
    }

    .text {
        width: 100%;
        font-size: 14px;
        background-color: black;
        color: white;
        opacity: 0.7;
        text-align: center;
        padding: 0;
        margin: 0;
    }

    h2 {
        margin:0;
        padding: 0;
    }

</style>

<script>
    $('.jcarousel').jcarousel({
        'visible': 6
    });
</script>
</body>
</html>