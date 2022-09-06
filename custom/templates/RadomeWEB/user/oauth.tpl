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
<div class="modal fade" id="modal-unlink-{$provider_name}" tabindex="-1" role="dialog" aria-hidden="true">    
<div class="modal-dialog" role="document" style="transform: translate(0,150px);">
<div class="modal-content">
    <div class="modal-header">
        {$UNLINK} {$provider_name|ucfirst}
    </div>
    <div class="modal-body">
        {$OAUTH_MESSAGES[$provider_name]['unlink_confirm']}
    </div>
    <div class="modal-footer">
        <a class="btn btn-secondary">{$NO}</a>
        <form class="btn btn-primary" action="" method="post" style="display: inline">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="hidden" name="action" value="unlink">
            <input type="hidden" name="provider" value="{$provider_name}">
            <input type="submit" class="" value="{$YES}">
        </form>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modal-link-{$provider_name}" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog" role="document" style="transform: translate(0,150px);">
<div class="modal-content">
    <div class="modal-header">
        {$LINK} {$provider_name|ucfirst}
    </div>
    <div class="modal-body">
        {$OAUTH_MESSAGES[$provider_name]['link_confirm']}
    </div>
    <div class="modal-footer">
        <a class="btn btn-secondary">{$NO}</a>
        <a class="btn btn-primary" href="{$provider_data.url}">{$CONFIRM}</a>
    </div>
</div>
</div>
</div>
{/foreach}

{include file='footer.tpl'}