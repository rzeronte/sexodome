<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <select id="selector_site" name="site_id" class="selectpicker" data-width="100%">
                @foreach($sites as $s)
                    <option value="{{$s->id}}" data-content="@include('panel._selector_site_option')" data-action="{{route('site', ['locale' => $locale, 'site_id' => $s->id])}}" @if ($s->id != $site->id) selected @endif>{{$s->getHost()}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
