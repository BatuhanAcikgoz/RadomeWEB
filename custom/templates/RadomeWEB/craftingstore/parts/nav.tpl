<div class="row store-nav">
    <div class="col-md-12">
        <a href="{$HOME_URL}" class="btn btn-theme">{$HOME}</a> {foreach from=$CATEGORIES item=category} {if count($category.subCategories)}
        <div class="btn-group" role="group" aria-label="storedropdown">
            <a class="btn btn-theme dropdown-toggle" href="#" data-toggle="dropdown">
            {$category.name}
            </a>
            <div class="dropdown-menu">
                {foreach from=$category.subCategories item=subcategory}
                <a class="dropdown-item" href="{$subcategory.link}">{$subcategory.name}</a> {/foreach}
            </div>
        </div>
        {else}
        <a href="{$category.link}" class="btn btn-theme">{$category.name}</a> {/if} {/foreach}
        <a class="btn btn-theme pull-right" href="{$STORE_URL}" target="_blank">{$VIEW_FULL_STORE}</a>
    </div>
</div>