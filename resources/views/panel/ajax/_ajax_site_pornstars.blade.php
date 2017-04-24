<?php $loop = 0 ?>
<div class="row">
    @if (count($pornstars) == 0)
        <div class="row" style="margin:0px;padding:15px;">
            Currently no pornstars
        </div>
    @endif

    @foreach($pornstars as $pornstar)

        <div class="col-md-2 text-center" style="padding:10px;">
            <img src="{{$pornstar->thumbnail}}" style="border: solid 1px black;width:100%;height: 125px;"/>
            <br/>
            {{$pornstar->name}}
        </div>

    @endforeach

</div>

<div class="row site_pornstars_paginator" style="padding:10px;">
    <?php $pornstars->setPath('pornstars/'.$site->id);?>
    <?php echo $pornstars->render(); ?>
</div>
