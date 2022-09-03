{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container" style="min-height: calc(-175.133px + 100vh);">

{if count($ERRORS)}
	<div class="alert alert-danger">
		{foreach from=$ERRORS item=error}
		<li>{$error}</li>
		{/foreach}
	</div>
	{/if}

	{if count($SUCCESS)}
	<div class="alert alert-success">
		{foreach from=$SUCCESS item=ok}
		<li>{$ok}</li>
		{/foreach}
	</div>
	{/if}
<div class="card">
<div class="card-body">



	{if $VALIDATION != true}
	<p class="text-center text-warning font-weight-bold">{$VALIDATION_INFO} {$VALIDATION_CODE}</p>
	<p class="text-center text-danger font-weight-bold">{$IS_VALIDATION}</p>
	{else}
	<p class="text-center text-warning font-weight-bold">{$VERIFITED}</p>
	{/if}


</div>
</div>

</div>

{include file='footer.tpl'}