{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="card">
        <div class="card-header header-theme">{$STATUS}</div>
        <div class="card-body">
            {if isset($CLICK_TO_COPY_TOOLTIP)}
            <div class="text-center">
                <span onclick="copyToClipboard('#ip')" data-toggle="tooltip" title="{$CLICK_TO_COPY_TOOLTIP}" style="cursor: pointer">{$CONNECT_WITH}</span>
            </div><br />
            {/if} {if count($SERVERS)} {assign var=i value=0} {foreach from=$SERVERS item=server name=serverArray} {if $i eq 0 OR ($i % 3) eq 0}
            <div class="card-deck text-center">
                {/if}
                <div class="card server" id="server{$server->id|escape}" data-id="{$server->id|escape}" data-bungee="{$server->bungee|escape}" data-players="{$server->player_list|escape}" data-bedrock="{$server->bedrock|escape}">
                    <div class="card-header header-theme" id="header{$server->id|escape}">
                        {$server->name|escape:'html'}
                    </div>
                    <div class="card-body" id="content{$server->id|escape}">
                        <i class="fa fa-spinner fa-pulse fa-2x" id="spinner{$server->id|escape}"></i>
                    </div>
                    {if !$server->bedrock}
                        <div class="extra content" id="server-players"></div>
                        {/if}                    
                </div>
                {if (($i+1) % 3) eq 0 OR $smarty.foreach.serverArray.last}
            </div><br /> {/if} {assign var=i value=$i+1} {/foreach} {else}
            <div class="alert alert-warning" style="text-align:center">{$NO_SERVERS}</div>
            {/if}
        </div>
    </div>
</div>
{include file='footer.tpl'}