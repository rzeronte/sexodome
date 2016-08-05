<script type="text/javascript">
    var data = [
        @foreach ($scene->tags()->get() as $tag)
        <?php $translation = $tag->translations()->where('language_id',$language->id)->first(); ?>
        '<?= $translation->name?>',
        @endforeach
        ];
    var dataCategories = [
        @foreach ($scene->categories()->get() as $category)
        <?php $translation = $category->translations()->where('language_id',$language->id)->first(); ?>
        '<?= $translation->name?>',
        @endforeach
        ];

    $('.js-tags-<?=$scene->id?>').tagEditor({
        initialTags: data,
        autocomplete: { 'source': $("#ajaxUrls").attr('data-tags-url'), minLength: 3 }
    });

    $('.js-categories-<?=$scene->id?>').tagEditor({
        initialTags: dataCategories,
        autocomplete: { 'source': $("#ajaxUrls").attr('data-categories-url'), minLength: 3 }
    });
</script>