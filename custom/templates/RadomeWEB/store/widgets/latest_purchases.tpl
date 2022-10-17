<div class="card" id="widget-featured-package">
    <div class="content">
        <div class="card-header header-theme">{$LATEST_PURCHASES}</div>
        <table class="table table-hover">
        <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>İsim</th>
                  <th class="text-center">Ürün</th>
                  <th class="text-center">Tarih</th>
                </tr>
        </thead>
        {if isset($LATEST_PURCHASES_LIST) && count($LATEST_PURCHASES_LIST)}
            {foreach from=$LATEST_PURCHASES_LIST item=purchase name=purchaseLoop}
                <tr>
                        <td class="text-center">
                        <img class="avatar" src="{$purchase.avatar}" alt="{$purchase.username}" width="32" height="32" style="max-width: 150px;">
                        </td>
                        <td><a class="" {if $purchase.user_id}href="{$purchase.profile}" data-poload="{$USER_INFO_URL}{$purchase.user_id}" data-html="true"{/if} style="{$purchase.style|replace:';':''}!important;margin:5px">{$purchase.username}</a></td>
                        <td class="text-center">{$purchase.price}{$purchase.currency_symbol} {$product_name}</td>
                        <td class="text-center">{$purchase.date_friendly}</td>
                </tr>
                {if not $smarty.foreach.purchaseLoop.last}{/if}
            {/foreach}
        {else}
            <div class="card-body">{$NO_PURCHASES}</div>
        {/if}
        </table> 
    </div>
</div>