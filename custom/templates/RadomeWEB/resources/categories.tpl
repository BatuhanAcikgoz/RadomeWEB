<div class="card">
    <div class="card-header header-theme">{$CATEGORIES_TITLE}</div>
    <div class="card-body">
        {foreach from=$CATEGORIES item=item}
        <a class="btn btn-theme btn-block" href="{$item.link}">{$item.name}</a> {/foreach}
    </div>
</div>