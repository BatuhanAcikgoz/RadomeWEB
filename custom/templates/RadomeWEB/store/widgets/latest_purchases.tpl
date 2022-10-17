<div class="card" id="widget-featured-package">
    <div class="content">
        <div class="card-header header-theme">{$LATEST_PURCHASES}</div>
        {if isset($LATEST_PURCHASES_LIST) && count($LATEST_PURCHASES_LIST)}
            {foreach from=$LATEST_PURCHASES_LIST item=purchase name=purchaseLoop}
                <div class="card-body">
                    <div class="item">
                        <div class="content">
                        <img class="avatar" src="{$purchase.avatar}" alt="{$purchase.username}" width="48" height="48">
                            <a class="" {if $purchase.user_id}href="{$purchase.profile}" data-poload="{$USER_INFO_URL}{$purchase.user_id}" data-html="true"{/if} style="{$purchase.style|replace:';':''}!important;margin:5px">{$purchase.username}</a>
                            {$purchase.price}{$purchase.currency_symbol} | {$purchase.name} | {$purchase.date_full}
                        </div>
                    </div>
                </div>
                {if not $smarty.foreach.purchaseLoop.last}<div class="card-body"></div>{/if}
            {/foreach}
        {else}
            <div class="card-body">{$NO_PURCHASES}</div>
        {/if}
    </div>
</div>