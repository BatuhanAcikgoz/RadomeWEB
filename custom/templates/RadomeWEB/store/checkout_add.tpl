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
      <div class="card">

        <h1 class="card-header header-theme" style="display:inline;">{$STORE} &raquo; {$PRODUCT_NAME}</h1>
        {include file='store/navbar.tpl'}
        
        </br>
        
        {if isset($SUCCESS)}
          <div class="btn btn-success btn-lg">
            <i class="check icon"></i>
            <div class="content">
             {$SUCCESS}
            </div>
          </div>
        {/if}
                    
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
        
        <form class="ui form" action="" method="post" id="forms">
          <h3 style="margin: 20px;">{$PRODUCT_NAME}</h3>
          <div class="ui divider"></div>
          
          {foreach from=$PRODUCT_FIELDS item=field}
            <div class="field" style="margin: 20px;">
              <label for="{$field.id}">{$field.description} {if $field.required} <span class="text-danger"><strong>*</strong></span>{/if}</label>
              
              {if $field.type == "1"}
                <input type="text" name="{$field.id}" id="{$field.id}" value="{$field.value}" placeholder="{$field.description}" {if $field.required}required{/if}>
              {elseif $field.type == "2"}
                <select class="rounded-lg dropdown" name="{$field.id}" id="{$field.id}" {if $field.required}required{/if}>
                  {foreach from=$field.options item=option}
                  <option value="{$option}" {if $option eq $field.value} selected{/if}>{$option}</option>
                  {/foreach}
                </select>
              {elseif $field.type == "3"}
                <textarea name="{$field.id}" id="{$field.id}" {if $field.required}required{/if}>{$field.value}</textarea>
              {elseif $field.type == "4"}
                <input type="number" name="{$field.id}" id="{$field.id}" value="{$field.value}" placeholder="{$field.description}" {if $field.required}required{/if}>
              {elseif $field.type == "5"}
                <input class="rounded-lg" type="email" name="{$field.id}" id="{$field.id}" value="{$field.value}" placeholder="{$field.description}" {if $field.required}required{/if}>
              {elseif $field.type == "6"}
                {foreach from=$field.options item=option}
                  <div class="field">
                    <div class="ui radio checkbox">
                      <input type="radio" name="{$field.id}" value="{$option}" {if $field.required}required{/if}>
                      <label>{$option}</label>
                    </div>
                  </div>
                {/foreach}
              {elseif $field.type == "7"}
                {foreach from=$field.options item=option}
                  <div class="field">
                    <div class="ui checkbox">
                      <input type="checkbox" name="{$field.id}[]" value="{$option}">
                      <label>{$option}</label>
                    </div>
                  </div>
                  <hr />
                {/foreach}
              {/if}
            </div>
          {/foreach}
          
          <div class="field" style="margin: 20px;">
              <input type="hidden" name="token" value="{$TOKEN}">
              <input type="submit" class="btn btn-theme primary" value="{$CONTINUE}">
          </div>

        </form>
        
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