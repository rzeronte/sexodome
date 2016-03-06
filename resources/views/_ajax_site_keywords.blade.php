@foreach($keywords as $keyword)
    Keyword: <a href='http://www.google.com?q={{$keyword["keyword"]}}' target="_blank">{{$keyword["keyword"]}}</a><br/>
    Sessions: {{$keyword["sessions"]}}
    <hr/>
@endforeach
