<head>
    <title>Member zone {{\App\rZeBot\rZeBotCommons::getMainPlataformDomain()}}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{--jquery --}}
    <script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <link href="https://code.jquery.com/ui/1.11.4/themes/black-tie/jquery-ui.css" rel="stylesheet" type="text/css">

    {{--bootstrap 3.3.6--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    {{-- font awesome --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    {{-- json Editor --}}
    {{--<script src="{{asset('jsoneditor/jsoneditor.js')}}"></script>--}}
    {{--<link rel="stylesheet" href="{{asset('jsoneditor/jsoneditor.css')}}" />--}}


    <script src="{{ asset('jquery-file-upload/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('jquery-file-upload/js/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('jquery-file-upload/js/jquery.fileupload.js') }}"></script>

    <script src="{{asset('admin.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{asset('admin.css')}}" />

    {{-- high charts --}}
    <script src="http://code.highcharts.com/highcharts.js"></script>

    {{-- bootstrap colorpicker --}}
    <script src="{{asset('bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" />

    {{-- bootstrap select --}}
    <script src="{{asset('bootstrap-select/js/bootstrap-select.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('bootstrap-select/css/bootstrap-select.min.css')}}" />

    <script
            src="http://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
            integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
            crossorigin="anonymous"></script>

    {{-- select2 --}}
    <script src="{{asset('chosen/chosen.jquery.js')}}"></script>
    <script src="{{asset('chosen/chosen.proto.js')}}"></script>
    <link rel="stylesheet" href="{{asset('chosen/chosen.css')}}"/>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-78770486-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>
