<div class="col-md-12 detail-colors">

    <form action="{{route('updateColors', ['site_id' => $site->id])}}" class="form-update-color-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-2">
            <label>Body BG:</label>
            <div id="theme_color_1{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color" value="{{$site->color}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_1{{$site->id}}').colorpicker({ color: '{{$site->color}}', format: 'hex' });});</script>
        </div>

        <div class="col-md-2">
            <label>Header BG</label>
            <div id="theme_color_2{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color2" value="{{$site->color2}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_2{{$site->id}}').colorpicker({ color: '{{$site->color2}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Header Right text:</label>
            <div id="theme_color_3{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color3" value="{{$site->color3}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_3{{$site->id}}').colorpicker({ color: '{{$site->color3}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Billboard BG:</label>
            <div id="theme_color_4{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color4" value="{{$site->color4}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_4{{$site->id}}').colorpicker({ color: '{{$site->color4}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Text Billboard:</label>
            <div id="theme_color_5{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color5" value="{{$site->color5}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_5{{$site->id}}').colorpicker({ color: '{{$site->color5}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Section text:</label>
            <div id="theme_color_6{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color6" value="{{$site->color6}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_6{{$site->id}}').colorpicker({ color: '{{$site->color6}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Section text small:</label>
            <div id="theme_color_7{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color7" value="{{$site->color7}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_7{{$site->id}}').colorpicker({ color: '{{$site->color7}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Video text:</label>
            <div id="theme_color_8{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color8" value="{{$site->color8}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_8{{$site->id}}').colorpicker({ color: '{{$site->color8}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Tag BG:</label>
            <div id="theme_color_9{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color9" value="{{$site->color9}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_9{{$site->id}}').colorpicker({ color: '{{$site->color9}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Buttons BG:</label>
            <div id="theme_color_10{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color10" value="{{$site->color10}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_10{{$site->id}}').colorpicker({ color: '{{$site->color10}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>Buttons color:</label>
            <div id="theme_color_11{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color11" value="{{$site->color11}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_11{{$site->id}}').colorpicker({ color: '{{$site->color11}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2">
            <label>On video info BG:</label>
            <div id="theme_color_12{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color12" value="{{$site->color12}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_12{{$site->id}}').colorpicker({ color: '{{$site->color12}}', format: 'hex'  });});</script>
        </div>

        <div class="col-md-2" style="margin-top: 5px;">
            <br/>
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
        </div>


    </form>

</div>
