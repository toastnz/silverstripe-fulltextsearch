<div class="contentBlock textBlock textBlock--fullwidth">
    <div class="contentBlock__wrap textBlock__wrap row">
        <div class="searchable">
            <div class="newSearch">
                <h1>Search Results</h1>
                $InPageSearchForm
                <%--<p>We are showing you the results for 'GoldenBay Corrugated', because you searched for 'corrugated'</p>--%>
            </div>
        </div>
    </div>
</div>
<% if $DeprecatedNames %>
    <% loop $DeprecatedNames %>
        <div class="contentBlock textBlock  textBlock--fullwidth">
            <div class="contentBlock__wrap row">
                <div class="column">
                    <h1 class="h2"><b class="colour--primary">$Title</b><b>is no longer manufactured by GoldenBay.</b></h1>
                    <%------------------------------------------------------------------%>
                    <%--Text Block Content--%>
                    <%------------------------------------------------------------------%>
                    <p>We've found the GoldenBay equivalents for you.</p>
                </div>

            </div>
        </div>
    <% end_loop %>
<% end_if %>

<% if $CompetitorNames %>
    <% loop $CompetitorNames %>
        <div class="contentBlock textBlock  textBlock--fullwidth">
            <div class="contentBlock__wrap row">
                <div class="column">
                    <h1 class="h4"><b>$Title</b></h1>
                    <h1 class="h2"><b>Excepteur sunt consectetur voluptate ut excepteur anim adipisicing amet ullamco labore ea voluptate pariatur quis.</b></h1>
                    <%------------------------------------------------------------------%>
                    <%--Text Block Content--%>
                    <%------------------------------------------------------------------%>
                    <p>Excepteur sunt consectetur voluptate ut excepteur anim adipisicing amet ullamco labore ea voluptate pariatur quis.</p>
                </div>

            </div>
        </div>
    <% end_loop %>
<% end_if %>

<% if $AlternativeNames %>
    <% loop $AlternativeNames %>
        <div class="contentBlock textBlock  textBlock--fullwidth">
            <div class="contentBlock__wrap row">
                <div class="column">
                    <h1 class="h4"><b>$Title</b></h1>
                    <h1 class="h2"><b>Excepteur sunt consectetur voluptate ut excepteur anim adipisicing amet ullamco labore ea voluptate pariatur quis.</b></h1>
                    <%------------------------------------------------------------------%>
                    <%--Text Block Content--%>
                    <%------------------------------------------------------------------%>
                    <p>Excepteur sunt consectetur voluptate ut excepteur anim adipisicing amet ullamco labore ea voluptate pariatur quis.</p>
                </div>

            </div>
        </div>
    <% end_loop %>
<% end_if %>

<% if $Synonyms %>
    <% loop $Synonyms %>
        <div class="contentBlock textBlock  textBlock--fullwidth">
            <div class="contentBlock__wrap row">
                <div class="column">

                    <p>We are showing you the results for '<span class="searchWord">{$Title}</span>', because you searched for '<span class="searchWord">{$Top.Query}</span>'</p>
                </div>

            </div>
        </div>
    <% end_loop %>
<% end_if %>

