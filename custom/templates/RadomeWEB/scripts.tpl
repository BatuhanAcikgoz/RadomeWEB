<div class="container">
      <a class="btn btn-secondary" href="#" id="scroll" {if isset($THEME_TS) && $THEME_TS eq 'icon'}style="right: 80px; display: none;"{else}style="right: 30px; display: none;"{/if}><i class="fas fa-arrow-up"></i></a>
</div>
{foreach from=$TEMPLATE_JS item=script}
	{$script}
{/foreach}
{/if}