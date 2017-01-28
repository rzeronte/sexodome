<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <h1>Fix Translations</h1>

    <div class="row">

        <div class="col-md-6" style="margin-bottom:10px;">
            <form action="{{route('AddFixTranslation', ['locale' => $locale])}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="row">
                    <div class="col-md-3">
                        <select class="selectpicker form-control show-tick" data-width="100%" data-style="btn-primary" name="language">
                            @foreach($languages as $itemLang)
                                <option value="{{$itemLang->id}}"><small>{{$itemLang->name}}</small></option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="from" name="from"/>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="to" name="to"/>
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="btn btn-success" value="Create fix"/>
                    </div>

                </div>
            </form>

        </div>

        <div class="clearfix"></div>

        <div class="container">
            <h2>Fix Translations list</h2>
            <p><i>Remember: They will be applied to tags and categories.</i></p>
            @if (count($fixs) == 0)
                <div class="clearfix"></div>
                <div class="alert-danger">No fix translations</div>
            @endif

            <?php $loop = 0 ?>
            @foreach ($fixs as $fix)
                <?php
                $loop++;

                if ($loop % 2) {
                    $bgColor = '#e8e8e8';
                } else {
                    $bgColor = 'lightyellow';
                }
                ?>

                <div class="row" style="padding:10px;background-color:<?=$bgColor?>;">

                    <div class="col-md-2">
                        <img src='{{asset("flags/".$fix->language->code.".png")}}'/> {{$fix->language->name}}
                    </div>
                    <div class="col-md-2">
                        From: <b>{{$fix->from}}</b>
                    </div>

                    <div class="col-md-1">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                    </div>

                    <div class="col-md-2">
                        To: <b>{{$fix->to}}</b>
                    </div>

                    <div class="col-md-3">
                        <a href="{{route('deleteFixTranslation', ['locale' => $locale, 'fixtranslation_id' => $fix->id])}}" class="btn btn-danger"> <i class=" glyphicon glyphicon-trash"></i> Delete translation fix</a>
                    </div>
                </div>

            @endforeach
        </div>

    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

@include('panel._sticker')
@include('panel._modal')
</body>
</html>
