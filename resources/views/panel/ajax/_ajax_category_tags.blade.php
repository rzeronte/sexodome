<h3><i class="glyphicon glyphicon-th"></i> Category tags:</h3>
<form action="{{route('categoryTags', ['locale' => $locale, "category_id" => $category->id])}}" method="post" class="form-control form-update-category-tags" style="height:400px;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <select id="" multiple style="width:100%;height:300px;" class="form-control" name="categories[]">
        @foreach($tags as $tag)
            <option value="{{$tag->id}}" @if (in_array($tag->id, $category_tags)) selected @endif>{{$tag->permalink}}</option>
        @endforeach
    </select>
    <br/>
    <input type="submit" value="Update" class="btn btn-success">
</form>

<script type="text/javascript">
    $( ".form-update-category-tags" ).submit(function( event ) {
        var action = $(this).attr("action");
        var form = $(this);
        var actionAjaxPopunders = $(this).attr('data-ajax-popunders');

        $.ajax({
            url: action,
            data: $(this).serialize(),
            method: 'post'
        }).done(function( data ) {
            jsonData = $.parseJSON(data);
            if (jsonData["status"] == true) {
                showGenericalSuccessMessage();
            } else {
                showGenericalErrorMessage();
            }
        });
        event.preventDefault();
    });

</script>