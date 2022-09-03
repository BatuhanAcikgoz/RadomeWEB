{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">
        <div class="col-md-3">
            {include file='user/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-theme">{$ALERTS}</div>
                <div class="card-body">
                    {nocache} {if count($ALERTS_LIST)} {foreach from=$ALERTS_LIST item=alert}
                    	{$alert->content}
                        <a class="white-link" href="{$alert->view_link}">{$CLICK_TO_VIEW}</a>
                        <span class="float-right">
				<span data-toggle="tooltip" data-trigger="hover" data-original-title="{$alert->date}">{$alert->date_nice}</span>
                        </span>
			<hr />
                    {/foreach}
		    <a href="{$DELETE_ALL_LINK}" class="btn btn-secondary">{$DELETE_ALL}</a>
                    {else} {$NO_ALERTS} {/if} {/nocache}
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}