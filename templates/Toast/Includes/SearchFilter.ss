<%----------------------------------------------------------------
Search Filter
----------------------------------------------------------------%>

<section class="searchFilter" style="">

    <div class="searchFilter__wrap">
        <div class="searchFilter__wrap__grid">
            <% loop $Filters() %>
                <a href="$Link" data-tab="$Title" data-id="$Title" data-count="{$Count}" class="searchFilter__wrap__grid__item [ js-search-filter-trigger ] <% if $Top.TabDefault == $Title %>active<% end_if %>"><b> $Title</b></a>
            <% end_loop %>
            <div class="searchFilter__wrap__grid__indicator [ js-search-filter-indicator ]"></div>
        </div>
    </div>

</section>
