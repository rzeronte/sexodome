<div class="col-md-12 detail-seo" style="display:none">
    <form method="post" class="form-update-seo-data" action="{{route('updateSiteSEO', ['locale'=>$locale, 'site_id'=>$site->id])}}">

        <div style="margin-top:20px;">
            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-signal"></i> <b>Language for {{$site->getHost()}}</b></p>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    <select name="language_id" class="form-control">
                        @foreach($languages as $lang)
                            @if ($site->language->id == $lang->id)
                                <option value="{{$lang->id}}" selected>{{$lang->name}}</option>
                            @else
                                <option value="{{$lang->id}}">{{$lang->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div style="margin-top:20px;">
            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-signal"></i> <b>SEO for {{$site->getHost()}}</b></p>
            </div>
        </div>

        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="col-md-12">
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Index:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->title_index}}" name="title_index">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Index:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->description_index}}" name="description_index">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Category:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->title_category}}" name="title_category">
                    <small><b>Variables:</b> {domain}, {category}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Category:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->description_category}}" name="description_category">
                    <small><b>Variables:</b> {domain}, {category}</small>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-signal"></i> <b>Main config for {{$site->getHost()}}</b></p>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Domain:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->getHost()}}" name="domain" onfocus="blur()">
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Billboard Text:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->head_billboard}}" name="head_billboard">
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    GAnalytics:
                </div>
                <div class="col-md-7">
                    <textarea name="google_analytics" class="form-control">{{$site->google_analytics}}</textarea>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" class="btn btn-primary" value="Save settings for @if ($site->have_domain == 1){{$site->getHost()}}@else{{$site->name}}@endif" style="width:100%;"/>
                </div>
            </div>

        </div>
    </form>

</div>

