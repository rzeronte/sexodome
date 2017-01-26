<div class="category-thumbs-{{$category->id}}" style="width:100%;">
    <?php $pos = 0 ?>
    @foreach ($scenes as $scene)
        <img data-category-id="{{$category->id}}" data-thumb-number="{{$pos}}" class="category-thumb-image-selector" src="{{$scene->preview}}" style="width:100px;margin:14px;"/>
        <?php $pos++ ?>
    @endforeach
</div>
