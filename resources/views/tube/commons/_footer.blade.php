<section class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12  text-left" style="padding-left:10px;">
                @if (env("PORN_STATIC_LINKS", false))
                    <a href="{{route('dmca', ["host" => $site->getHost()])}}">DMCA/Copyright</a> |
                    <a href="{{route('terms', ["host" => $site->getHost()])}}">Terms of use</a> |
                    <a href="{{route('C2257', ["host" => $site->getHost()])}}">2257</a> |
                    <a href="mailto:{{$site->contact_email}}">Contact</a>
                @endif
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                <a href="http://www.sexodome.com" target="_blank">made in by sexodome.com</a>
            </div>

        </div>
    </div>
</section>