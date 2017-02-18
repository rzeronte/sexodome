<footer class="footer">
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
            <p class="text-muted">
                <a href="{{route('dmca', ["host" => $site->getHost()])}}">DMCA/Copyright</a> |
                <a href="{{route('terms', ["host" => $site->getHost()])}}">Terms of use</a> |
                <a href="{{route('C2257', ["host" => $site->getHost()])}}">2257</a> |
                <a href="mailto:{{$site->contact_email}}">Contact</a> |
                <a href="http://www.rtalabel.org" target="_blank"><i class="icon-logo_rta"></i></a></p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            <p class="text-muted"><a href="http://www.sexodome.com" target="_blank">Adult Tube Creator</a> | sexodome.com</p>
        </div>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="{{asset('tube/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('tube/bower_components/wow/dist/wow.min.js')}}"></script>
<script src="{{asset('tube/js/main.js')}}"></script>
