<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <select id="selector_site" name="site_id" class="selectpicker" data-width="100%">
                @foreach($sites as $s)
                    @if (isset($site))
                        @if ($s->id != $site->id)
                            <option value="{{$s->id}}" data-content="@include('panel._selector_site_option')" data-action="{{route('site', ['locale' => $locale, 'site_id' => $s->id])}}">{{$s->getHost()}}</option>
                        @else
                        <option value="{{$s->id}}" data-content="@include('panel._selector_site_option')" data-action="{{route('site', ['locale' => $locale, 'site_id' => $s->id])}}" selected>{{$s->getHost()}}</option>
                        @endif
                    @else
                        <option value="{{$s->id}}" data-content="@include('panel._selector_site_option')" data-action="{{route('site', ['locale' => $locale, 'site_id' => $s->id])}}">{{$s->getHost()}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>
