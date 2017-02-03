<section class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-12 col-xs-12  text-left" style="padding-left:10px;">
                @if (env("PORN_STATIC_LINKS", false))
                    <a href="{{route('dmca', ["host" => $site->getHost()])}}">DMCA/Copyright</a> |
                    <a href="{{route('terms', ["host" => $site->getHost()])}}">Terms of use</a> |
                    <a href="{{route('C2257', ["host" => $site->getHost()])}}">2257</a> |
                    <a href="mailto:{{$site->contact_email}}">Contact</a>
                @endif
            </div>
            <div class="col-md-1 col-sm-12 col-xs-12 text-right">
                <a target="_blank" title="RTA Label" href="http://http://www.rtalabel.org/"><img src="{{asset('images/rta.gif')}}" alt="RTA Label"></a>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                <a href="http://www.sexodome.com" target="_blank">Adult Tube Creator</a> | sexodome.com
            </div>

        </div>
    </div>
</section>
