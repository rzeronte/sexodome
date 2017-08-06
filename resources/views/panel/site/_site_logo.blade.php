<div class="col-md-12 detail-logo coloreable">

    <form action="{{route('updateLogo', ['site_id' => $site->id])}}" enctype="multipart/form-data" method="post" class="ajax-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-4 text-center">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-floppy-disk"></i>
                <span>Logo Upload</span>
                <input type="file" name="logo" class="fileuploadSiteLogo" data-url="{{ route( 'updateLogo', [ 'site_id'  => $site->id ] ) }}"/>
            </span>
        </div>
        <div class="col-md-4 text-center">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-floppy-disk"></i>
                <span>Favicon Upload</span>
                <input type="file" name="favicon" class="fileuploadSiteLogo" data-url="{{ route( 'updateLogo', [ 'site_id'  => $site->id ] ) }}"/>
            </span>
        </div>

        <div class="col-md-4 text-center">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-floppy-disk"></i>
                <span>Header Upload</span>
                <input type="file" name="header" class="fileuploadSiteLogo" data-url="{{ route( 'updateLogo', [ 'site_id'  => $site->id ] ) }}"/>
            </span>

            <div class="clearfix"></div>
            <label>Delete header:</label>
            <input type="checkbox" name="header_delete" value="1"/>

        </div>

        <div class="clearfix" style="margin-bottom:20px;"></div>

        <div class="col-md-4 text-center alternate_coloreable">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getLogosFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="border: solid 1px gray;" id="site_logo_image"/>
            @else
                <img src="{{asset('/images/image_not_found.png')}}" style="border: solid 1px gray;" id="site_logo_image"/>
            @endif
        </div>

        <div class="col-md-4 text-center alternate_coloreable">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getFaviconsFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/favicons/'.md5($site->id).".png")}}" style="border: solid 1px gray; width:16px;" id="site_favicon_image"/>
            @else
                <img src="{{asset('/images/image_not_found.png')}}" style="border: solid 1px gray;" id="site_logo_image"/>
            @endif
        </div>

        <div class="col-md-4 text-center alternate_coloreable">
            @if (file_exists(\App\rZeBot\sexodomeKernel::getHeadersFolder()."/".md5($site->id).".png"))
                <img src="{{asset('/headers/'.md5($site->id).".png")}}" style="border: solid 1px gray;" id="site_header_image"/>
            @else
                <img src="{{asset('/images/image_not_found.png')}}" style="border: solid 1px gray;" id="site_logo_image"/>
            @endif
                <div class="clearfix"></div>

            <button type="submit" class="btn btn-primary" style="margin-top:10px;"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>

        </div>


        <div class="col-md-12" >
        </div>

    </form>
</div>
