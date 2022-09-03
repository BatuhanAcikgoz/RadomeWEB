{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">
    {*
        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">*}
        <div class="{if count($WIDGETS)}col-md-9{else}col-md-12{/if}">
            <div class="row store-nav">
                <div class="col-md-12">
                    {if $SHOW_HOME_TAB eq '1'}<a href="{$HOME_URL}" class="btn btn-theme">{$HOME}</a>{/if}
                    {foreach from=$CATEGORIES item=category}
                        {if count($category.subcategories)}
                            <div class="btn-group" role="group" aria-label="storedropdown">
                                <a class="btn btn-theme dropdown-toggle" href="#" data-toggle="dropdown">
                                    {$category.title}
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{$category.url}">{$category.title}</a>
                                    {foreach from=$category.subcategories item=subcategory}
                                    <a class="dropdown-item" href="{$subcategory.url}">{$subcategory.title}</a> {/foreach}
                                </div>
                            </div>
                        {else}
                        <a href="{$category.url}" class="btn btn-theme">{$category.title}</a> {/if}
                    {/foreach}
                    <a class="btn btn-theme pull-right" href="{$STORE_URL}" target="_blank">{$VIEW_FULL_STORE}</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header header-theme">
                    {$STORE}
                </div>
                <div class="card-body">
                    {$CONTENT}
                </div>
            </div>
        </div>
            {*   {if count($WIDGETS_RIGHT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}*}
        
    {if count($WIDGETS)}
        <div class="col-md-3">
            {foreach from=$WIDGETS item=widget}
                {$widget}
            {/foreach}
        </div>
    {/if}
    </div>
</div>
{include file='footer.tpl'}