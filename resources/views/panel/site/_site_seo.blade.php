<div class="col-md-12 detail-seo">
    <form method="post" class="form-update-seo-data" action="{{route('updateSiteSEO', ['locale'=>$locale, 'site_id'=>$site->id])}}">


        <div class="col-md-12">
            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-comment"></i> <b>Language</b></p>
            </div>

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

        <div class="col-md-12">
            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-signal"></i> <b>SEO</b></p>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Contact email:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->contact_email}}" name="contact_email">
                </div>
            </div>


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
                    <textarea class="form-control" name="description_index">{{$site->description_index}}</textarea>
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
                    <textarea class="form-control" name="description_category">{{$site->description_category}}</textarea>
                    <small><b>Variables:</b> {domain}, {category}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title index Pornstars:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->title_pornstars}}" name="title_pornstars">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description index Pornstars:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_pornstars">{{$site->description_pornstars}}</textarea>
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Pornstar:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->title_pornstar}}" name="title_pornstar">
                    <small><b>Variables:</b> {domain}, {pornstar}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Pornstar:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_pornstar">{{$site->description_pornstar}}</textarea>
                    <small><b>Variables:</b> {domain}, {pornstar}</small>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

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
                    <input type="text" name="google_analytics" class="form-control" value="{{$site->google_analytics}}"/>
                </div>
            </div>

            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-random"></i> <b>Header buttons</b></p>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Button URL #1:
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="button1_url" class="form-control" value="{{$site->button1_url}}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Button Text #1:
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="button1_text" class="form-control" value="{{$site->button1_text}}"/>
                    </div>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Button URL #2:
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="button2_url" class="form-control" value="{{$site->button2_url}}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Button Text #2:
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="button2_text" class="form-control" value="{{$site->button2_text}}"/>
                    </div>
                </div>
            </div>

            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-object-align-left"></i> <b>Banners</b></p>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Script for footer banner #1:
                </div>
                <div class="col-md-7">
                    <textarea name="banner_script1" class="form-control">{{$site->banner_script1}}</textarea>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Script for footer banner #2:
                </div>
                <div class="col-md-7">
                    <textarea name="banner_script2" class="form-control">{{$site->banner_script2}}</textarea>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Script for footer banner #3:
                </div>
                <div class="col-md-7">
                    <textarea name="banner_script3" class="form-control">{{$site->banner_script3}}</textarea>
                </div>
            </div>

            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-modal-window"></i> <b>Mobile Banners</b></p>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Script for banner #1:
                </div>
                <div class="col-md-7">
                    <textarea name="banner_mobile1" class="form-control">{{$site->banner_mobile1}}</textarea>
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

