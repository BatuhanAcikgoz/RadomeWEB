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

        <h2 class="card-header header-theme" style="display:inline;">{$STORE} &raquo; {$CHECKOUT}</h2>
        <br style="margin-bottom: 10px;">
        {include file='store/navbar.tpl'}
        
        <br style="margin-bottom: 35px;">
        
        {if isset($SUCCESS)}
          <div class="btn btn-success btn-lg">
            <i class="fas fa-check"></i>
            <div class="content">
             {$SUCCESS}
            </div>
          </div>
        {/if}
                    
        {if isset($ERRORS)}
          <div class="ui negative icon message">
            <div class="content">
            <i class="fas fa-times"></i>
              {foreach from=$ERRORS item=error}
                {$error}<br />
              {/foreach}
            </div>
          </div>
        {/if}
        
        <form class="col-md-12" action="" method="post" id="forms">
          <h3>{$SHOPPING_CART}</h3>
          <br>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{$NAME}</th>
                <th>{$OPTIONS}</th>
                <th>{$QUANTITY}</th>
                <th>{$PRICE}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {foreach from=$SHOPPING_CART_LIST item=item}
                <tr>
                  <td>{$item.name}</td>
                  <td>{if count($item.fields)} {foreach from=$item.fields item=field name=fields}<strong>{$field.description}</strong>: {$field.value}{if not $smarty.foreach.fields.last}</br>{/if}{/foreach} {/if}</td>
                  <td>{$item.quantity}</td>
                  <td>{$CURRENCY_SYMBOL}{$item.price} {$CURRENCY}</td>
                  <td><a href="{$item.remove_link}" class="btn btn-danger"><i class="fas fa-times"></i></a></td>
                </tr>
              {/foreach}
            </tbody>
          </table>
        
          <h4>{$TOTAL_PRICE} {$TOTAL_PRICE_VALUE} {$CURRENCY}</h4>
          <br>
          
          <h3>{$PAYMENT_METHOD}</h3>
          <hr />
          {foreach from=$PAYMENT_METHODS item=gateway}
            <div class="field">
              <div class="form-group custom-control custom-switch">
                <input type="radio" name="payment_method" value="{$gateway.name}" required>
                <label>{$gateway.displayname}</label>
              </div>
            </div>
          {/foreach}
          <br>        
        
          <h3>{$PURCHASE}</h3>
          <hr />
          <div class="field">
            <div class="form-group custom-control custom-switchx" style="display:inline;">
              <input type="hidden" name="token" value="{$TOKEN}">
              <input type="checkbox" name="t_and_c" value="1" required> <label>{$AGREE_T_AND_C_PURCHASE}</label>
              <span class="left floated"><input type="submit" class="btn btn-theme" value="{$PURCHASE} &raquo;"></span>
            </div>
          </div>
          </br>
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