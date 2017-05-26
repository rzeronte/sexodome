<div class="col-md-12 detail-iframe">

    <div class="row" style="margin-bottom:10px;">
        <form class="form-update-iframe-data" action="{{route('updateIframeData', ['site_id' => $site->id])}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <div class="col-md-5">
                <select name="iframe_site_id_{{$site->id}}" class="form-control">
                    <option value="">No iframe</option>
                    @foreach($sites as $sit)
                        <option value="{{$sit->id}}" @if ($site->iframe_site_id == $sit->id) selected @endif>{{$sit->getHost()}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
            </div>
        </form>

    </div>

</div>
