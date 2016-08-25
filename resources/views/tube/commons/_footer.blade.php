<section class="footer">
    <div class="container">
        <div class="col-md-4 text-left">
            @if (env("PORN_STATIC_LINKS", false))
                <a href="{{route('dmca', ["host" => $site->getHost()])}}">DMCA/Copyright</a> |
                <a href="{{route('terms', ["host" => $site->getHost()])}}">Terms of use</a> |
                <a href="{{route('C2257', ["host" => $site->getHost()])}}">2257</a> |
                <a href="mailto:{{$site->contact_email}}">Contact</a>
            @endif
        </div>
        <div class="col-md-4 text-center">
            <a href="{{route('categories', ['profile' => $profile])}}" title="{{$language->title}}">
                @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                    <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="max-height: 50px;"/>
                @else
                    {{$site->domain}}
                @endif
            </a>
        </div>
        <div class="col-md-4 text-right">
            <a href="http://www.sexodome.com" target="_blank">made in by sexodome.com</a>
        </div>
    </div>
</section>
@include('tube.commons._theme')