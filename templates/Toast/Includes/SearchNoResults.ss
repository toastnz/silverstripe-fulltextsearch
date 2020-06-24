<div class="contentBlock textBlock  textBlock--fullwidth">
    <div class="contentBlock__wrap row">
        <div class="column">
            <h1 class="h2"><b>Sorry there are no results for "$Query"</b></h1>
            <h1 class="h4"><b></b></h1>
            <%------------------------------------------------------------------%>
            <%--Text Block Content--%>
            <%------------------------------------------------------------------%>
            <%-- <% if $Suggestions %>
                <p class="suggestion">Did you mean 
                    <% loop $Suggestions %>
                        <% if $first %>
                            <a href='$Link'>$Title</a>
                        <% else %>
                            , <a href='$Link'>$Title</a>
                        <% end_if %>

                    <% end_loop %>
                ?</p>
            <% end_if %> --%>
            
                
            <% with $SearchResult %>
                <% if $Suggestion %>
                    <p class="suggestion">Did you mean <a href='$SuggestionLink'>$Suggestion</a>?
                    </p>
                <% else %>
                    <p>Please use the form above to change your search phrase and criteria.</p>
                <% end_if %>

            <% end_with %>



            <p class="suggestion">Are you looking for:
                <% loop $Suggestions %>
                    <a href='/search-results/results?Search={$Title}'>$Title</a>
                    <% if $Last %>
                        ?
                    <% else %>
                        ,
                    <% end_if %>
                <% end_loop %>
            </p>
            
            <% if $noResultsRecomendations($Query) %>
                <p class="suggestion">Are you looking for:
                    <% loop $noResultsRecomendations($Query) %>
                        <a href='$SearchDidYouMeanLink'>$Title</a>
                        <% if $Last %>
                            ?
                        <% else %>
                            ,
                        <% end_if %>
                    <% end_loop %>
                </p>
            <% end_if %>
        </div>

    </div>
</div>