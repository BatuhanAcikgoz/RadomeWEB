<div class="container">
      {if (isset($SKYFALL_VERSION) && $SKYFALL_VERSION|count_characters > 0) && ($SKYFALL_VERSION != $THEME_LOCAL_VERSION) && ($THEME_EXT_UPDATE eq "yes")}{if isset($LOGGED_IN_USER)}<button type="button" onClick="return false;" data-toggle="tooltip" data-placement="left" data-html="true" class="btn btn-danger btn-lg update-btn" id="update-btn" title="{$UPDATE_BTN} coldfiredzn.com/discord"><i class="fas fa-exclamation-triangle"></i></button>{/if}{/if}
      <a class="btn btn-secondary" href="#" id="scroll" {if isset($THEME_TS) && $THEME_TS eq 'icon'}style="right: 80px; display: none;"{else}style="right: 30px; display: none;"{/if}><i class="fas fa-arrow-up"></i></a>
      {if isset($THEME_TS) && $THEME_TS eq 'icon'}<button class="btn btn-secondary ts-icon" style="right: 30px;" id="theme-switch" onclick="themeFunction()"><i class="fas fa-palette"></i></button>{/if}
</div>
{foreach from=$TEMPLATE_JS item=script}
	{$script}
{/foreach}
{if isset($NEW_UPDATE)}
	{if $NEW_UPDATE_URGENT ne true}
		<script>
            	$(document).ready(function(){
                $('#closeUpdate').click(function(event){
                    event.preventDefault();
                    let expiry = new Date();
                    let length = 3600000;
                    expiry.setTime(expiry.getTime() + length);
                    $.cookie('update-alert-closed', 'true', { path: '/', expires: expiry });
                });
                if($.cookie('update-alert-closed') === 'true'){
                    $('#updateAlert').hide();
                }
            });
		</script>
	{/if}
{/if}