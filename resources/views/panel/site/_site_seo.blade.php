<div class="col-md-12 detail-seo">
    <form method="post" class="form-update-seo-data" action="{{route('updateSiteSEO', ['site_id'=>$site->id])}}">

        <div class="row" style="padding:10px;">
            <div class="col-md-3">
                Domain:
            </div>
            <div class="col-md-7">
                <input type="text" class="form-control" value="{{$site->getHost()}}" name="domain" onfocus="blur()">
            </div>
        </div>

        <div class="col-md-12">
            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-comment"></i> <b>Basic config</b></p>
            </div>
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Type:
                </div>

                <div class="col-md-3">
                    <select name="type_id" class="form-control">
                        @foreach($types as $type)
                            @if ($site->type->id == $type->id)
                                <option value="{{$type->id}}" selected>{{$type->name}}</option>
                            @else
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Language:
                </div>
                <div class="col-md-3">
                    <select name="language_id" class="form-control">
                        @foreach(App::make('sexodomeKernel')->getLanguages() as $lang)
                            @if ($site->language->id == $lang->id)
                                <option value="{{$lang->id}}" selected>{{$lang->name}}</option>
                            @else
                                <option value="{{$lang->id}}">{{$lang->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Order type:
                </div>
                <div class="col-md-3">
                    <select name="order_type" class="form-control">
                        <option value="1" @if ($site->order_type == 0) selected @endif>Analytics</option>
                        <option value="0" @if ($site->order_type == 1) selected @endif>Manual</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-12">

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-alert"></i> <b>Status</b></p>
                </div>
            </div>
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Status:
                </div>
                <div class="col-md-7">
                    <select name="status" class="form-control">
                        <option value="1" @if ($site->status == 1) selected @endif>Si</option>
                        <option value="0" @if ($site->status != 1) selected @endif>No</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-cog"></i> <b>SEO/URL Setup</b></p>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Category URL:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->category_url}}" name="category_url">
                    <small><b>Example:</b> www.domain.com/{category_url}/blonde</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Pornstars URL:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->pornstars_url}}" name="pornstars_url">
                    <small><b>Example:</b> www.domain.com/{pornstars_url}/</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Pornstar URL:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->pornstar_url}}" name="pornstar_url">
                    <small><b>Example:</b> www.domain.com/{pornstar_url/jenna_jameson</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Video URL:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->video_url}}" name="video_url">
                    <small><b>Example:</b> www.domain.com/{video_url}/title_example_for_video_permalink</small>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-signal"></i> <b>Headers</b></p>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Logo H1:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->logo_h1}}" name="logo_h1">
                    <small><b>Example:</b> ExamplePorn</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Home H2:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->h2_home}}" name="h2_home">
                    <small><b>Example:</b> Porn Videos</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Category H2:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->h2_category}}" name="h2_category">
                    <small><b>Example:</b> {category} Porn Videos</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Pornstars H2:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->h2_pornstars}}" name="h2_pornstars">
                    <small><b>Example:</b> Pornstars videos compilation</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Pornstar H2:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->h2_pornstar}}" name="h2_pornstar">
                    <small><b>Example:</b> {pornstar} videos compilation</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Categories H3:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->categories_h3}}" name="categories_h3">
                    <small><b>Example:</b> All Categories A / Z</small>
                </div>
            </div>

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-signal"></i> <b>Title/Description SEO</b></p>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Index:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_index}}" name="title_index">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Index:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_index">{{$site->seo->description_index}}</textarea>
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Category:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_category}}" name="title_category">
                    <small><b>Variables:</b> {domain}, {category}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Category:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_category">{{$site->seo->description_category}}</textarea>
                    <small><b>Variables:</b> {domain}, {category}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title index Pornstars:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_pornstars}}" name="title_pornstars">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description index Pornstars:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_pornstars">{{$site->seo->description_pornstars}}</textarea>
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Pornstar:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_pornstar}}" name="title_pornstar">
                    <small><b>Variables:</b> {domain}, {pornstar}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Pornstar:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_pornstar">{{$site->seo->description_pornstar}}</textarea>
                    <small><b>Variables:</b> {domain}, {pornstar}</small>
                </div>
            </div>
            --
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title Tag:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_tag}}" name="title_tag">
                    <small><b>Variables:</b> {domain}, {tag}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description Tag:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_tag">{{$site->seo->description_tag}}</textarea>
                    <small><b>Variables:</b> {domain}, {tag}</small>
                </div>
            </div>
            --
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Title TopScenes:
                </div>

                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->title_topscenes}}" name="title_topscenes">
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Description TopScenes:
                </div>

                <div class="col-md-7">
                    <textarea class="form-control" name="description_topscenes">{{$site->seo->description_topscenes}}</textarea>
                    <small><b>Variables:</b> {domain}</small>
                </div>
            </div>
            --
            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Head Text:
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->seo->header_text}}" name="header_text">
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <div style="margin-top:20px;">
                <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                    <p><i class="glyphicon glyphicon-envelope"></i> <b>Contact Email</b></p>
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


            <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
                <p><i class="glyphicon glyphicon-certificate"></i> <b>Miscelaneous</b></p>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Link billboard(HTML):
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->link_billboard}}" name="link_billboard">
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Link billboard Mobile(HTML):
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{$site->link_billboard_mobile}}" name="link_billboard_mobile">
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-3">
                    Google Analytics UA:
                </div>
                <div class="col-md-7">
                    <input type="text" name="google_analytics" class="form-control" value="{{$site->google_analytics}}" style="width:200px;"/>
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
                <p><i class="glyphicon glyphicon-object-align-left"></i> <b>Scripts</b></p>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-12">
                    <textarea name="javascript" class="form-control" style="height:250px">{{$site->javascript}}</textarea>
                </div>
            </div>

            <div class="row" style="padding:10px;">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary" value="Save settings for @if ($site->have_domain == 1){{$site->getHost()}}@else{{$site->name}}@endif" style="width:100%;"/>
                </div>
            </div>


        </div>
    </form>

</div>

