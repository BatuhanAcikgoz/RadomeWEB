<div class="card">
    <div class="card-header header-theme">{$ONLINE_STAFF}</div>
    <div class="card-body">
        {if isset($ONLINE_STAFF_LIST)} {foreach from=$ONLINE_STAFF_LIST name=online_staff_arr item=user}
        <div class="row">
            <div class="col-2 col-md-3 col-lg-4 col-xl-3">
                <img src="{$user.avatar}" alt="{$user.nickname}" class="avatar-img" style="max-width: 50px;">
            </div>
            <div class="col-10 col-md-9 col-lg-8 col-xl-9">
                <a style="{$user.style}" href="{$user.profile}" data-poload="{$USER_INFO_URL}{$user.id}" data-html="true" data-placement="top">{$user.username}</a><br /> {if $user.group} {$user.group} {else} {$user.title} {/if}
            </div>
        </div>
        {if not $smarty.foreach.online_staff_arr.last}<br />{/if} {/foreach} {else} {$NO_STAFF_ONLINE} {/if}
    </div>
    <div class="card-footer">
        {$TOTAL_ONLINE_STAFF}
    </div>
</div>