<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="{{route('site', ['site_id' => $site->id])}}" class="btn btn-success center-block">Back to {{$site->getHost()}}</a>
        </div>

        <div class="col-md-4">
            <a href="{{route('orderCategories', ['site_id' => $site->id])}}" class="btn btn-success center-block btn-update-categories-order">Update Categories Order</a>
        </div>
    </div>
    
    <div class="row" style="margin-top:20px;">

        <div class="cleafix"></div>

        <ul id="sortable">
            <?php $i = 1; ?>
            @foreach ($categories as $category)
                <li data-original-order="{{$i}}" data-current-order="{{$i}}" data-category-id="{{$category->id}}">
                    <div class="row">

                        <?php $translation = $category->translations()->where('language_id',App::make('sexodomeKernel')->getLanguage()->id)->first(); ?>
                        <div class="col-md-1">
                            <p class="current">{{$i}}</p>
                        </div>

                        <div class="col-md-5">
                            <small>Origin: {{$i}}) </small>  {{ucwords($category->name)}}

                        </div>


                    </div>
                </li>
                <?php $i++; ?>
            @endforeach
        </ul>

    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>
@include('panel._sticker')

<script type="text/javascript">
    $( function() {
        $( "#sortable" ).sortable({
            stop: function (event, ui) {
                var order = 1;
                $('ul#sortable li').each( function( i ) {
                    var old = $(this).find('.current').html();

                    $(this).attr('data-current-order', order);
                    $(this).find('.current').html(order);
                    if (old > order) {
                        $(this).find('.current').css('color', 'green');
                    } else  if (old < order) {
                        $(this).find('.current').css('color', 'red');
                    }

                    order++;
                });
            }
        });
        $( "#sortable" ).disableSelection();
    } );
</script>
</body>
</html>
