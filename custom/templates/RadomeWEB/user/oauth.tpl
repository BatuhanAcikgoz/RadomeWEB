{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container" id="alerts">
    <div class="row">
        <div class="col-md-3">
            {include file='user/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <h3 class="card-header header-theme">
                    {$OAUTH}
                </h3>
                {if isset($SUCCESS_MESSAGE)}
                <div class="ui success icon message">
                    <i class="check icon"></i>
                    <div class="card-body">
                        <div class="header">{$SUCCESS} <p>{$SUCCESS_MESSAGE}</p></div>
                        {$SUCCESS_MESSAGE}
                    </div>
                </div>
                {/if}
                {if isset($ERROR_MESSAGE)}
                <div class="ui negative icon message">
                    <i class="x icon"></i>
                    <div class="card-body">
                        <div class="header">{$ERROR}</div>
                        {$ERROR_MESSAGE}
                    </div>
                </div>
                {/if}
                <div class="ui middle aligned relaxed selection list">
                    {nocache}
                    {if count($OAUTH_PROVIDERS)}
                    <table class="ui striped table">
                        <tbody>
                            {foreach $OAUTH_PROVIDERS as $provider_name => $provider_data}
                            <tr>
                                <td>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-4" style="line-height: 2.5rem;">
                                                {if $provider_data.icon}
                                                <i class="{$provider_data.icon} fa-lg">&nbsp;</i>
                                                {/if}
                                                {$provider_name|ucfirst}
                                            </div>
                                            <div class="col-md-4">
                                                {if isset($USER_OAUTH_PROVIDERS[$provider_name])}
                                                <div class="res right floated">
                                                    <code style="line-height: 2.5rem;">{$USER_OAUTH_PROVIDERS[$provider_name]->provider_id}</code>
                                                </div>
                                                {/if}
                                            </div>
                                            <div class="col-md-4">
                                                {if isset($USER_OAUTH_PROVIDERS[$provider_name])}
                                                <a class="btn btn-theme res right floated" href="#" data-toggle="modal"
                                                    data-target="#modal-unlink-{$provider_name}">{$UNLINK}</a>
                                                {else}
                                                <a class="ui mini green button" href="#" data-toggle="modal"
                                                    data-target="#modal-link-{$provider_name}">{$LINK}</a>
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                    {else}
                    <div class="ui info message">
                        <div class="card-body">
                            {$NO_PROVIDERS}
                        </div>
                    </div>
                    {/if}
                    {/nocache}
                </div>
            </div>
        </div>
    </div>
</div>

{foreach $OAUTH_PROVIDERS as $provider_name => $provider_data}
<div class="ui small modal" id="modal-unlink-{$provider_name}">
    <div class="header">
        {$UNLINK} {$provider_name|ucfirst}
    </div>
    <div class="card-body">
        {$OAUTH_MESSAGES[$provider_name]['unlink_confirm']}
    </div>
    <div class="actions">
        <a class="ui negative button">{$NO}</a>
        <form class="ui form" action="" method="post" style="display: inline">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="hidden" name="action" value="unlink">
            <input type="hidden" name="provider" value="{$provider_name}">
            <input type="submit" class="ui green button" value="{$YES}">
        </form>
    </div>
</div>

<div class="ui small modal" id="modal-link-{$provider_name}">
    <div class="header">
        {$LINK} {$provider_name|ucfirst}
    </div>
    <div class="card-body">
        {$OAUTH_MESSAGES[$provider_name]['link_confirm']}
    </div>
    <div class="actions">
        <a class="ui negative button">{$NO}</a>
        <a class="ui green button" href="{$provider_data.url}">{$CONFIRM}</a>
    </div>
</div>
{/foreach}

{include file='footer.tpl'}