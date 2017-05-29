<footer class="footer">
    <div class="container">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
            <p class="text-muted">
                <a href="{{route('dmca', ["host" => App::make('sexodomeKernel')->getSite()->getHost()])}}">DMCA/Copyright</a> |
                <a href="{{route('terms', ["host" => App::make('sexodomeKernel')->getSite()->getHost()])}}">Terms of use</a> |
                <a href="{{route('C2257', ["host" => App::make('sexodomeKernel')->getSite()->getHost()])}}">2257</a> |
                <a href="mailto:{{App::make('sexodomeKernel')->getSite()->contact_email}}">Contact</a> |
                <a href="http://www.rtalabel.org" target="_blank"><i class="icon-logo_rta"></i></a></p>
        </div>
        <div class="col-md-4" style="margin-top:12px;">
            <div id="share"></div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
            <p class="text-muted"><a href="http://www.sexodome.com" target="_blank">Adult Tube Creator</a> | sexodome.com</p>
        </div>
    </div>
</footer>
