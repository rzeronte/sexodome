@foreach($pageViews as $pageView)
    URL: <a href='{{$pageView["url"]}}' target="_blank">{{$pageView["url"]}}</a><br/>
    PageViews: {{$pageView["pageViews"]}}
    <hr/>
@endforeach
