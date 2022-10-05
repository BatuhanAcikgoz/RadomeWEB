{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">

<div class="container" style="min-height: calc(-175.133px + 100vh);">
  <div class="row">
  
    {if count($WIDGETS_LEFT)}
      <div class="col-md-3">
        {foreach from=$WIDGETS_LEFT item=widget}
          {$widget}
        {/foreach}
      </div>
    {/if}
    
       <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
      <div class="ui segment">


        {include file='store/navbar.tpl'}
        <h1 style="display:inline;">{$STORE} &raquo; {$ACTIVE_CATEGORY}</h1>                
        <div class="ui bottom attached segment">
          {if isset($ERRORS)}
            <div class="ui negative icon message">
              <i class="x icon"></i>
              <div class="content">
                {foreach from=$ERRORS item=error}
                  {$error}<br />
                {/foreach}
              </div>
            </div>
          {/if}
                    
          <h3>{$PLEASE_ENTER_USERNAME}</h3>
          <form class="ui form" action="" method="post">
            <div class="field">
              <input type="text" name="username" id="username" placeholder="{$PLEASE_ENTER_USERNAME}">
            </div>
            <div class="field">
              <input type="hidden" name="token" value="{$TOKEN}">
              <input type="hidden" name="type" value="store_login">
              <input type="submit" class="ui primary button" value="{$CONTINUE} &raquo;">
            </div>
          </form>
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