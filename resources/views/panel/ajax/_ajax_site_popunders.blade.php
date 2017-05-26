@if (count($popunders) == 0)
    <div class="row" style="margin:0px;padding-top:15px;">
        No popunders for this site
    </div>
@endif

<div class="row infojobs-list">

    @foreach($popunders as $popunder)

        <div class="alert alert-success" style="margin:0px;padding-top:15px;">

            <div class="col-md-10">
                <b>URL: </b> {{$popunder->url}}
            </div>
            <div class="col-md-2">
                <a href="{{route('ajaxDeletePopunder', ['popunder_id' => $popunder->id])}}" class="delete-site-popunder-btn btn btn-danger">Delete popunder</a>
            </div>

            <div class="clearfix"></div>
        </div>
    @endforeach
</div>