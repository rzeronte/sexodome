<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="{{asset('/slick/slick.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{asset('slick/slick.css')}}" />
    <link rel="stylesheet" href="{{asset('slick/slick-theme.css')}}" />

</head>

<body style="background-color: @if (ctype_xdigit(Request::input('c', 'ffffff'))) #{{Request::input('c', 'ffffff')}} @else #fffff @endif;">
        <!-- Wrapper for slides -->
    <?php $i = 0 ?>
    <h4> <p><i class="glyphicon glyphicon-th"></i> {{$site->getHost()}}</p></h4>

    <div class="slick">
        @foreach ($categories as $scene)
            <?php $translation = $scene->translations()->where('language_id',$language->id)->first(); ?>
            <div class="scene">
                @if ($translation)
                    <a href="{{route('category', ['profile'=>$site->getHost(),'permalink' => $translation->permalink])}}?utm_source={{$site->domain}}&utm_medium={{$translation->name}}&utm_campaign=iframe_sexodome" alt="{{$translation->title}}" target="_blank">
                        <p class="text">{{ucwords($translation->name)}}</p>
                        <?php $srcThumbnail = asset('/thumbnails/'.md5($translation->thumb).".jpg")?>
                        <img src="{{$srcThumbnail}}" alt="{{ucwords($translation->name)}}">
                    </a>
                @endif
            </div>
            <?php $i++;?>
        @endforeach
    </div>

<style>

    body{
        margin: 0;
        padding: 0;
    }
    .scene {
        padding-right: 15px;
    }
    .scene img{
        width: 100%;
        height: 150px;
    }

    .text {
        width: 100%;
        font-size: 14px;
        background-color: black;
        color: white;
        text-align: center;
        padding: 0;
        margin: 0;
        @if (ctype_xdigit(Request::input('c10', 'black')))
            background-color: #{{Request::input('c10', 'black')}}
        @else
            background-color: gray;
        @endif;

        @if (ctype_xdigit(Request::input('c11', 'black')))
            color: #{{Request::input('c11', 'black')}}
        @else
            background-color: gray;
        @endif;
    }

    h4 {
        margin:0;
        padding: 0;
        @if (ctype_xdigit(Request::input('c6', 'black')))
            color: #{{Request::input('c6', 'black')}};
        @else
            color: black;
        @endif;
    }

    .slick-prev{
        width: 30px;
        left: 3em;
        z-index: 1000;
    }

    .slick-next{
        width: 30px;
        z-index: 1000;
        right: 3em;
    }

    .slick-prev:before, .slick-next:before {
        font-family: "Glyphicons Halflings", "slick", sans-serif;
        font-size: 25px;
        @if (ctype_xdigit(Request::input('c10', 'black')))
            background-color: #{{Request::input('c10', 'black')}} !important;
        @else
            background-color: gray;
        @endif;
        @if (ctype_xdigit(Request::input('c6', 'black')))
            color: #{{Request::input('c6', 'black')}};
        @else
            color: black;
    @endif;

    }

    .slick-prev:before { content: "\e257"; }
    .slick-next:before { content: "\e258"; }
</style>

<script>
    $(document).ready(function(){

        $('.slick').slick({
            infinite: true,
            slidesToShow: 6,
            slidesToScroll: 6,
            nextArrow: '<button type="button" class="slick-next">Next</button>',
            prevArrow: '<button type="button" class="slick-prev">Previous</button>',
        });
    });
</script>
</body>
</html>