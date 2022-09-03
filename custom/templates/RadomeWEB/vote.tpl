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
                <div class="card-body text-center">
                    {if isset($MESSAGE_ENABLED)}
                        {$MESSAGE}<br /><br />
                    {/if}
                    <div class="row d-flex justify-content-center">
                        {foreach from=$SITES item=site}
                            <div class="col-md-4 mb-2">
                                <a class="btn btn-block btn-theme" href="{$site.url}" target="_blank" role="button"
                                    rel="noopener nofollow">{$site.name}</a>
                            </div>
                        {/foreach}
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