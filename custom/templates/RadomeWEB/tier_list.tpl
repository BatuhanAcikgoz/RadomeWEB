{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">

    <div class="container leaderboards">
        <div class="row justify-content-center">
            {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="item leaderboard_tab btn mb-1 btn-theme btn-lg btn-block" name="{$placeholder->name}"
                            id="tab-{$placeholder->name}" onclick="showTable('{$placeholder->name}');">
                            {$placeholder->friendly_name}
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content" id="pills-tabContent">
                    {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                        <div class="leaderboard_table" id="table-{$placeholder->name}" style="display: none;">
                            <div class="card">
                                <div class="card-header header-theme">{$placeholder->friendly_name}</div>
                                <div class="row">
                                    <div class="card-body" style="overflow-x: auto">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tier 1</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$TIER_LIST_HT1_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #035e9b;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                                {foreach from=$TIER_LIST_LT1_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #5dade2 ;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-body" style="overflow-x: auto">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tier 2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$TIER_LIST_HT2_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #035e9b;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                                {foreach from=$TIER_LIST_LT2_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #5dade2 ;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-body" style="overflow-x: auto">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tier 3</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$TIER_LIST_HT3_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #035e9b;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                                {foreach from=$TIER_LIST_LT3_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #5dade2 ;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-body" style="overflow-x: auto">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tier 4</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$TIER_LIST_HT4_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #035e9b;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                                {foreach from=$TIER_LIST_LT4_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #5dade2 ;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-body" style="overflow-x: auto">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tier 5</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$TIER_LIST_HT5_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #035e9b;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                                {foreach from=$TIER_LIST_LT5_DATA item=data}
                                                    {if $data->name eq $placeholder->name}
                                                        <tr>
                                                            <td>
                                                                <img class="avatar-img" style="height: 30px; width: 30px;"
                                                                    src="{$data->avatar}" alt="{$data->username}">
                                                                <a href="{$data->profile_url}" style="color:  #5dade2 ;"
                                                                    data-poload="{$USER_INFO_URL}{$data->user_id}" data-html="true"
                                                                    data-placement="top">{$data->username}</a>
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>

{include file='footer.tpl'}