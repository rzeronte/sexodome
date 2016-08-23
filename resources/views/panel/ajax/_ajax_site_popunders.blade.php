@if (count($popunders) == 0)
    <div class="row" style="margin:0px;padding-top:15px;">
        No popunders for this site
    </div>
@endif

<div class="row infojobs-list">
    <?php $loop = 0 ?>
    @foreach($popunders as $popunder)
        <?php
        $loop++;
        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>
        <div class="alert alert-success" style="background-color:<?=$bgColor?>;margin:0px;padding-top:15px;">

            <div class="col-md-10">
                <b>URL: </b> {{$popunder->url}}
            </div>
            <div class="col-md-2">
                <a href="{{route('ajaxDeletePopunder', ['locale' => $locale, 'popunder_id' => $popunder->id])}}" class="delete-site-popunder-btn btn btn-danger">Delete popunder</a>
            </div>

            <div class="clearfix"></div>
        </div>
    @endforeach
</div>