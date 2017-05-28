@if (App::make('sexodomeKernel')->getUA()->isMobile())
    <aside class="billboard">
        <div class="container text-center">
            @if (strlen(trim(App::make('sexodomeKernel')->getSite()->link_billboard_mobile)) > 0)
                <?php echo App::make('sexodomeKernel')->getSite()->link_billboard_mobile ?>
            @endif
        </div>
    </aside>
@else
    <aside class="billboard">
        <div class="container text-center">
            @if (strlen(trim(App::make('sexodomeKernel')->getSite()->link_billboard)) > 0)
                <?php echo App::make('sexodomeKernel')->getSite()->link_billboard ?>
            @endif
        </div>
    </aside>
@endif
