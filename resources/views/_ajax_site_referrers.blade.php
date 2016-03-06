@foreach($referrers as $referrer)
    URL: <a href='{{$referrer["url"]}}' target="_blank">{{$referrer["url"]}}</a><br/>
    PageViews: {{$referrer["pageViews"]}}
    <hr/>
@endforeach
