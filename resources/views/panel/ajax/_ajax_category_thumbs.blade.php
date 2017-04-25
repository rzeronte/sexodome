<div class="category-thumbs-{{$category->id}}" style="text-align:center; width:100%;">

<?php $pos = 0 ?>
@foreach ($filenames as $filename)
    <img data-category-id="{{$category->id}}" data-thumb-number="{{$pos}}" class="category-thumb-image-selector" src="http://{{ $category->site->getHost()."/categories_market/".$filename }}" style="width:90px;margin:15px;"/>
    <?php $pos++ ?>
@endforeach


</div>
