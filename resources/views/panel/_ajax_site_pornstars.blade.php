<?php $loop = 0 ?>
<div class="row">
    @foreach($pornstars as $pornstar)
        <?php
        $loop++;

        if ($loop % 2) {
            $bgColor = '#e8e8e8';
        } else {
            $bgColor = 'lightyellow';
        }
        ?>

        <div class="col-md-2 text-center" style="padding:10px;background-color:<?=$bgColor?>;">
            <img src="{{$pornstar->thumbnail}}" style="border: solid 1px black;"/>
            <br/>
            {{$pornstar->name}}
        </div>

    @endforeach

</div>

<div class="row site_pornstars_paginator" style="padding:10px;">
    <?php $pornstars->setPath('pornstars/'.$site->id);?>
    <?php echo $pornstars->render(); ?>
</div>
