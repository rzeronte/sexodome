<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('layout_admin._head')

<body style="background-color: dimgray;">

<div id="ajaxUrls" data-categories-url="{{route('ajaxCategories', ['locale'=> $locale])}}" data-tags-url="{{route('ajaxTags', ['locale'=> $locale])}}"></div>

<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="container" style="background-color:white;padding:10px;">
        <?php $loop = 0 ?>
        @foreach($channels as $channel)
            <?php
                $loop++;
                if ($loop % 2) {
                    $bgColor = '#e8e8e8';
                } else {
                    $bgColor = 'lightyellow';
                }
            ?>
            <div class="row" style="background-color:<?=$bgColor?>;padding: 5px;">
                <div class="col-md-1">
                    {{$channel->name}}
                </div>
                <div class="col-md-1">
                    {{$channel->url}}
                </div>
                <div class="col-md-3">
                    {{$channel->file}}
                </div>
                <div class="col-md-1">
                    {{$channel->permalink}}
                </div>
                <div class="col-md-3">
                    {{$channel->mapping_class}}
                </div>
            </div>
        @endforeach
    </div>
    <!-- Modal Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .successAjax{
        border: solid 3px green;
    }

    .errorAjax{
        border: solid 3px red;
    }

</style>

    <style>
    .js_tags+.tag-editor { background: #fafafa; font-size: 12px; }
    .js_tags+.tag-editor .tag-editor-spacer { width: 7px; }
    .js_tags+.tag-editor .tag-editor-delete { display: none; }
    .js_tags_tier1+.tag-editor .tag-editor-tag {
        color: #ffffff; background: limegreen;
        border-radius: 2px;
    }
    .js_tags_tier2+.tag-editor .tag-editor-tag {
        color: #ffffff; background: orange;
        border-radius: 2px;
    }
    .js_tags_tier3+.tag-editor .tag-editor-tag {
        color: #ffffff; background: deepskyblue;
        border-radius: 2px;
    }
</style>

</body>
</html>
