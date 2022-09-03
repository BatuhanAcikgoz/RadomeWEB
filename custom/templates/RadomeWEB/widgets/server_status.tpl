<div class="card">
    <div class="card-header header-theme">{$SERVER_STATUS}</div>
    <div class="card-body">
        {if isset($SERVER)}
            {$SERVER.name}
            {if $SERVER.status_value eq 1}
                <span class="badge badge-success float-right">{$ONLINE}</span>
            {else}
                <span class="badge badge-danger float-right">{$OFFLINE}</span>
            {/if}

            {if $SERVER.status_value eq 1}
                <hr />
                {$ONLINE}: <strong>{$SERVER.player_count} / {$SERVER.player_count_max}</strong><br />
                {if isset($VERSION)}{$VERSION}<br />{/if}
                {$IP}: <strong>{$SERVER.join_at}</strong><br />
                {if isset($SERVER.format_player_list) && count($SERVER.format_player_list)}
                    {foreach from=$SERVER.format_player_list item=player}
                        <a href="{$player.profile}" data-toggle="tooltip" data-original-title="{$player.username}"><img class="avatar-img" src="{$player.avatar}" alt="{$player.username}" style="width: 30px; margin-bottom: 5px" /></a>
                    {/foreach}
                {/if}
            {/if}
        {else}
            {$NO_SERVERS}
        {/if}
    </div>
</div>