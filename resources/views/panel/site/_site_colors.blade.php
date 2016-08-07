<div class="col-md-12 detail-colors" style="display:none;margin-top:20px;">
    <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
        <p><i class="glyphicon glyphicon-tint"></i> <b>Colors</b></p>
    </div>

    <form action="{{route('updateColors', ['locale' => $locale, 'site_id' => $site->id])}}" class="form-update-color-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

        <div class="col-md-2">
            <label>General btns bg:</label>
            <div id="theme_color_1{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color" value="{{$site->color}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_1{{$site->id}}').colorpicker({ color: '{{$site->color}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>General btns color:</label>
            <div id="theme_color_11{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color1" value="{{$site->color11}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_11{{$site->id}}').colorpicker({ color: '{{$site->color11}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Border scenes</label>
            <div id="theme_color_2{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color1" value="{{$site->color2}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_2{{$site->id}}').colorpicker({ color: '{{$site->color2}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Box info scenes:</label>
            <div id="theme_color_3{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color1" value="{{$site->color3}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_3{{$site->id}}').colorpicker({ color: '{{$site->color3}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Tube link bg:</label>
            <div id="theme_color_4{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color4" value="{{$site->color4}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_4{{$site->id}}').colorpicker({ color: '{{$site->color4}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Title color:</label>
            <div id="theme_color_5{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color5" value="{{$site->color5}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_5{{$site->id}}').colorpicker({ color: '{{$site->color5}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Title hover color:</label>
            <div id="theme_color_6{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color6" value="{{$site->color6}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_6{{$site->id}}').colorpicker({ color: '{{$site->color6}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Title bg:</label>
            <div id="theme_color_7{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color7" value="{{$site->color7}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_7{{$site->id}}').colorpicker({ color: '{{$site->color7}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Category-tube color:</label>
            <div id="theme_color_8{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color8" value="{{$site->color8}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_8{{$site->id}}').colorpicker({ color: '{{$site->color8}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Category link bg:</label>
            <div id="theme_color_9{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color9" value="{{$site->color9}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_9{{$site->id}}').colorpicker({ color: '{{$site->color9}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Billboard color:</label>
            <div id="theme_color_10{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color10{{$site->color}}" value="{{$site->color10}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_10{{$site->id}}').colorpicker({ color: '{{$site->color10}}' });});</script>
        </div>

        <div class="col-md-2">
            <label>Background color:</label>
            <div id="theme_color_12{{$site->id}}" class="input-group colorpicker-component">
                <input type="text" name="color12" value="{{$site->color12}}" class="form-control" />
                <span class="input-group-addon"><i></i></span>
            </div>
            <script>$(function() { $('#theme_color_12{{$site->id}}').colorpicker({ color: '{{$site->color12}}' });});</script>
        </div>

        <div class="col-md-2" style="margin-top: 5px;">
            <br/>
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>
        </div>


    </form>

</div>
