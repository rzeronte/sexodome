<head>
    <title>{{$language->title}}</title>

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

<?php echo $scene->iframe?>
<div class="row">
    <div class="col-md-6">
        <a href="{{route('index')}}" target="_blank" alt="{{$language->title}}"><img src="{{asset('logo.png')}}"/></a>
    </div>

    <div class="col-md-6">
        @foreach ($scene->tags()->get() as $tag)
            <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
            <div class="col-md-2">
                <a href="{{ route('tag', array('permalink'=> $translation->permalink )) }}" class="tag">
                    <small>{{ $translation->name}}</small>
                </a>
            </div>
        @endforeach

    </div>

</div>
