<?php $link_limit = 7; ?>

@if ($paginator->lastPage() > 1)
    <ul class="pagination justify-content-center">
        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a href="{{ route(str_replace("_page", "", $route_name), ['profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalinkCategory' => $categoryTranslation->permalink]) }}">First</a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
                $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    @if ($i == 1)
                        <a href="{{ route(str_replace("_page", "", $route_name), [ 'profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalinkCategory' => $categoryTranslation->permalink]) }}">{{ $i }}</a>
                    @else
                        <a href="{{ route($route_name, [ 'profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalinkCategory' => $categoryTranslation->permalink, 'page' => $i]) }}">{{ $i }}</a>
                    @endif

                </li>
            @endif
        @endfor
        <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a href="{{ route($route_name, [ 'profile' => App::make('sexodomeKernel')->getSite()->domain, 'permalinkCategory' => $categoryTranslation->permalink, 'page' => $paginator->lastPage()])  }}">Last</a>
        </li>
    </ul>
@endif
