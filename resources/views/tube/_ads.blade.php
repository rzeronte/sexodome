<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="adsCarousel" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <?php $i = 0 ?>
        <h2 style="color:white">{{$siteIframe->getHost()}}</h2>
        <div class="carousel-inner" role="listbox">
        @foreach ($categories->chunk(6) as $chunk)
            <div class="item @if ($i==0)active @endif">
                @foreach ($chunk as $scene)
                    <div class="scene">
                        <?php $translation = $scene->translations()->where('language_id',$language->id)->first(); ?>
                        @if ($translation)
                            <a href="{{route('category', ['profile'=>$siteIframe->getHost(),'permalink' => $translation->permalink])}}?utm_source=ads_{{$language->domain}}" alt="{{$translation->title}}" target="_blank">
                                <p class="text">{{$translation->name}}</p>
                                <img src="{{$translation->thumb}}" alt="{{$translation->name}}">
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
            <?php $i++;?>
        @endforeach
        </div>

                <!-- Left and right controls -->
        <a class="left carousel-control" href="#adsCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#adsCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
</div>

<style>
    body{
        overflow: hidden;
        margin: 0;
        padding: 0;
    }
    .item{
    }
    .scene{
        width:175px;
        float:left;
        margin-right: 4px !important;
        border: solid 1px black;
        box-sizing: border-box;
    }
    .scene:hover{
        font-weight: bold;
        text-decoration: none;
    }

    .scene img{
        width: 100%;
        height: 130px;
    }

    .carousel-inner{
        margin-left: 150px;
    }

    .text{
        width: 100%;
        font-size: 14px;
        background-color: black;
        color: white;
        opacity: 0.7;
        text-align: center;
        padding: 0;
        margin: 0;
    }
    h2{
        margin:0;
        padding: 0;
        margin-left: 150px;
    }

    #adsCarousel{
        background-color: gray;
        padding-bottom: 20px;
    }
</style>

</body>
</html>