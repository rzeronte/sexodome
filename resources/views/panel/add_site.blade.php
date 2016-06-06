<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">
    <div class="header row">
        @include('panel._header_config')
    </div>

    <div class="row" style="margin-top:20px;">
        <div style="border-bottom: solid 1px darkorange;margin-bottom:20px;">
            <p><b>Add a new site</b></p>
        </div>

        <form action="{{route('addSite', ['locale'=>$locale])}}" method="post">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="col-md-2 col-md-offset-5" style="text-align: center;padding:10px;">
                    <select id="add_site_type" class="form-control" name="type_site" placeholder="language" autocomplete="off">
                        <option value="0" @if(!Session::get('error_domain')) selected @endif>Without domain</option>
                        <option value="1" @if(Session::get('error_domain')) selected @endif>With domain</option>
                    </select>
                </div>

                <div class="clearfix"></div>

                <div class="div_input_name" style="@if(Session::get('error_domain')) display:none; @endif">
                    <div class="col-md-5 col-md-offset-5" style="text-align: center;padding:10px;">
                        <input id="add_site_subdomain_input" onkeyup="checkSubdomain(this)" type="text" name="subdomain" value="" placeholder="subdomain" class="form-control" style="float:left;width:175px;"required/><p style="color:gray; font-size:25px;float:left;">.{{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}</p>
                        <div class="clearfix"></div>
                        <div class="result_subdomain text-left">
                            @if (Session::get('error_subdomain'))
                                <p class='check_domain_ko'>{{{ Session::get('error_subdomain') }}}</p>
                            @endif
                                <div class="alert alert-warning">
                                    <p>Subdomain activation <br/>can take a few <br/>minutes...please, wait.</p>
                                </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="div_input_domain" style="@if(!Session::get('error_domain')) display:none; @endif">
                    <div class="col-md-4 col-md-offset-4" style="text-align: center;padding:10px;">
                        <p style="margin-left: 40px;color:gray; font-size:25px;float:left;">www.</p><input id="add_site_domain_input" onkeyup="checkDomain(this)" type="text" value="" name="domain" placeholder="Domain" class="form-control" style="width:175px;"/>
                        <div class="clearfix"></div>
                        <div class="result_domain text-center"></div>
                        @if (Session::get('error_domain'))
                            <p class='check_domain_ko'>{{ Session::get('error_domain') }}</p>
                        @endif
                        <div class="alert alert-warning">
                            <p>Configure your DNS to resolve in:</p>
                            <p>91.121.81.154</p>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="clearfix"></div>
                <div class="col-md-2 col-md-offset-5" style="text-align: center;padding:10px;">
                    <input type="submit" value="create new site" class="btn btn-primary" style="width:100%;"/>
                </div>

            </div>
        </form>

        <form id="form_check_subdomain" action="{{ route('checkSubdomain', [ "locale" => $locale ]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="subdomain" value="" class="subdomain">
        </form>

        <form id="form_check_domain" action="{{ route('checkDomain', [ "locale" => $locale ]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="domain" value="" class="domain">
        </form>

    </div>

    <div style="border-top: solid 1px darkorange;margin-top:20px;">
        <p class="text-right">panel v.0.16</p>
    </div>

</div>

</body>
</html>
