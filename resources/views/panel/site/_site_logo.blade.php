<div class="col-md-12 detail-logo" style="display:none;margin-top:20px;">
    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-picture"></i> <b>Logo</b></p>
    </div>
    <form action="{{route('updateLogo', ['locale' => $locale, 'site_id' => $site->id])}}" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:100%;"/>
            @endif
        </div>

        <div class="col-md-5">
            <input type="file" name="logo"/>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
        </div>

    </form>
</div>
