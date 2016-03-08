<div class="scene-thumbs-{{$scene->id}}" style="width:100%;">
    <?php $pos = 0 ?>
    @foreach (json_decode($scene->thumbs) as $thumb)
        @if ($scene->thumb_index == $pos)
            <img data-scene-id="{{$scene->id}}" data-thumb-number="{{$pos}}" class="scene-thumb-image-selector" src="{{$thumb}}" style="width:100px;margin:14px;border: solid 4px green !important;"/>
        @else
            <img data-scene-id="{{$scene->id}}" data-thumb-number="{{$pos}}" class="scene-thumb-image-selector" src="{{$thumb}}" style="width:100px;margin:14px;"/>
        @endif
        <?php $pos++ ?>
    @endforeach
</div>
