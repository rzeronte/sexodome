<div class="category-thumbs-{{$category->id}}" style="text-align:center; width:100%;">
    <?php $pos = 0 ?>
    @foreach ($scenes as $scene)
        <img data-category-id="{{$category->id}}" data-thumb-number="{{$pos}}" class="category-thumb-image-selector" src="{{$scene->preview}}" style="width:150px;margin:15px;"/>
        <?php $pos++ ?>
    @endforeach
</div>
