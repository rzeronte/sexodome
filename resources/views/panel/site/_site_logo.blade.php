<div class="col-md-12 detail-logo">

    <form action="{{route('updateLogo', ['locale' => $locale, 'site_id' => $site->id])}}" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-5">
            <label>Logo:</label>
            <input type="file" name="logo"/>
        </div>

        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:100%;"/>
            @endif
        </div>

        <div class="clearfix"></div>

        <div class="col-md-5">
            <label>Favicon:</label>
            <input type="file" name="favicon"/>
        </div>
        <div class="clearfix"></div>

        <br/>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
        </div>


        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\rZeBotCommons::getFaviconsFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/favicons/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:100%;"/>
            @endif
        </div>

    </form>
</div>
