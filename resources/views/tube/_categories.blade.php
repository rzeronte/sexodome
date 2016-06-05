@foreach ($categories as $category)
    {{--@if ($category->scenes()->count() > 0)--}}
        <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
        <div class="col-md-1 col-sm-4 col-xs-4" style="padding: 2px;text-align: center">
            <a href="{{ route('category', array('profile' => $profile, 'permalink'=> str_slug($translation->name) )) }}" class="tag btn btn-default btn-xs" style="margin:6px;width:100% !important;" target="_blank">
                {{ ucfirst($translation->name)}}
            </a>
        </div>
    {{--@endif--}}
@endforeach
