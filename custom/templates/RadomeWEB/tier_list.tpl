{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">

<div class="container leaderboards">
    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                    <a class="item leaderboard_tab btn mb-1 btn-theme btn-lg btn-block" name="{$placeholder->name}"
                    id="tab-{$placeholder->name}"
                    onclick="showTable('{$placeholder->name}');">
                    {$placeholder->friendly_name}
                </a>
                {/foreach}
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="pills-tabContent">
                {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                    <div class="leaderboard_table" id="table-{$placeholder->name}"
                    style="display: none;">
                    <div class="card">
                        <div class="card-header header-theme">{$placeholder->friendly_name}</div>
                        <div class="card-body" style="overflow-x: auto">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{$PLAYER}</th>
                                        <th>{$SCORE}</th>
                                        <th>{$LAST_UPDATED}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$ROWLT1 item=data}
                                    <tr>
                                        <td>
                                            <img class="avatar-img" style="height: 30px; width: 30px;"
                                                src="{$data->avatar}" alt="{$data->username}">
                                            <span>{$data->username}</span>
                                        </td>
                                    </tr>
                                    {/foreach}
                            </table>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}