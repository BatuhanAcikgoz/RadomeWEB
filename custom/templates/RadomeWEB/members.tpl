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
            <div class="card">
                <div class="card-header header-theme">{$TITLE}</div>
                <div class="card-body">
                    <a href="{$ALL_LINK}" class="btn btn-theme mb-2">{$DISPLAY_ALL}</a>
                    {foreach from=$GROUPS item=groups}
                        <a href="{$groups.link}" class="btn btn-theme mb-2">{$groups.name}</a>
                    {/foreach}
                    <br /><br />
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dataTables-users" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{$USERNAME}</th>
                                    <th>{$GROUP}</th>
                                    <th>{$CREATED}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$MEMBERS item=member}
                                    <tr>
                                        <td><a href="{$member.profile}"><img src="{$member.avatar}" class="avatar-img"
                                                    style="height:35px; width:35px;" alt="{$member.nickname}" /></a> <a
                                                style="color:{$member.group_colour};"
                                                href="{$member.profile}">{$member.nickname}</a></td>
                                        <td>{foreach from=$member.groups item=group}{$group}{/foreach}</td>
                                        <td>{$member.joined}</td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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