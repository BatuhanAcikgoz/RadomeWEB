<div class="container">
    <div class="row">
        <div class="col-4">
            <a href="{$PROFILE}"><img src="{$AVATAR}" alt="{$USERNAME}" class="avatar-img popover-img"/></a>
        </div>
        <div class="col-8">
            <a href="{$PROFILE}" style="font-size: 16px; {$STYLE}"><b>{$NICKNAME}</b></a><br /> {if count($GROUPS)} {foreach from=$GROUPS item=group}
            <span class="popover-15px">{$group}</span> {/foreach} {else}
            <div class="badge badge-secondary popover-15px">{$GUEST}</div>
            {/if}
        </div>
    </div>
    <br />{if count($GROUPS)}
    <div class="popover-text-btm">
        {if isset($REGISTERED)}<small>{$REGISTERED}</small><br />{/if} {if isset($TOPICS) && isset($POSTS)}
        <small>{$TOPICS}</small> &bull; <small>{$POSTS}</small> {/if}
    </div>
    {/if}
</div>