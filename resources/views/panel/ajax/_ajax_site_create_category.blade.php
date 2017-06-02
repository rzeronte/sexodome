<form class="form-create-category" action="{{ route('createCategory', ['site_id' => $site->id]) }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    @foreach(\App\Model\Language::getAddLanguages($site->language_id) as $language)
        <div class="row" style="margin-bottom:5px;">
            <div class="col-md-3 col-lg-offset-2 text-right">{{ $language->name }} <img src="{{asset("flags/$language->code.png")}}"/></div>
            <div class="col-md-4"><input type="text" name="language_{{ $language->code }}" class="form-control" required/></div>
        </div>
    @endforeach

    <div class="row" style="margin-bottom:5px;margin-top:30px;">
        <div class="col-md-7 col-lg-offset-3">
            <input type="submit" value="Create category" class="btn btn-success" style="width:100%"/>
        </div>
    </div>
</form>