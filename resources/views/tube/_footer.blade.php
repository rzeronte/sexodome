<footer class="row text-center">
    <div class="container">
        <div class="col-md-4 text-left">
            @if (env("PORN_STATIC_LINKS", false))
                <a href="{{route('dmca', ["host" => $site->getHost()])}}" style="color:white;">DMCA/Copyright</a><br/>
                <a href="{{route('terms', ["host" => $site->getHost()])}}" style="color:white;">Terms of use</a><br/>
                <a href="{{route('C2257', ["host" => $site->getHost()])}}" style="color:white;">2257</a><br/>
                <a href="mailto:Auth::user()->email" style="color:white;">Contact</a>
            @endif
        </div>
        <div class="col-md-4">
            <a href="{{route('categories', ['profile' => $profile])}}" title="{{$language->title}}">
                @if (file_exists(\App\rZeBot\rZeBotCommons::getLogosFolder()."/".md5($site->id).".png"))
                    <img src="{{asset('/logos/'.md5($site->id).".png")}}" style="max-height: 50px;"/>
                @else
                    {{$site->domain}}
                @endif
            </a>

        </div>
        <div class="col-md-4"></div>
    </div>
</footer>