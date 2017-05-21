<div class="col-md-12 detail-logo">

    <form action="{{route('updateLogo', ['locale' => $locale, 'site_id' => $site->id])}}" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-5">
            <label>Logo:</label>
            <input type="file" name="logo"/>
        </div>

        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getLogosFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:100%;"/>
            @endif
        </div>

        <div class="clearfix"></div>

        <div class="col-md-5">
            <label>Favicon:</label>
            <input type="file" name="favicon"/>
        </div>

        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getFaviconsFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/favicons/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:16px;"/>
            @endif
        </div>

        <div class="clearfix"></div>

        <div class="col-md-5">
            <label>Header:</label>
            <input type="file" name="header"/>
        </div>

        <div class="col-md-2">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getHeadersFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/headers/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:400px;"/>
            @endif
        </div>
        <div class="clearfix"></div>

        <div class="col-md-2">
            <label>Delete header:</label>
            <input type="checkbox" name="header_delete" value="1"/>
        </div>

        <div class="clearfix"></div>

        <div class="col-md-2" style="margin-top:10px;">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
        </div>

    </form>
</div>
