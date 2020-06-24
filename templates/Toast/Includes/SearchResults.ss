<%-- <div class="search__masonry__sizer [ js-masonry-sizer ]" ></div>
<% with $SearchResult %>
    <div class="search__masonry [ js-masonry ]"  data-default-filter="{$Top.TabDefault}">
        <% if $Matches %>
            <% loop $Matches %>
                <% include Toast/Includes/SearchItem %>
            <% end_loop %>
        <% else %>
            <h5>Sorry there are no results for that query</h5>
        <% end_if %>
    </div>
<% end_with %> --%>


<div class="search__masonry__sizer [ js-masonry-sizer ]" ></div>
<div class="clearfix"></div>

<div class="search__masonry [ js-masonry ]"  data-default-filter="{$Top.TabDefault}">
    <% if $results.Matches %>
        <% loop $results.Grouped %>
            <% include Toast/Includes/SearchItem %>
        <% end_loop %>
    <% else %>
        <h5>Sorry there are no results for that query</h5>
    <% end_if %>
</div>
