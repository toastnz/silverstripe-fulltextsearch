
    <% if $Link %>
        <a href="{$Link}"  class="search__masonry__item [ js-masonry-item ]" data-filters="$FilterForTemplate">
            <div class="search__masonry__item__image" style="background-image:url('{$getThumbnailForTemplate().Fill(700,400).URL}');">
                <span class="search__masonry__item__image__overlay"></span>
                <% if $getClassNameForSearch %>
                    <div class="gridBlock__wrap__item__tag">
                        <div class="gridBlock__wrap__item__tag__shape"></div>
                        <p>$getClassNameForSearch</p>
                    </div>            
                <% end_if %>
            </div>
            <div class="search__masonry__item__content">
                
                <h5><b>$Title</b></h5>
    
                <p>$Summary</p>
                <span href="{$Link}?search={$Query}" class="[ js-magnify ]">
                    Read More $SVG('downloads-chevron')
                </span>
            </div>
        </a>
    <% end_if %>