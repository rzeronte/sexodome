<div class="row">
    <div class="col-md-7">
        <h4>Insert in your website:</h4>
        <input type="text" class="form-control" value='<iframe width="560" height="315" src="{{route('iframe', ['profile'=> App::make('sexodomeKernel')->getSite()->getHost(),'scene_id'=>$video->id])}}" frameborder="0" allowfullscreen></iframe>'>
    </div>
</div>