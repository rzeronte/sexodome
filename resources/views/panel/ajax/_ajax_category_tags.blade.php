<h3><i class="glyphicon glyphicon-th"></i> Category tags:</h3>
<form action="{{route('categoryTags', ["category_id" => $category->id])}}" method="post" class="form-control form-update-category-tags" style="height:400px;">
    <input type="hidden" tabindex="1" name="_token" value="{{ csrf_token() }}"/>
    <select id="select2_category_tags" class="chosen-select" name="categories[]" multiple style="width:500px;">
        @foreach($tags as $tag)
            <option value="{{$tag->id}}" @if (in_array($tag->id, $category_tags)) selected @endif>{{$tag->permalink}}</option>
        @endforeach
    </select>
    <br/>
    <input type="submit" value="Update" class="btn btn-success" style="margin-top:10px;">
</form>

<script type="text/javascript">
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $( ".form-update-category-tags" ).submit(function( event ) {
        var action = $(this).attr("action");

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

    $(".chosen-select").chosen({
        disable_search_threshold: 10,
        width: "100%"
    });
</script>