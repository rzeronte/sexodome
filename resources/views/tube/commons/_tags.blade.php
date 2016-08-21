@foreach ($tags as $tag)
    @if ($tag->scenes()->count() > 0)
        <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
        <div class="col-md-1 col-sm-4 col-xs-4" style="text-align: center">
            <a href="{{ route('tag', array('profile' => $profile, 'permalink'=> $translation->permalink )) }}" class="tag btn btn-default btn-xs" target="_blank">
                {{ $translation->name}}
            </a>
        </div>
    @endif
@endforeach
