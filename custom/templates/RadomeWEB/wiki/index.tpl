{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container">
    <div class="row">
        <div class="col-md-9">
            {*<ol class="breadcrumb">
                <li><a class="grey-link" href="{$WIKI_HOME_LINK}">{$WIKI}</a></li>
            </ol>*}
            <ol class="breadcrumb">
                {if $PAGE_RESULT == 0}
                    <li><a href="{$WIKI_HOME_LINK}" class="white-link">{$WIKI}</a></li>
                {elseif $PAGE_RESULT == 1}
                    {if $WP_TYPE}
                        <li><a href="{$WIKI_HOME_LINK}" class="white-link">{$WIKI}</a></li>
                        <div class="divider" style="margin: 0 12px"> / </div>
                        <li class="active"><a href="../{$WP_CATID}" class="white-link">{$WP_PARENT}</a></li>
                        <div class="divider" style="margin: 0 12px"> / </div>
                        <li class="active"><a href="{$WP_NAMEID}" class="white-link">{$WP_TITLE}</a></li>
                    {else}
                        <li><a href="{$WIKI_HOME_LINK}" class="white-link">{$WIKI}</a></li>
                        <div class="divider" style="margin: 0 12px"> / </div>
                        <li class="active"><a href="{$WP_NAMEID}" class="white-link">{$WP_TITLE}</a></li>
                    {/if}
                {elseif $PAGE_RESULT == 2}
                <a href="{$WIKI_HOME_LINK}" class="section">{$WIKI}</a>
                {/if} 
            </ol> 
            <div class="card">
                <div class="card-header header-theme">{$WIKI}</div>
                <div class="card-body">
                    {if $PAGE_RESULT == 0}
                        {$HOME_PAGE_CONTEXT}
                    {elseif $PAGE_RESULT == 1}
                        {$WP_CONTEXT}
                        
                    {elseif $PAGE_RESULT == 2}
                        {$WP_404_MESSAGE}
                    {/if}
                </div>
                {if $PAGE_RESULT == 1}
                <div class="card-footer">
                    <i class="fas fa-eye"></i> {$WP_VIEWS}
                </div>
                {/if}
            </div>
        </div>
        <div class="col-md-3">
            {include file='wiki/widgets/search-box.tpl'}
            {include file='wiki/widgets/side-menu.tpl'}
        </div>
    </div>
</div>

{include file='footer.tpl'}