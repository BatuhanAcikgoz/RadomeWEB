{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">

        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
            {if count($STAFF_GROUPS)} 
                {foreach from=$STAFF_GROUPS item=group} 
                    {if count($group.members)}
                        <div class="card">
                            <div class="card-header staff-header" style="background: {$group.color};">{$group.name}</div>
                            <div class="card-body">
                                <div class="row">
                                    {foreach from=$group.members item=member}
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <img src="{$member.avatar}" class="avatar-img staff-img" alt="{$member.username}">
                                                <a href="{$member.profile}" style="color: {$group.color};">{$member.username}</a>
                                                {if !empty($member.title)}
                                                    <br />{$member.title}
                                                {/if}
                                            </div>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    {/if} 
                {/foreach} 
            {/if}
        </div>

        {if count($WIDGETS_RIGHT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

    </div>
</div>
{include file='footer.tpl'}