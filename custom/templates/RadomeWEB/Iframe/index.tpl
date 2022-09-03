{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">

	{foreach from=$IFRAME_LIST item=iframe}
	{* <h5 class="text-center" style="margin: 20px;" >{$iframe.name}</h5>
	<h5 class="text-center" style="margin: 20px;" ></h5>  *}
	<span class="text-center">{$iframe.description}</span>
	<div class="embed-responsive embed-responsive-{$iframe.size} mb-3">
		<iframe src="{$iframe.src}" frameborder="0"></iframe>
	</div>
	<span class="text-center">{$iframe.footer_description}</span>
	{/foreach}

</div>

{include file='footer.tpl'}