<b>Tier1</b>

<br/>

@if (count($tier1) == 0)
    <p>Not tags defined</p>
@endif

@foreach ($tier1 as $tag)
    <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
    <small style="padding:2px;background-color: #3bc53e;color:white;">{{$translation->name}} ({{ $tag->countScenesLangIn($language->id, $database) }})</small>
@endforeach

<br/>

<b>Tier2</b>
<br/>
@if (count($tier2) == 0)
    <p>Not tags defined</p>
@endif

@foreach ($tier2 as $tag)
    <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
    <small style="padding:2px;background-color: #ffa600;color:white;">{{$translation->name}} ({{ $tag->countScenesLang($language->id) }})</small>
@endforeach

<b>Tier3</b>
<br/>
@if (count($tier3) == 0)
    <p>Not tags defined</p>
@endif

@foreach ($tier3 as $tag)
    <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
    <small style="padding:2px;background-color: #05bdff;color:white;">{{$translation->name}} ({{ $tag->countScenesLang($language->id) }})</small>
@endforeach
