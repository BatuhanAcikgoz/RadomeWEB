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
            {if isset($NO_PACKAGES)}
                <div class="alert alert-info">
                    {$NO_PACKAGES}
                </div>
            {else}
                <div class="row">
                    {foreach from=$PACKAGES item=package name=packageArray}
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header header-theme">{$package.name}</div>
                                <div class="card-body">
                                    {if $package.image}
                                        <center><img class="rounded" style="max-width: 100%;" src="{$package.image}"
                                            alt="{$package.name}"></center><br /> {/if}
                                    <span class="store-text-large">{if $package.sale_active}<span
                                                class="store-sale">{$CURRENCY}{$package.price}
                                        </span>{/if}{$CURRENCY}{$package.real_price}</span>
                                    <button role="button" class="btn btn-theme pull-right" data-toggle="modal"
                                        data-target="#modal{$package.id}">{$BUY}</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal{$package.id}" tabindex="-1" role="dialog"
                            aria-labelledby="modal{$package.id}Label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content text-center">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modal{$package.id}Label">{$package.name}</h4>
                                    </div>
                                    {if $package.description}
                                        <div class="modal-body">
                                            <div class="forum_post">
                                                {$package.description}
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="modal-footer">
                                        <span class="mr-auto store-text-large">{$CURRENCY}{$package.real_price}</span>
                                        <a href="{$package.link}" target="_blank" rel="nofollow noopener"
                                            class="btn btn-theme">{$BUY}</a>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CLOSE}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            {/if}
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