<!DOCTYPE html>
<html>

@include('layout_admin._head')

<body style="background-color: dimgray;">
<div class="container">
    <div class="header row">
        @include('layout_admin._header_config')
    </div>

    <div class="row" style="background-color:white;padding:10px;">
        <div class="col-md-4">
            <form action="{{ route('tags', ['locale'=>$locale]) }}" method="get" style="width:100%">

                <div class="input-group">
                    <input id="query_string" name="q" type="text" placeholder="" class="form-control query_string" value="{{$query_string}}" style="width:100%;">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Find</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-md-8" style="text-align:right;">
        </div>

    </div>

    <?php $loop = 0 ?>
    @foreach($tags as $tag)
        <?php
        $loop++;

        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>

        <div class="row" style="background-color:<?=$bgColor?>;">
            <div class="col-md-2">
                {{$tag->name}}
            </div>
            <div class="col-md-2">
                {{$tag->scenes()->count()}}
            </div>
        </div>

    @endforeach

    <div class="row">
        <?php echo $tags->appends(['locale'=>$locale, 'q' => $query_string])->render(); ?>
    </div>

</div>
</body>
</html>
