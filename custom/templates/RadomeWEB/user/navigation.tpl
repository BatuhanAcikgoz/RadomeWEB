<div class="container">
	{foreach from=$CC_NAV_LINKS key=name item=item}
		<a class="btn btn-theme btn-lg btn-block" href="{$item.link}" target="{$item.target}">{$item.title}</a>
	{/foreach}
	<br />
</div>