{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="card">
        <div class="card-header header-theme">{$FORUM_SEARCH}</div>
        <div class="card-body">
            {if isset($ERROR)}
            <div class="alert alert-danger">
                {$ERROR}
            </div>
            {/if}
            <form class="form-horizontal" role="form" method="post" action="{$FORM_ACTION}">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="forum_search" placeholder="{$SEARCH}">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <span class="input-group-btn">
		    	<button type="submit" class="btn btn-theme">
		        	<i class="fa fa-search"></i>
		        </button>
		    </span>
                </div>
            </form>
        </div>
    </div>
</div>
{include file='footer.tpl'}