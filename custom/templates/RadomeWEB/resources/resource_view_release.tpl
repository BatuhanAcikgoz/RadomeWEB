{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="card">
        <div class="card-header header-theme">{$VIEWING_RELEASE}<span class="float-right"><a href="{$BACK_LINK}" class="btn btn-theme btn-sm">{$BACK}</a></span></div>
        <div class="card-body">
            {$DESCRIPTION}
            <hr />
            <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$DATE_FULL}">{$DATE}</span> {if isset($DOWNLOAD_URL)}
            <br /><br /><a href="{$DOWNLOAD_URL}" class="btn btn-theme" target="_blank">{$DOWNLOAD}</a> {elseif isset($PURCHASE_FOR_PRICE)}
            <a {if isset($PURCHASE_LINK)}href="{$PURCHASE_LINK}" {else}disabled {/if}class="btn btn-theme">{$PURCHASE_FOR_PRICE}</a> {elseif isset($PAYMENT_PENDING)}
            <button type="button" disabled class="btn btn-theme">{$PAYMENT_PENDING}</button> {/if}
        </div>
    </div>
</div>
{include file='footer.tpl'}